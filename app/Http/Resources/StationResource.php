<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'distance' => $this->distance,
            'address' => $this->address,
            'working_hours' => ($this->working_hours ?? '24') . (app()->getLocale() == 'en' ? ' hours' : ' ساعة') ,
            'has_contract' => $this->has_contract,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'fuels' => FuelResource::collection($this->whenLoaded('fuels')),
            'images' => $this->getMedia()->map->getFullUrl(),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
