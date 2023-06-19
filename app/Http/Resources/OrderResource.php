<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'station_id' => $this->station_id,
            'service_id' => $this->service_id,
            'price' => $this->price,
            'from_subscription' => $this->from_subscription,
            'total' => $this->price + $this->from_subscription,
            'amount' => $this->litres,
            'external_number' => $this->external_number,
            'created_at' => $this->created_at,
            'subscription_name' => $this->when(
                $this->subscription_id && $this->relationLoaded('subscription') && $this->subscription->relationLoaded('plan'),
                fn() => $this->subscription->plan?->name,
                null
            ),
            'service' => new ServiceResource($this->whenLoaded('service')),
            'station' => new StationResource($this->whenLoaded('station')),
        ];
    }
}
