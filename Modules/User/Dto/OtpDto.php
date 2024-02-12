<?php

namespace Modules\User\Dto;

class OtpDto
{
    public function __construct(
        public ?string $otpCode = null,

    ) {
    }

    public static function fromOtpRequest($request): self
    {
        return new self(
            otpCode: $request->validated('otp_code'),

        );
    }
}
