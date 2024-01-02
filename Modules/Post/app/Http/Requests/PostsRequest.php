<?php

namespace Modules\Post\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'category_id' => 'exists:categories,id',
            'pet_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'pet_type' => 'string',
            'pet_name' => 'string',
            'pet_gender' => 'string',
            'pet_age' => 'integer|digits_between:1,2',
            'pet_breed' => 'string',
            'pet_desc'  => 'string'
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
