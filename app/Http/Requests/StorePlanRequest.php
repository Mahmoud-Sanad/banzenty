<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePlanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('plan_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'array',
                'required',
            ],
            'fuel_id' => [
                'required',
                'integer',
            ],
            'price' => [
                'required',
            ],
            'period' => [
                'required',
            ],
        ];
    }
}
