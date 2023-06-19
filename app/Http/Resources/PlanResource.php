<?php

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    protected $subscription_orders;

    public function __construct($resource, $orders = null)
    {
        $this->subscription_orders = $orders instanceof Collection ? $orders : null;
        parent::__construct($resource);
    }

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
            'litres' => $this->litres,
            'price' => $this->price,
            'period' => Plan::PERIOD_SELECT[$this->period],
            'fuel' => new FuelResource($this->whenLoaded('fuel')),
            'services' => ServiceResource::collection(
                $this->whenLoaded('services', $this->services->each->setUsed($this->subscription_orders))
            ),
        ];
    }
}
