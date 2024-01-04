<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username_email' =>  ['required', 'string','regex:/^[^<>\/\#\$%&\*\(\)_!#]*$/'],
            'password' => ['required', 'regex:/^[^<>]*$/', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), Password::defaults()],

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
