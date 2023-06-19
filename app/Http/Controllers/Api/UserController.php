<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CarResource;
use App\Http\Resources\PaginatedResourceCollection;
use App\Http\Resources\UserResource;
use App\Models\Car;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    protected User $user;

    public function __construct()
    {
        $this->user = request()->user('sanctum') ?? new User();
        $this->user->load('car');
    }

    public function profileDetails()
    {
        try {

            return $this->apiResponse([
                'user' => new UserResource($this->user),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function update(Request $request)
    {
        try {
            $car_plate_digits = Car::NumbersToEnglish($request->input('car_plate_digits'));
            $request->merge(['car_plate_digits' => (int) $car_plate_digits]);
            $car_plate = $request->input('car_plate_digits') . $request->input('car_plate_characters');

            $validator = Validator::make(array_merge(compact('car_plate'), $request->all()), [
                'name'  => 'string',
                'phone' => "unique:users,phone,{$this->user->id}|regex:/^\+[0-9]{12}$/",
                'email' => 'nullable|email',
                'image' => 'image|max:4098',
                'car_plate_digits'      => 'numeric|digits_between:1,4',
                'car_plate_characters'  => 'string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]+$/u',
                'car_plate'             => "nullable|unique:cars,plate_number,{$this->user->car->id},id",
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            if (config('custom.phone_verification') && $request->has('phone') && $request->phone != $this->user->phone) {
                $verification_code = VerificationCode::makeNew($this->user, $request->phone);
                $this->sendSMS(trans('api.verification_message', ['code' => $verification_code->code]), $request->phone);

                $this->user->fillable(array_diff($this->user->getFillable(), ['phone'])); // to prevent updating current phone

                $needs_verification = true;
            } else {
                $needs_verification = false;
            }

            $this->user->update($validator->validated());

            if ($car_plate) {
                $this->user->car->update(['plate_number' => $car_plate]);
            }

            if ($request->has('image')) {
                $this->user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return $this->apiResponse(
                ['user' => new UserResource($this->user)],
                $needs_verification ? trans('api.Phone not verified') : null,
                $needs_verification ? [200, 801] : 200
            );

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password_old'      => 'required|current_password',
                'password'          => 'required|confirmed|different:password_old',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $this->user->update($request->only('password'));

            return $this->apiResponse();

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function notifications()
    {
        try {

            $notifications = $this->user->notifications()
                ->whereNotNull('sent_at')
                ->orderBy('sent_at', 'desc')
                ->paginate(20);

            DB::table('notification_user')
                ->where('user_id', $this->user->id)
                ->whereIn('notification_id', $notifications->pluck('id'))
                ->update(['read' => 1]);

            return $this->apiResponse(new PaginatedResourceCollection($notifications));

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function unreadNotificationsCount()
    {
        try {
            $count = $this->user->getUnreadNotificationsCount();

            return $this->apiResponse([
                'unread_notifications_count' => $count
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function AddCar(Request $request)
    {
        try {
            $car_plate_digits = Car::NumbersToEnglish($request->input('car_plate_digits'));
            $request->merge(['car_plate_digits' => (int) $car_plate_digits]);
            $plate_number = $request->input('plate_number_digits') . $request->input('plate_number_characters');

            $validator = Validator::make(array_merge(compact('plate_number'), $request->all()), [
                'plate_number_digits'       => 'required|numeric|digits_between:1,4',
                'plate_number_characters'   => 'required|string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]+$/u',
                'plate_number'              => 'required|unique:cars,plate_number'
            ],[
                'plate_number_characters.regex' => trans('api.plate_number_format'),
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $this->user->cars()->create($validator->validated());

            return $this->apiResponse();

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function listCars()
    {
        try {
            $cars = $this->user->cars;

            return $this->apiResponse([
                'cars' => CarResource::collection($cars),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function deleteCar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'car_id'           => 'required|exists:cars,id,user_id,' . auth()->id(),
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $car = Car::find($request->input('car_id'));

            $car->forceDelete();

            return $this->apiResponse();

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function requests()
    {
        try {
            $requests = $this->user->orders()->with('station', 'service', 'subscription.plan')->latest()->paginate(10);

            return $this->apiResponse(new PaginatedResourceCollection($requests));

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }
}
