<?php

namespace App\Http\Controllers;

use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SyllabusResource;

class SyllabusController extends Controller
{
    public function getUserSyllabus()
    {
        $user=Auth::user();

        $CourseIds=$user->batches()
        ->with('course_package')
        ->get()
        ->pluck('course_package_id');
        $syllabus=Syllabus::WhereIn('Course_id',$CourseIds)
                            ->where('Status','Completed')
                            ->get();

        if($syllabus->isEmpty())
        {
            return response()->json(['error'=>'your syllabus is not completed'],200);
        }
        
        return SyllabusResource::collection($syllabus);
    }
}
