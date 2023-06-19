<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BannerResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends BaseController
{
    public function home(Request $request)
    {
        try {
            $user = $request->user('sanctum');

            if($user) $user->load('activeSubscription.plan.fuel', 'car');

            $latest_request = $user ? $user->orders()->with('station', 'service', 'subscription.plan')->latest()->first() : null;

            $banners = Banner::with('target')->where('active', 1)->orderBy('order')->get();

            $unread_notifications_count = $user ? $user->getUnreadNotificationsCount() : null;

            return $this->apiResponse([
                'user' => $user ? new UserResource($user) : null,
                'banners' => BannerResource::collection($banners),
                'latest_request' => $latest_request ? new OrderResource($latest_request) : null,
                'unread_notifications_count' => $unread_notifications_count,
                'subscribe_message' => Settings::getValue('subscribe_message'),
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function contactUs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'          => 'required',
                'email'         => 'required|email',
                'text'          => 'required'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            ContactUs::create($validator->validated());

            return $this->apiResponse();

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }
}
