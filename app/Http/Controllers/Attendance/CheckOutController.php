<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckOutController extends Controller
{
    /**
     * Handle the check-out request.
     */
    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();

            // Find the user's most recent active attendance
            $attendance = Attendance::where('user_id', $user->id)
                ->whereNull('check_out')
                ->orderBy('check_in', 'desc')
                ->first();

            if (!$attendance) {
                return response()->json(['message' => 'No active check-in found.'], 400);
            }

            $meeting = Meeting::findOrFail($attendance->meeting_id);

            // Check if the meeting is still ongoing or has just ended
            $now = Carbon::now();
            $gracePeriod = $meeting->end_time->addMinutes(30); // 30 minutes grace period after meeting end
            if ($now->lt($meeting->start_time)) {
                return response()->json(['message' => 'The meeting has not started yet.'], 400);
            }
            if ($now->gt($gracePeriod)) {
                return response()->json(['message' => 'The check-out period for this meeting has expired.'], 400);
            }

            DB::beginTransaction();

            // Update the attendance record with check-out time
            $attendance->update([
                'check_out' => $now,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Check-out successful',
                'attendance' => $attendance
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-out failed: ' . $e->getMessage());
            return response()->json(['message' => 'Check-out failed: ' . $e->getMessage()], 500);
        }
    }
}