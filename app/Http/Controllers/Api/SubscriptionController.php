<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Jobs\SubscriptionRenewal;
use App\Models\Plan;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends BaseController
{
    public function plans()
    {
        try {
            $plans = Plan::with('fuel', 'services')->get()->sortByDesc('fuel.name');

            /** @var User $user */
            $user = request()->user('sanctum');

            $subscribed = (bool) $user->activeSubscription;

            $pending_request = $user->subscriptionRequest;

            $subscribe_message = Settings::getValue('subscribe_message');

            return $this->apiResponse([
                'plans' => PlanResource::collection($plans),
                'subscribed' => $subscribed,
                'pending_subscription_id' => $pending_request?->plan_id,
                'requested_subscription_message' => $subscribe_message['subscribe'] ?? null,
                'pending_subscription_message' => $subscribe_message['request_pending'] ?? null,
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function subscribe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id'           => 'required|exists:plans,id'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            /** @var User $user */
            $user = auth()->user();

            $subscribed = $user->activeSubscription()->lockForUpdate()->first();

            if ($subscribed) {
                return $this->apiErrorResponse(trans('api.user_subscribed'));
            }

            $user->subscriptionRequest?->delete();

            $user->subscriptionRequest()->create([
                'plan_id' => $request->plan_id,
                'type' => 1,
            ]);

            return $this->apiResponse();

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function mySubscription()
    {
        try {

            /** @var User $user */
            $user = auth()->user();

            $subscription = $user->activeSubscription ? new SubscriptionResource($user->activeSubscription) : null;

            return $this->apiResponse([
                'subscription' => $subscription,
                'subscribe_message' => Settings::getValue('subscribe_message'),
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function renew()
    {
        try {

            DB::beginTransaction();

            /** @var User $user */
            $user = auth()->user();

            if (!$user->activeSubscription) {
                return $this->apiErrorResponse(trans('api.user_not_subscribed'));
            }

            $subscription = $user->activeSubscription->renew();

            DB::commit();

            return $this->apiResponse([
                'subscription' => new SubscriptionResource($subscription),
            ]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function cancel()
    {
        try {

            /** @var User $user */
            $user = auth()->user();

            if (!$user->activeSubscription) {
                return $this->apiErrorResponse(trans('api.user_not_subscribed'));
            }

            $user->activeSubscription->update(['renew_at' => now(), 'status' => 2]);

            return $this->apiResponse();

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }
}
