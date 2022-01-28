<?php

namespace App\Http\Requests\API\V1\Auth\LogIn;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LogInRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile_number' => [
                'required',
                'numeric',
                'regex:/[+][8][8](0)?[0-9]{11}$/',
            ],
            'password' => [
                'required',
                'between:8,32',
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'messages' => $validator->errors()->all(),
            'data' => [],
        ], 200));
    }
}
