<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    use HasFactory;

    public $table = 'firebase_tokens';

    protected $fillable = ['user_id', 'fcm_token'];

    public static function updateToken(User $user): void
    {
        if (request()->filled('fcm_token')) {
            FirebaseToken::updateOrCreate([
                'fcm_token' => request()->input('fcm_token')
            ], [
                'user_id' => $user->id,
            ]);
        }
    }
}
