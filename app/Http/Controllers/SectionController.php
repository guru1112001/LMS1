<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\TeachingMaterial;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SectionResource;
use App\Http\Resources\TeachingMaterialResource;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function index($batch_id, $curriculum_id = null)
    {

        $user = \request()->user();

        $batch = $user->batches()->where('batches.id', $batch_id)->count();

        if ($batch) {
            $sections = Section::select('sections.*')
                ->join('batch_section', 'sections.id', '=', 'batch_section.section_id')
                ->where('batch_section.batch_id', $batch_id)
                ->when($curriculum_id, function ($q) use ($curriculum_id) {
                    $q->where('curriculum_id', $curriculum_id);
                })
                ->get();

            //$items2 = Str::replaceArray('?', $sections->getBindings(), $sections->toSql());
            //dd($items2);

            if ($sections->isEmpty()) {
                return response()->json(['message' => 'No sections available for your enrolled batches.'], 200);
            }

            return SectionResource::collection($sections);
        } else {
            return response()->json(['message' => 'No sections available for your enrolled batches.'], 200);
        }
    }
}
