<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'lat' => explode(',', $this->location)[0] ?? null,
            'lng' => explode(',', $this->location)[1] ?? null,
        ]);
    }

    public function rules()
    {
        return [
            'name' => [
                'array',
                'nullable',
            ],
            'company_id' => [
                'required',
                'integer',
            ],
            'lat' => [
                'numeric',
                'required',
                'min:-90',
                'max:90',
            ],
            'lng' => [
                'numeric',
                'required',
                'min:-180',
                'max:180',
            ],
            'address' => [
                'string',
                'max:250',
                'nullable',
            ],
            'working_hours' => [
                'string',
                'max:250',
                'nullable',
            ],
            'services.*' => [
                'integer',
            ],
            'services' => [
                'array',
            ],
            'fuels.*' => [
                'integer',
            ],
            'fuels' => [
                'required',
                'array',
            ],
            'users.*' => [
                'integer',
            ],
            'users' => [
                'array',
            ],
        ];
    }
}
