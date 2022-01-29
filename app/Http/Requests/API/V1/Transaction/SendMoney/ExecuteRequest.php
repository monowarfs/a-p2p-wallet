<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Transaction\SendMoney;

use App\Rules\API\Verify\VerifyPIN;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExecuteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_mobile_number' => [
                'required',
                'regex:/[+][8][8](0)?[0-9]{11}$/',
                'exists:users,mobile_no',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:' . config('biz_settings.transaction.send_money.min'),
                'max:' . config('biz_settings.transaction.send_money.max'),
            ],
            'pin' => [
                'required',
                'digits:4',
                (new VerifyPIN(auth()->user())),
            ],

        ];
    }

    public function messages()
    {
        return [
            'receiver_mobile_number.exists' => 'The selected receiver does not have an account.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'messages' => $validator->errors()->all(),
            'data' => null,
        ], 200));
    }
}
