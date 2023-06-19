<?php

namespace App\Traits;

use DateTimeInterface;

trait SerializeDate
{
    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
