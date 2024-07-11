<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'batch_id' => $this->id,
            'batch_name' => $this->name,
            'batch_start' => $this->start_date,
            'batch_end' => $this->end_date,
            'batch_manager_id' => $this->manager_id,
            'batch_manager_name' => $this->user->name,
            'curriculum' => $this->curriculum_data,
            'course_id' => $this->course_package ? $this->course_package->id : '',
            'course_name' => $this->course_package ? $this->course_package->name : '',
            'course_type' => $this->course_package ? $this->course_package->get_formatted_course_type : '',
            'description' => $this->course_package ? $this->course_package->short_description : '',
            //'image_url' => $this->course_package ? url("storage/" . $this->course_package->image_url) : '',
            'image_url' => $this->course_package && $this->course_package->image_url ? url('storage/' . $this->course_packageimage_url) : null,
            'completed' => 50,
            'attendance' => rand(1,100),
        ];
    }
}
