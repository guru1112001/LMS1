<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type'=>'Holiday',
            'title' => $this->name,
            'start' => $this->date,
            // 'end' => $this->date,
            // 'url' => "#",
        ];
    }
}
