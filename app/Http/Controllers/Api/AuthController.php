<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\Car;
use App\Models\FirebaseToken;
use App\Models\Settings;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $car_plate_digits = Car::NumbersToEnglish($request->input('car_plate_digits'));
            $request->merge(['car_plate_digits' => (int) $car_plate_digits]);
            $car_plate = $request->input('car_plate_digits') . $request->input('car_plate_characters');

            $validator = Validator::make(array_merge(compact('car_plate'), $request->all()), [
                'name'                  => 'required',
                'email'                 => 'email',
                'phone'                 => 'required|regex:/^\+[0-9]{12}$/',
                'password'              => 'required_without:social_id|confirmed|min:6',
                'social_id'             => 'required_without:password|unique:users',
                'social_type'           => 'required_with:social_id',
                'car_plate_digits'      => 'required|numeric|digits_between:1,4',
                'car_plate_characters'  => 'required|string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]+$/u',
                'car_plate'             => 'required|unique:cars,plate_number',
            ],[
                'car_plate_digits.regex' => trans('api.plate_number_format'),
                'car_plate_characters.regex' => trans('api.plate_number_format'),
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            DB::beginTransaction();

            $user = User::firstWhere('phone', $request->input('phone'));

            if ($user) {
                if ($user->social_type) {
                    return $this->apiErrorResponse(trans('api.use_social_login', ['type' => $user->social_type]), 400);
                } else {
                    return $this->apiErrorResponse(trans('api.use_normal_login'), 400);
                }
            }

            $user = User::create($validator->validated());

            $user->cars()->create([
                'plate_number' => $car_plate
            ]);

            if (config('custom.phone_verification')) {
                $verification_code = VerificationCode::makeNew($user);
                $this->sendSMS(trans('api.verification_message', ['code' => $verification_code->code]), $user->phone);
            } else {
                $user->phone_verified_at = now();
                $user->save();
                $token = $this->generateNewToken($user);
            }

            FirebaseToken::updateToken($user);

            DB::commit();

            return $this->apiResponse([
                'user' => new UserResource($user->load('car')),
            ] + (isset($token) ? compact('token') : []));

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password'          => 'required_without:social_id',
                'phone'             => 'required_with:password',
                'social_id'         => 'required_without:password',
                'social_type'       => 'required_with:social_id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            if($request->filled('password')){
                auth()->attempt($request->only('phone', 'password'));
                $user = auth()->user();
            }else{
                $user = User::where($request->only('social_id', 'social_type'))->firstOr(function() use($request){
                    if($request->filled('phone'))
                        $user = User::where('phone', $request->input('phone'))->first();
                    if($user ?? false)
                        $user->update($request->only('social_type', 'social_id'));
                    return $user ?? null;
                });
            }

            if (!$user) {
                return $this->apiErrorResponse(trans('api.Wrong credentials'), 401);
            }

            if ($user->phone_verified_at == null) {
                $verification_code = VerificationCode::makeNew($user);
                $this->sendSMS(trans('api.verification_message', ['code' => $verification_code->code]), $user->phone);

                return $this->apiResponse([
                    'user' => new UserResource($user->load('car'))
                ], trans('api.Phone not verified'), [200, 801]);
            }

            FirebaseToken::updateToken($user);

            $token = $this->generateNewToken($user);

            return $this->apiResponse([
                'token' => $token,
                'user' => new UserResource($user->load('car'))
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function logout()
    {
        try {
            request()->user()->currentAccessToken()->delete();

            return $this->apiResponse();

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function resendVerifictaionCode(Request $request)
    {
        try {

            $user = User::where('phone', $request->input('phone'))->firstOr(function() use ($request){
                return User::whereHas('verificationCodes', fn ($q) => $q->where('verification_codes.phone', $request->phone))->first();
            });

            $validator = Validator::make(array_merge(['user' => $user?->id], $request->all()), [
                'phone'         => 'required|regex:/^\+[0-9]{12}$/',
                'user'          => 'required|exists:users,id',
                'type'          => 'required|in:phone,password',
            ],[
                'user' => trans('api.no phone'),
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            if ($request->type == 'phone' && $user->email_verified_at != null) {
                return $this->apiErrorResponse(trans('api.verified'), 400);
            }

            if ($request->type == 'phone') {
                $verification_code = VerificationCode::makeNew($user, $request->phone);
                $this->sendSMS(trans('api.verification_message', ['code' => $verification_code->code]), $user->phone);
            }else{
                $verification_code = VerificationCode::makeNew($user, null, VerificationCode::TYPE_PASSWORD);
                $this->sendSMS(trans('api.password_reset_message', ['code' => $verification_code->code]), $user->phone);
            }

            return $this->apiResponse();

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function verify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone'          => 'required',
                'code'           => 'required|exists:verification_codes,code,phone,'.$request->input('phone'),
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $verification_code = VerificationCode::firstWhere('code', $request->input('code'));

            if (now() > $verification_code->expire_at) {
                $verification_code->delete();
                return $this->apiErrorResponse(trans('api.Verification expired'), 400);
            }

            $user = $verification_code->user;

            if ($verification_code->type == VerificationCode::TYPE_PHONE) {
                $user->update([
                    'phone_verified_at' => now(),
                    'phone' => $verification_code->phone
                ]);
                $verification_code->delete();

                $token = $this->generateNewToken($user);
            }

            if ($verification_code->type == VerificationCode::TYPE_PASSWORD) {
                $reset_token = Str::random(10);
                $verification_code->update(['token' => $reset_token]);
            }

            return $this->apiResponse([
                'token' => $token ?? null,
                'user' => new UserResource($user->load('car')),
                'reset_token' => $reset_token ?? null,
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function requestPasswordReset(Request $request)
    {
        try {

            return $this->resendVerifictaionCode($request->merge(['type' => 'password']));

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function resetPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'password'      => 'required|confirmed|string|min:6',
                'token'         => 'required|exists:verification_codes,token'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $verification_code = VerificationCode::where('token', $request->token)->first();

            $user = $verification_code->user;

            $user->password = $request->password;
            $user->save();

            $verification_code->delete();

            return $this->apiResponse([
                'user' => new UserResource($user->load('car'))
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function getTermsAndConditions()
    {
        try {
            $terms = Settings::getValue('terms-and-conditions');

            return $this->apiResponse([
                'terms-and-conditions' => $terms,
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    protected function generateNewToken(User $user)
    {
        if (!config('custom.enable_multiple_logins')) {
            $user->tokens()->delete();
        }

        return $user->createToken(config('app.name'))->plainTextToken;
    }

}
