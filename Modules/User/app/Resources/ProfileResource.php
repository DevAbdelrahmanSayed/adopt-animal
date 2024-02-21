<?php

namespace Modules\User\app\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'user_id' => $this->id,
            'name' => $this->name_,
            'profile' => $this->profile,
            'username' => $this->username,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'country' => $this->country,
            'address' => $this->address,
            'created_at' => Carbon::parse($this->created_at)->toTurkey(),

        ];
    }
}
