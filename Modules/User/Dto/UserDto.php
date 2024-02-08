<?php

namespace Modules\User\Dto;

 use Modules\User\app\Http\Requests\ProfileRequest;

 class UserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly string $profile,
        public readonly string $contact_number,
        public readonly string $country,
        public readonly string $address,
        public readonly string $password,
    ){}

     public static function fromProfileRequest(ProfileRequest $request)
     {
         return new self(
             name: $request->validated('name'),
             username: $request->validated('username'),
             email: $request->validated('email'),
             profile: $request->validated('profile'),
             contact_number: $request->validated('contact_number'),
             country: $request->validated('country'),
             address: $request->validated('address'),
             password:null
         );

     }



}
