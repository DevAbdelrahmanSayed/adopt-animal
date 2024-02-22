<?php

namespace Modules\User\Dto;

use Modules\User\app\Http\Requests\LoginRequest;
use Modules\User\app\Http\Requests\ProfileRequest;
use Modules\User\app\Http\Requests\RegisterRequest;
use Modules\User\app\Http\Requests\UpdatePasswordRequest;

class UserDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $username = null,
        public ?string $email = null,
        public ?object $profile = null,
        public ?string $contactNumber = null,
        public ?string $country = null,
        public ?string $address = null,
        public ?string $password = null,
        public ?string $newPassword = null,
        public ?string $usernameEmail = null,
    ) {
    }

    public static function fromProfileRequest(ProfileRequest $request): self
    {
        return new self(
            name: strtolower($request->validated('name_')),
            username: strtolower($request->validated('username')),
            email: $request->validated('email'),
            profile: $request->file('profile'),
            contactNumber: $request->validated('contact_number'),
            country: $request->validated('country'),
            address: $request->validated('address'),
        );
    }

    public static function fromUpdatePasswordRequest(UpdatePasswordRequest $request): self
    {
        return new self(
            password: $request->validated('password'),
            newPassword: $request->validated('passwordNew'),
        );
    }

    public static function resetLinkRequest($request): self
    {
        return new self(
            usernameEmail: $request->validated('username_email'),
        );
    }

    public static function fromRegisterRequest(RegisterRequest $request): self
    {
        return new self(
            name: strtolower($request->validated('name_')),
            username: strtolower($request->validated('username')),
            email: $request->validated('email'),
            contactNumber: $request->validated('contact_number'),
            country: $request->validated('country'),
            address: $request->validated('address'),
            password: $request->validated('password'),
        );
    }

    public static function fromLoginRequest(LoginRequest $request): self
    {
        return new self(
            password: $request->validated('password'),
            usernameEmail: $request->validated('username_email'),

        );
    }

    public static function resetPasswordRequest($request): self
    {
        return new self(
            password: $request->validated('password'),
        );
    }

    public function toArrayProfile(): array
    {
        return [
            'name_' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'profile' => $this->profile,
            'contact_number' => $this->contactNumber,
            'country' => $this->country,
            'address' => $this->address,
        ];
    }

    public function toArrayRegister(): array
    {
        return [
            'name_' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'contact_number' => $this->contactNumber,
            'country' => $this->country,
            'address' => $this->address,
        ];
    }
}
