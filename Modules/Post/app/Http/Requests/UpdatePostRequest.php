<?php

namespace Modules\Post\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'exists:categories,id', // Corrected: use pipe instead of comma
            'pet_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'pet_type' => 'string',
            'pet_name' => 'string',
            'pet_gender' => 'string',
            'pet_age' => 'integer|digits_between:1,2',
            'pet_breed' => 'string',
            'pet_desc'  => 'string'
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
