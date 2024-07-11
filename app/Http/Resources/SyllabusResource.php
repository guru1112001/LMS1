<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SyllabusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'Day'=> $this->day,
            'Syllabus'=>$this->syllabus,
            'SSTA'=>$this->subject,
        ];
    }
}
