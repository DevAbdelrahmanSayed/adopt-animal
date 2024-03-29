<?php

namespace Modules\User\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username_email' => ['required', 'string', 'regex:/^[^<>\/\#\$%&\*\(\)_!#]*$/'],
            'password' => ['required', 'regex:/^[^<>]*$/', Password::defaults()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $response = ApiResponse::sendResponse(422, 'Validation Errors', $validator->errors());
            throw new HttpResponseException($response);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
