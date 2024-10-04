<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Http\Resources\AdminAttendanceResource;
use Illuminate\Http\Request;

class AdminAttendanceMonitoringController extends Controller
{
    public function monitorMeetingAttendance(Request $request, $meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);
        
        $attendances = Attendance::where('meeting_id', $meetingId)
            ->with(['user:id,name,email,member_id', 'location:id,name'])
            ->get();

        $totalParticipants = $meeting->users()->count();
        $presentParticipants = $attendances->where('check_in', '!=', null)->count();
        $absentParticipants = $totalParticipants - $presentParticipants;

        $data = [
            'meeting' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'start_time' => $meeting->start_time->format('Y-m-d H:i'),
                'end_time' => $meeting->end_time->format('Y-m-d H:i'),
                'total_participants' => $totalParticipants,
                'present_participants' => $presentParticipants,
                'absent_participants' => $absentParticipants,
            ],
            'attendances' => AdminAttendanceResource::collection($attendances),
        ];

        return response()->json($data);
    }
}