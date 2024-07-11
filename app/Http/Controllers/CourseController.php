<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchUser;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\BatchResource;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function getCourses(Request $request)
    {
        $user = $request->user();

        // Retrieve the courses associated with the user's enrolled batches
        // $courses = Course::whereIn('id', function ($query) use ($user) {
        //     $query->select('course_id')
        //           ->from('batch_courses')
        //           ->whereIn('batch_id', function ($query) use ($user) {
        //               $query->select('batch_id')
        //                     ->from('batch_users')
        //                     ->where('user_id', $user->id);
        //           });
        // })->get();

        /*$courses = Batch::where('batch_user.user_id', $user->id)
                    ->get();
    */
        $batches = $user->batches()->with('course_package')->get();
        $courses = $batches->map(function($batch) {
            return $batch->course_package;
        })->filter();
        if ($courses->isEmpty()) {
            return response()->json(['message' => 'You are not enrolled in any course.'], 200);
        }
        return CourseResource::collection($courses);
        /*
        $batchUsers = BatchUser::with('batch.course_package')->where('user_id', $user->id)->get();

        $courses = $batchUsers->pluck('batch.course_package'); // take the courses out  using pluck()

        return CourseResource::collection($courses);*/

        // $batch_data=BatchResource::collection($batches);
        //return CourseResource::collection($courses);

    }
}
