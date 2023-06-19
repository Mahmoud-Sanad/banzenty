<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Settings;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $subscription_id) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = Subscription::findOrFail($this->subscription_id);

        if($subscription->status > 1) return;

        $subscription->update(['status' => 2]);

        if (Settings::getValue('subscription_auto_renewal')){
            $subscription->renew();
        } else {
            $notification = Notification::create([
                'title' => [
                    'ar' => 'نذكرك بموعد تجديد الاشتراك',
                    'en' => 'Time to renew your subscription'
                ],
                'body' => [
                    'ar' => '',
                    'en' => '',
                ],
                'type' => 2,
            ]);
            $notification->users()->attach($subscription->user_id);
            $notification->send();
        }
    }
}
