<?php

namespace Modules\User\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_' => ['string', 'min:3', 'max:25'],
            'username' => ['string', 'min:3', 'max:30', 'unique:users'],
            'email' => ['email', 'unique:users,email'],
            'profile' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'contact_number' => 'string|unique:users,contact_number',
            'country' => 'string|max:255',
            'address' => 'string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $response = ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
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
