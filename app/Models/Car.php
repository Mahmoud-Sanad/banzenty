<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;
    use HasFactory;
    use SerializeDate;


    public $table = 'cars';

    public static $searchable = [
        'plate_number',
    ];

    protected $fillable = [
        'plate_number',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function digits(): Attribute
    {
        return Attribute::make(
            get: fn() => str_replace($this->characters, '', $this->plate_number)
        );
    }

    public function characters(): Attribute
    {
        return Attribute::make(
            get: fn() => preg_replace('/[0-9]/', '', $this->plate_number)
        );
    }

    public static function NumbersToEnglish($number)
    {
        $arabic_numbers = array_merge(
            array_flip(array_map(fn($n) => mb_chr(1632 + $n), range(0, 9))),
            array_flip(array_map(fn($n) => mb_chr(1776 + $n), range(0, 9))),
        );

        return collect(mb_str_split($number))->map(
            fn($digit) => $arabic_numbers[$digit] ?? $digit
        )->implode('');
    }
}
