<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ServiceResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'image' => $this->image ? Arr::only($this->image->getAttributes(), ['url', 'thumbnail', 'preview']) : null,
            'discount' => $this->whenPivotLoaded('plan_service', fn() => $this->pivot->discount),
            'limit' => $this->whenPivotLoaded('plan_service', fn() => $this->pivot->limit),
            'used'=> $this->when(isset($this->used), $this->used),
        ];
    }
}
