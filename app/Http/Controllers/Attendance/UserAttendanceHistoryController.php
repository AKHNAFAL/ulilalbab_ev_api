<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AttendanceHistoryResource;
use Carbon\Carbon;

class UserAttendanceHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        
        $attendances = Attendance::where('user_id', $user->id)
            ->with(['meeting:id,title,start_time,end_time', 'location:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $history = $attendances->flatMap(function ($attendance) {
            $items = [];

            // Check-in entry
            if ($attendance->check_in) {
                $items[] = [
                    'type' => 'check_in',
                    'time' => $attendance->check_in,
                    'meeting' => $attendance->meeting,
                    'location' => $attendance->location,
                ];
            }

            // Check-out entry
            if ($attendance->check_out) {
                $items[] = [
                    'type' => 'check_out',
                    'time' => $attendance->check_out,
                    'meeting' => $attendance->meeting,
                    'location' => $attendance->location,
                ];
            }

            return $items;
        })->sortByDesc('time')->values();

        // Paginate the results
        $perPage = 10; // You can adjust this number
        $page = $request->input('page', 1);
        $paginatedHistory = new \Illuminate\Pagination\LengthAwarePaginator(
            $history->forPage($page, $perPage),
            $history->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return AttendanceHistoryResource::collection($paginatedHistory)->additional([
            'message' => 'User attendance history retrieved successfully.'
        ]);
    }
}