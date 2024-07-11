<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Calendar;
use App\Models\BatchUser;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $attendances_count = Attendance::where('user_id', $user->id)
            ->count();

        $attendancePercentage = 0;

        $totalClasses = Calendar::count();

        if ($attendances_count && $totalClasses) {
            $attendancePercentage = round(($attendances_count / $totalClasses) * 100);
        }
        return [
            'Attendance_count' => $attendances_count,
            'Attendance_percentage' => $attendancePercentage,
        ];
    }
    public function getAttendanceReport(Request $request)
{
    $user = $request->user();

    // Get user's batch
    $batchId = BatchUser::where('user_id', $user->id)->value('batch_id');

    if (!$batchId) {
        return response()->json(['message' => 'User not assigned to any batch'], 404);
    }

    // Get all calendar entries for the user's batch, using the global scope
    $calendarEntries = Calendar::select('calendars.*')
        ->where('calendars.batch_id', $batchId)
        ->get();

    if ($calendarEntries->isEmpty()) {
        return response()->json(['message' => 'No calendar entries found for this user'], 404);
    }

    // Get all attendance records for the user
    $attendanceRecords = Attendance::where('user_id', $user->id)->get();

    $report = [];

    foreach ($calendarEntries as $calendarEntry) {
        $calendarStart = Carbon::parse($calendarEntry->start_time);
        $calendarEnd = Carbon::parse($calendarEntry->end_time);

        $attendanceRecord = $attendanceRecords->first(function ($attendance) use ($calendarStart, $calendarEnd) {
            $attendanceDate = Carbon::parse($attendance->date);
            
            return $attendanceDate->isSameDay($calendarStart) || 
                   $attendanceDate->isSameDay($calendarEnd) ||
                   $attendanceDate->between($calendarStart, $calendarEnd);
        });

        $report[] = [
            'date' => $calendarStart->toDateString(),
            'start_time' => $calendarStart->format('H:i'),
            'end_time' => $calendarEnd->format('H:i'),
            'status' => $attendanceRecord ? 'Present' : 'Absent',
        ];
    }

    return response()->json($report);
}
}
