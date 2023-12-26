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

            'category_id' => 'required|exists:categories,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed file types and size as needed
            'pet_type' => 'required|string',
            'pet_name' => 'required|string',
            'pet_color' => 'required|string',
            'pet_age' => 'required|string',
            'pet_breed' => 'required|string',
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
