<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'fleet' => [
                'string',
                'required',
            ],
            'phone' => [
                'string',
                'required',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
            'plans.*' => [
                'integer',
            ],
            'plans' => [
                'array',
            ],
            'stations.*' => [
                'integer',
            ],
            'stations' => [
                'array',
            ],
        ];
    }
}