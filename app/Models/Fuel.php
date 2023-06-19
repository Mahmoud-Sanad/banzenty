<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fuel extends Model
{
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['name'];

    public $table = 'fuels';

    protected $fillable = [
        'name',
        'price',
    ];
}
