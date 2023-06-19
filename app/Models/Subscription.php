<?php

namespace App\Models;

use App\Jobs\SubscriptionRenewal;
use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    use SerializeDate;

    protected $table = 'plan_user';

    protected $fillable = [
        'plan_id',
        'user_id',
        'remaining',
        'renew_at',
        'status',
    ];

    public const STATUS_SELECT = [
        1 => 'active',
        2 => 'ended',
    ];

    protected $casts = [
        'renew_at' => 'datetime',
        'remaining' => 'decimal:2',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function litres(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->relationLoaded('plan') && $this->plan->relationLoaded('fuel'))
                ? round($this->remaining / $this->plan?->fuel?->price, 2)
                : null
        );
    }

    public function renew()
    {

        // Payment

        $this->update(['renew_at' => now(), 'status' => 2]);

        if($this->plan && $this->user) {
            $subscription = self::create([
                'user_id' => $this->user_id,
                'plan_id' => $this->plan_id,
                'remaining' => $this->plan->price,
                'renew_at' => $this->plan->getRenewTime(),
            ]);

            if ($subscription->renew_at) {
                SubscriptionRenewal::dispatch($subscription->id)->delay($subscription->renew_at);
            }

            return $subscription;
        }
    }
}
