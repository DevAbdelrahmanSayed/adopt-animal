<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'contact_number' => 'required|string|unique:users,contact_number',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
