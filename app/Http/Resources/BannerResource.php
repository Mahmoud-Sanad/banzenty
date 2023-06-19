<?php

namespace App\Http\Resources;

use App\Models\Banner;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BannerResource extends JsonResource
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
            'image' => $this->image ? Arr::only($this->image->getAttributes(), ['url', 'thumbnail', 'preview']) : null,
            'target_type' => Banner::TARGET_TYPES[$this->target_type] ?? $this->target_type,
            'target_id' => $this->target_id,
            'target' => $this->whenLoaded('target'),
        ];
    }
}
