<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HasOngoingMeetingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $user = Auth::user();
        $now = Carbon::now();

        $ongoingMeeting = Meeting::where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['location', 'users']) // Eager load related data
            ->first();

        if ($ongoingMeeting) {
            return response()->json([
                'has_ongoing_meeting' => true,
                'meeting_id' => $ongoingMeeting->id,
                // 'meeting_data' => [
                //     'title' => $ongoingMeeting->title,
                //     'type' => $ongoingMeeting->type,
                //     'start_time' => $ongoingMeeting->start_time->toDateTimeString(),
                //     'end_time' => $ongoingMeeting->end_time->toDateTimeString(),
                //     'location' => [
                //         'id' => $ongoingMeeting->location->id,
                //         'name' => $ongoingMeeting->location->name,
                //         'latitude' => $ongoingMeeting->location->latitude,
                //         'longitude' => $ongoingMeeting->location->longitude,
                //     ],
                //     'participants_count' => $ongoingMeeting->users->count(),
                //     'time_remaining' => $now->diffInMinutes($ongoingMeeting->end_time) . ' minutes',
                // ],
            ]);
        }

        return response()->json(['has_ongoing_meeting' => false]);
    }
}