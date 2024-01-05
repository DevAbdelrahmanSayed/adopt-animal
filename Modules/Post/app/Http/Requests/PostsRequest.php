<?php

namespace Modules\Post\app\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'category_id' => 'required,exists:categories,id',
            'pet_photo' => 'required,image|mimes:jpeg,png,jpg,gif|max:2048',
            'pet_type' => 'required,string',
            'pet_name' => 'required,string',
            'pet_gender' => 'required,string',
            'pet_age' => 'required,integer|digits_between:1,2',
            'pet_breed' => 'required,string',
            'pet_desc'  => 'required,string'
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
