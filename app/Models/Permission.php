<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Permission extends Model
{
    use SoftDeletes;
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['title'];

    public $table = 'permissions';

    protected $fillable = [
        'title',
    ];
}
