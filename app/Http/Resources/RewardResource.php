<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class RewardResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description,
            'points'            => $this->points,
            'image'             => $this->image ? Arr::only($this->image->getAttributes(), ['url', 'thumbnail', 'preview']) : null,
            'service_id'        => $this->service_id,
            'service'           => new ServiceResource($this->whenLoaded('service')),
        ];
    }
}
