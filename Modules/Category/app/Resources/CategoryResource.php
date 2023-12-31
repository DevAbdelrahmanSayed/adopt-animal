<?php

namespace Modules\Category\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->name,
            'label' => ucfirst($this->name),

        ];
    }
}
