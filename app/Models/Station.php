<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Station extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['name'];

    public $table = 'stations';

    public static $searchable = [
        'name',
        'address',
    ];

    protected $fillable = [
        'name',
        'company_id',
        'lat',
        'lng',
        'address',
        'working_hours',
        'has_contract',
    ];

    public function scopeDistance($query, $lat, $lng)
    {
        return $query->selectRaw('
            *, ST_Distance_Sphere(point(`lng`, `lat`), point(?, ?))/1000 AS distance
        ', [$lng, $lat]);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function fuels()
    {
        return $this->belongsToMany(Fuel::class);
    }

    public function users() // employees
    {
        return $this->belongsToMany(User::class);
    }
}
