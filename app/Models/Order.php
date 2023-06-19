<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use SerializeDate;

    public $table = 'orders';

    protected $fillable = [
        'user_id',
        'service_id',
        'station_id',
        'subscription_id',
        'litres',
        'fuel_id',
        'price',
        'external_number',
        'from_subscription',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'fuel_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function attachSubscription(?Subscription $subscription)
    {
        // removing old subscription
        if ($this->subscription_id) {
            $this->subscription->increment('remaining', $this->from_subscription);
            $this->subscription_id = null;
            $this->from_subscription = null;
        }

        // attaching new subscription (fuel)
        if ($subscription?->remaining && $this->litres) {
            $this->subscription_id = $subscription->id;
            $this->from_subscription = min($this->price, $subscription->remaining);
            $this->price -= $this->from_subscription;
            $subscription->decrement('remaining', $this->from_subscription);
        }

        // attaching new subscription (discount)
        if(!$this->litres && $service = $subscription?->plan?->services?->find($this->service_id)) {
            if(!$service->pivot->limit || $service->pivot->limit > $service->setUsed($subscription->orders)->used) {
                $this->subscription_id = $subscription->id;
                $this->from_subscription = null;
            }
        }

        return $this;
    }

    protected static function booted()
    {
        static::creating(function($order) {
            do {
                $order->external_number = rand(0, 9999999);
            } while (self::where('external_number', $order->external_number)->exists());
        });
    }
}
