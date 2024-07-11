<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachingMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'material_name' => $this->name,
            'section_id' => $this->section_id,
            'curriculum_id' => $this->section ? $this->section->curriculum_id : '',
            'description' => $this->description,
            'material_source' => $this->material_source,
            'content' => $this->content,
            'file' => $this->file ? url('storage/' . $this->file) : null,
            'doc_type' => $this->doc_type,
            'prerequisite' => $this->prerequisite,
            'general_instructions' => $this->general_instructions,
            'result_declaration' => $this->result_declaration,
            'sort' => $this->sort,
        ];
    }
}
