<?php

namespace Modules\Post\Dto;

use App\Helpers\MediaService;
use Modules\Post\app\Http\Requests\PostsRequest;
use Modules\Post\app\Http\Requests\UpdatePostRequest;

class PostDto
{
    public function __construct(
        public ?string $category_id = null,
        public ?array $pet_photo = null,
        public ?string $pet_type = null,
        public ?string $pet_name = null,
        public ?string $pet_breed = null,
        public ?string $pet_gender = null,
        public ?string $pet_age = null,
        public ?string $pet_desc = null,
        public ?int $user_id = null
    ) {
    }

    public static function fromPostRequest(PostsRequest $request, ?int $user_id): self
    {
        $petPhotos = [];

        // Check if files were uploaded
        if ($request->hasFile('pet_photo')) {
            $petPhotos = MediaService::storeMultiplePhotos($request->file('pet_photo'));
        }

        return new self(
            category_id: $request->validated('category_id'),
            pet_photo: $petPhotos,
            pet_type: strtolower($request->validated('pet_type')),
            pet_name: strtolower($request->validated('pet_name')),
            pet_breed: strtolower($request->validated('pet_breed')),
            pet_gender: strtolower($request->validated('pet_gender')),
            pet_age: $request->validated('pet_age'),
            pet_desc: $request->validated('pet_desc'),
            user_id: $user_id,
        );
    }

    public static function fromUpdatePostRequest(UpdatePostRequest $request): self
    {
        $petPhotos = [];

        // Check if files were uploaded
        if ($request->hasFile('pet_photo')) {
            $petPhotos = MediaService::storeMultiplePhotos($request->file('pet_photo'));
        }

        return new self(
            category_id: $request->validated('category_id'),
            pet_photo: $petPhotos,
            pet_type: strtolower($request->validated('pet_type')),
            pet_name: strtolower($request->validated('pet_name')),
            pet_breed: strtolower($request->validated('pet_breed')),
            pet_gender: strtolower($request->validated('pet_gender')),
            pet_age: $request->validated('pet_age'),
            pet_desc: $request->validated('pet_desc'),
        );
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'pet_photo' => $this->pet_photo,
            'pet_type' => $this->pet_type,
            'pet_name' => $this->pet_name,
            'pet_breed' => $this->pet_breed,
            'pet_gender' => $this->pet_gender,
            'pet_age' => $this->pet_age,
            'pet_desc' => $this->pet_desc,
            'user_id' => $this->user_id,
        ];
    }

    public function toUpdateArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'pet_photo' => $this->pet_photo,
            'pet_type' => $this->pet_type,
            'pet_name' => $this->pet_name,
            'pet_breed' => $this->pet_breed,
            'pet_gender' => $this->pet_gender,
            'pet_age' => $this->pet_age,
            'pet_desc' => $this->pet_desc,
        ];
    }
}
