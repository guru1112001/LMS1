<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use App\Http\Resources\LeaveResource;

class LeaveController extends Controller
{
    public function applyLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $user = auth()->user();

        $leave = new Leave([
            'user_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        $leave->save();

        return response()->json(['message' => 'Leave applied successfully'], 201);
    }

    public function index(Request $request)
    {
        $user=$request->user();
        $perPage = $request->input('per_page', 10);
        $leaves= Leave::where('user_id',$user->id)->paginate($perPage);
        // $count= Leave::where('user_id',$user->id)->count();
        $leavesCollection=LeaveResource::collection($leaves);
        return response()->json([
            'data' => $leavesCollection,
            'count' => $leaves->total(),
        ]);
    }
}
