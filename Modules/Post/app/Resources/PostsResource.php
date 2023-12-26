<?php

namespace Modules\Post\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category' => $this->category_id,
            'photo' => url($this->photo),
            'pet_type' => $this->pet_type,
            'pet_name' => $this->pet_name,
            'pet_color' => $this->pet_color,
            'pet_age' => $this->pet_age,
            'pet_breed' => $this->pet_breed,
            'contact_number' => $this->contact_number,
            'country' => $this->country,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
