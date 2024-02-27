<?php

namespace Modules\User\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required', 'regex:/^[^<>]*$/',
            'new_password' => ['required', 'regex:/^[^<>]*$/', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), Password::defaults()],
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
