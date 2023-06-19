<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'service_id' => [
                'required',
                'integer',
            ],
            'station_id' => [
                'required',
                'integer',
            ],
            'fuel_id' => [
                'integer',
                'required_with:litres',
                'exists:fuels,id',
            ],
            'litres' => [
                'numeric',
            ],
        ];
    }

    public function messages()
    {
        return[
            'user_id.required' => trans('cruds.order.fields.user_not_found')
        ];
    }
}
