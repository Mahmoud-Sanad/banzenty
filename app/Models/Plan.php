<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use SoftDeletes;
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['name'];

    public const PERIOD_SELECT = [
        1 => 'Lifetime',
        2 => 'Weekly',
        3 => 'Monthly',
        4 => 'Yearly',
    ];

    public $table = 'plans';

    public static $searchable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'fuel_id',
        'price',
        'period',
    ];

    public function litres(): Attribute
    {
        return Attribute::make(
            get: fn() => round($this->price / $this->fuel->price, 2)
        );
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'fuel_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'plan_user')->withPivot(['remaining', 'renew_at', 'status']);
    }

    public function getRenewTime(): ?Carbon
    {
        $now = now();

        $renew_at = match ($this->period) {
            1 => null,
            2 => $now->addWeek(),
            3 => $now->addMonth(),
            4 => $now->addYear(),
        };

        return $renew_at;
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('discount', 'limit');
    }


}
