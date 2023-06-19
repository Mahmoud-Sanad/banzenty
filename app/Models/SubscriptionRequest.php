<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'plan_id', 'type', 'status'];

    public const TYPES = [
        1 => 'Subscribe',
        2 => 'Renew',
        3 => 'Cancel',
    ];

    public const STATUSES = [
        0 => 'pending',
        1 => 'accepted',
        2 => 'rejected',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
