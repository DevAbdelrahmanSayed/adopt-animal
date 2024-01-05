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


    public function authorize(): bool
    {
        return true;
    }
}
