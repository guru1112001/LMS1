<?php


namespace App\Http\Controllers;

use App\Http\Resources\TeachingMaterialResource;
use App\Models\TeachingMaterial;

class TeachingMaterialController extends Controller
{
    public function index($batch_id, $curriculum_id = null, $section_id = null)
    {

        $user = \request()->user();

        $batch = $user->batches()->where('batches.id', $batch_id)->count();

        if ($batch) {

            $sections = TeachingMaterial::select('teaching_materials.*')
                ->join('sections', 'sections.id', '=', 'teaching_materials.section_id')
                ->join('batch_section', 'sections.id', '=', 'batch_section.section_id')
                ->join('batch_teaching_materials', 'teaching_materials.id', '=', 'batch_teaching_materials.teaching_material_id')
                ->where('batch_section.batch_id', $batch_id)
                ->where('batch_teaching_materials.batch_id', $batch_id)
                ->when($curriculum_id, function ($q) use ($curriculum_id) {
                    $q->where('sections.curriculum_id', $curriculum_id);
                })
                ->when($section_id, function ($q) use ($section_id) {
                    $q->where('teaching_materials.section_id', $section_id);
                })
                ->get();

            if ($sections->isEmpty()) {
                return response()->json(['message' => 'No records found.'], 200);
            }

            return TeachingMaterialResource::collection($sections);
        } else {
            return response()->json(['message' => 'No records found.'], 200);
        }
    }
}
