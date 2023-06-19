<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'code', 'expire_at', 'type', 'token', 'phone'
    ];

    protected $dates = [
        'expire_at',
    ];

    public const TYPE_PHONE = 1;
    public const TYPE_PASSWORD = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function makeNew(User $user, $phone = null, $type = self::TYPE_PHONE): self
    {
        do {
            try {
                $verification_code = VerificationCode::updateOrCreate([
                    'user_id' => $user->id,
                    'type' => $type,
                ],[
                    'code' => mt_rand(1000, 9999),
                    'expire_at' => now()->addDays(1)->toDateTimeString(),
                    'phone' => $phone ?? $user->phone,
                ]);
            } catch (QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $verification_code = false;
                } else {
                    throw $e;
                }
            }
        } while (!$verification_code);

        return $verification_code;
    }
}
