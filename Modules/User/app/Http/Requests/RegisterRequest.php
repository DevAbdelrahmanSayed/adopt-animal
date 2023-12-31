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
            'name_' => ['required', 'string', 'min:3', 'max:25'],
            'username' => ['required', 'string', 'min:3', 'max:30','unique:users'],
            'email' => ['required', 'email', 'unique:users,email'],
            'contact_number' => 'required|string|unique:users,contact_number', // assuming 'users' is your table name
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'password' => ['required', 'max:255', Password::defaults()],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $response = ApiResponse::sendResponse(422, 'Validation Errors', $validator->errors());
            throw new HttpResponseException($response);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
