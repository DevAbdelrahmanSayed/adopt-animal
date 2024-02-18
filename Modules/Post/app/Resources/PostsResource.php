<?php

namespace Modules\Post\app\Resources;

use App\Enums\HostingEnum;
use Carbon\Carbon;
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
            'category' => $this->category_id,
            'owner_name' => $this->user->name_,
            'owner_id' => $this->user->id,
            'pet_photos' => collect($this->pet_photo)->map(function ($photo) {
                     return HostingEnum::LINK->value . $photo;
                 })->toArray(),
            'pet_favorite' => $this->isFavoritedByUser(),
            'pet_type' => $this->pet_type,
            'pet_name' => $this->pet_name,
            'pet_gender' => $this->pet_gender,
            'pet_age' => $this->pet_age,
            'pet_breed' => $this->pet_breed,
            'pet_desc' => $this->pet_desc,
            'contact_number' => $this->user->contact_number,
            'country' => $this->user->country,
            'address' => $this->user->address,
            'created_at' => Carbon::parse($this->created_at)->toTurkey(),
        ];
    }
}
