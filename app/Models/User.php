<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use InteractsWithMedia;
    use HasFactory;
    use SerializeDate;
    use HasApiTokens;

    public $table = 'users';

    public static $searchable = [
        'name',
        'phone',
        'email',
        'fleet'
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'phone_verified_at',
        'password',
        'social_id',
        'social_type',
        'remember_token',
        'points',
        'external_id',
        'fleet'
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }

        return $file;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->withPivot(['remaining', 'renew_at', 'status']);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function car()
    {
        return $this->hasOne(Car::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class)->withPivot(['read']);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->with('plan.fuel', 'plan.services', 'orders')
            ->where('status', 1)
            ->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionRequest()
    {
        return $this->hasOne(SubscriptionRequest::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function verificationCodes()
    {
        return $this->hasMany(VerificationCode::class);
    }

    public function stations() // for employees
    {
        return $this->belongsToMany(Station::class);
    }

    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->whereNotNull('sent_at')->wherePivot('read', 0)->count();
    }

    public function qrcode(): Attribute
    {
        return Attribute::make(
            get: function () {
                $image = $this->getFirstMediaUrl('qrcode');
                if (!$image) {
                    $path = public_path($this->external_id . '.png');
                    QrCode::format('png')->generate($this->external_id, $path);
                    $image = $this->addMedia($path)->toMediaCollection('qrcode')->getFullUrl();
                    $this->load('media');
                }
                return $image;
            }
        );
    }

    public function externalId(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    $value = rand(10000000, 99999999);
                    $this->update(['external_id' => $value]);
                }
                return $value;
            }
        );
    }
}