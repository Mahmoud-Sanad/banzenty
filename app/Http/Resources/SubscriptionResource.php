<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'litres' => (float) $this->litres,
            'remaining' => (float) $this->remaining,
            'renew_at' => $this->renew_at,
            'plan' => new PlanResource(
                $this->whenLoaded('plan'),
                $this->relationLoaded('orders') ? $this->orders : null
            ),
        ];
    }
}
