<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['name'];

    public $table = 'categories';

    public static $searchable = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];

    public function categoryServices()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }
}
