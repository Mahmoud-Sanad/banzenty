<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
