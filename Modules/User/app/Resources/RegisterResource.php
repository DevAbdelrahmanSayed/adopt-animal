<?php

namespace Modules\User\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'user_id' =>$this->id,
            'name'=>$this->name,
            'username'=>$this->username,
            'email'=>$this->email,
            'created_at' =>$this->created_at,
            'token'=>$this->token,

        ];
    }
}
