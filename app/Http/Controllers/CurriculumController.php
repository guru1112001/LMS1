<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurriculumResource;
use App\Models\Curriculum;

class CurriculumController extends Controller
{
    public function index($id)
    {
        $user = \request()->user();

        $batch = $user->batches()->where('batches.id', $id)->count();

        if ($batch) {
            $curriculums = Curriculum::select('curriculum.*')
                ->join('sections', 'curriculum.id', '=', 'sections.curriculum_id')
                ->join('batch_section', 'sections.id', '=', 'batch_section.section_id')
                ->where('batch_section.batch_id', $id)
                ->groupBy('curriculum.id')
                ->get();

            if ($curriculums->isEmpty()) {
                return response()->json(['message' => 'No records found'], 200);
            }

            return CurriculumResource::collection($curriculums);
        } else {
            return response()->json(['message' => 'No records found'], 200);
        }
    }
}
