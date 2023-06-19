<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SubscriptionRenewal;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function attach(User $user, Plan $plan)
    {
        if($user->activeSubscription?->plan_id == $plan->id) {
            return response(status: 204);
        }

        if($user->activeSubscription) {
            $this->cancel($user);
        }

        $subscription = $user->subscriptions()->create([
            'plan_id'   => $plan->id,
            'remaining' => $plan->price,
            'renew_at' => $plan->getRenewTime(),
        ]);

        if ($subscription->renew_at) {
            SubscriptionRenewal::dispatch($subscription->id)->delay($subscription->renew_at);
        }

        return response()->json();
    }

    public function renew(User $user)
    {
        $subscription = $user->subscriptions()->latest()->first();

        if (!$subscription) {
            return response(status: 422);
        }

        $this->cancel($user);

        return $this->attach($user, $subscription->plan);
    }

    public function cancel(User $user)
    {
        if (!$user->activeSubscription) {
            return response(status: 422);
        }

        $user->activeSubscription->update(['renew_at' => now(), 'status' => 2]);

        info('cancelled');

        $user->unsetRelation('activeSubscription');

        return response()->json();
    }
}
