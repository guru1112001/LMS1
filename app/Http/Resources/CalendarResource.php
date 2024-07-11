<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\HolidayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'type'=>'schedule',
            'tutor'=>$this->tutor->name,
            'avatar_url'=>$this->tutor->avatar_url,
            'location'=>$this->classroom->name,
            'title' => $this->curriculum ? $this->curriculum->name : '',
            'start' => $this->start_time->format('Y-m-d H:i:s'),
            'end' => $this->end_time->format('Y-m-d H:i:s'),
        ];
    }
    }

