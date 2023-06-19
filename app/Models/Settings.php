<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Settings extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use SerializeDate;

    protected $table = 'settings';

    public const TYPE_FILE = 2;
    public const TYPE_JSON = 3;

    protected $fillable = ['name', 'value', 'type'];

    public static $upload_path = 'uploads/settings/';

    public static function getValue($name)
    {
        return optional(self::firstWhere('name', $name))->value;
    }

    public static function setValue($name, $value, $type = 1)
    {
        return self::updateOrCreate(['name' => $name],['type' => $type, 'value' => $value]);
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $values = [
                    self::TYPE_FILE => fn () => $this->getFirstMediaUrl(),
                    self::TYPE_JSON => fn ($value) => json_decode($value ?? '[]', true),
                ];
                $function = $values[$attributes['type']] ?? null;
                return $function ? $function($value) : $value;
            },
            set: function ($value, $attributes) {
                $values = [
                    self::TYPE_FILE => fn ($value) => $this->addMedia($value)->toMediaCollection()->getUrl(),
                    self::TYPE_JSON => fn ($value) => json_encode($value ?? []),
                ];
                $function = $values[$attributes['type']] ?? null;
                return $function ? $function($value) : $value;
            },
        );
    }

}
