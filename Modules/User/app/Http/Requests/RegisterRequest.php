<?php

namespace Modules\User\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_' => ['required', 'string', 'min:3', 'max:1313', 'regex:/^[A-Za-z\s]+$/', 'regex:/^[^<>\/\#\$%&\*\(\)_!@#]*$/'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'unique:users', 'regex:/^[A-Za-z0-9]+$/', 'regex:/^[^<>\/\#\$%&\*\(\)_!@#]*$/'],
            'email' => ['required', 'email:rfc,dns,strict', 'unique:users,email', 'regex:/^[^<>\/\#\$%&\*\(\)_!#]*$/'],
            'contact_number' => ['required', 'unique:users,contact_number', 'regex:/^[^<>\/\#\$%&\*\(\)_!@#]*$/'],
            'country' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/', 'regex:/^[^<>\/\#\$%&\*\(\)_!@#]*$/'],
            'address' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/', 'regex:/^[^<>\/\#\$%&\*\(\)_!@#]*$/'],
            'password' => ['required', 'regex:/^[^<>]*$/', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), Password::defaults()],
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
