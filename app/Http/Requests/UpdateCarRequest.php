<?php

namespace App\Http\Requests;

use App\Models\Car;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('car_edit');
    }

    public function rules()
    {
        return [
            'plate_number' => [
                'string',
                'required',
                'unique:cars,plate_number,' . request()->route('car')->id,
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
