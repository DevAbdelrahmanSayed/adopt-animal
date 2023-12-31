<?php

namespace Modules\User\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' =>$this->id,
            'username'=>$this->username,
            'name'=>$this->name_,
            'email'=>$this->email,
            'token'=>$this->token,

        ];

    }
}
