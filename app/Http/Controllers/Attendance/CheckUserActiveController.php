<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckUserActiveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Check for an active attendance record
        $activeAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->orderBy('check_in', 'desc')
            ->first();

        if ($activeAttendance) {
            $meeting = Meeting::find($activeAttendance->meeting_id);

            if ($meeting && $now->between($meeting->start_time, $meeting->end_time)) {
                return response()->json([
                    'in_meeting' => true,
                    'message' => 'User is currently in an ongoing meeting and has checked in.',
                    'meeting_id' => $meeting->id,
                    'meeting_title' => $meeting->title,
                    'start_time' => $meeting->start_time,
                    'end_time' => $meeting->end_time
                ]);
            }
        }

        // If we've reached this point, the user does not have an active attendance in an ongoing meeting
        return response()->json([
            'in_meeting' => false,
            'message' => 'User is not currently in an ongoing meeting or has not checked in or already checked out.'
        ]);
    }
}