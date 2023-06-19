<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'social_id' => $this->social_id,
            'phone' => $this->phone,
            'points' => $this->points,
            'external_id' => $this->external_id,
            'image' => $this->image
                ? Arr::only($this->image->getAttributes(), ['url', 'thumbnail', 'preview'])
                : array_fill_keys(['url', 'thumbnail', 'preview'], url('images/profile-picture.png')),
            'car' => new CarResource($this->whenLoaded('car')),
            'active_subscription' => new SubscriptionResource($this->whenLoaded('activeSubscription')),
            'image_url' => $this->image ? $this->image->getUrl() : url('images/profile-picture.png'),
        ];
    }
}
