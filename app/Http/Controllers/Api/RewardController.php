<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CouponResource;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RewardController extends BaseController
{
    public function index()
    {
        try {
            $rewards = Reward::where('points', '<=', auth()->user()->points)->get();

            return $this->apiResponse([
                'rewards' => RewardResource::collection($rewards),
                'points' => auth()->user()->points,
                'qr_code' => auth()->user()->qrcode,
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function redeem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reward_id'           => 'required|exists:rewards,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $reward = Reward::find($request->reward_id);

            if ($reward->points > auth()->user()->points) {
                return $this->apiErrorResponse(trans('api.insufficient_points'), 400);
            }

            $coupon = $reward->coupons()->create([
                'user_id' => auth()->id(),
                'code' => Str::random(5),
            ]);

            return $this->apiResponse([
                'coupon' => new CouponResource($coupon),
                'coupon_help_text' => Settings::getValue('coupon_help_text')
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }
}
