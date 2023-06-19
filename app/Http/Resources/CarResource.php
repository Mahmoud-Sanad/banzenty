<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plate_number_characters = preg_replace('/[0-9]/', '', $this->plate_number);
        $plate_number_digits = str_replace($plate_number_characters, '', $this->plate_number);

        return [
            'id'                            => $this->id,
            'plate_number_digits'           => $plate_number_digits,
            'plate_number_characters'       => $plate_number_characters,
        ];
    }
}
