<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BatchResource;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $batches = $user->batches()
            ->with('course_package')
            ->get();

        return BatchResource::collection($batches);
    }

    public function view($id, Request $request)
    {
        $user = $request->user();
        $batches = $user->batches()
            ->with('course_package')
            ->find($id);

        return new BatchResource($batches);
    }
}
