<?php

namespace App\Http\Requests;

use App\Models\Car;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('car_create');
    }

    public function rules()
    {
        return [
            'plate_number' => [
                'string',
                'required',
                'unique:cars',
            ],
            'user_id' => [
                'required',
                'integer',
            ]
        ];
    }
}
