<?php

namespace App\Http\Controllers\Meetings;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Http\Resources\MeetingResource;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::all();
        return MeetingResource::collection($meetings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingRequest  $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $meeting = Meeting::create($validated);

            // Initialize an empty collection for users
            $users = collect();

            // Include all users if specified
            if ($request->has('include_all_users') && $request->include_all_users) {
                $users = User::where('status', 'active')->get();
            }
            // Include users based on department
            elseif ($request->has('department_id')) {
                $users = User::whereHas('division', function ($query) use ($validated) {
                    $query->where('department_id', $validated['department_id']);
                })->where('status', 'active')->get();
            }
            // Include users based on division
            elseif ($request->has('division_id')) {
                $users = User::where('division_id', $validated['division_id'])
                    ->where('status', 'active')
                    ->get();
            }
            // Include users based on specific user IDs
            elseif ($request->has('user_ids')) {
                $users = User::whereIn('id', $validated['user_ids'])
                    ->where('status', 'active')
                    ->get();
            }
            // Include users based on role if Coordinator is set up
            elseif ($request->has('coordinator_meeting') && $request->coordinator_meeting) {
                $coordinators = User::whereIn('role_id', [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])
                    ->where('status', 'active')
                    ->get();
                $users = $users->merge($coordinators);
            }
            // Include core team members (Sekretaris and Bendahara) if 'core_meeting' is set up
            elseif ($request->has('core_meeting') && $request->core_meeting) {
                $core_members = User::whereIn('role_id', [2, 4])
                    ->where('status', 'active')
                    ->get();
                $users = $users->merge($core_members);
            }

            // Filter out admin users
            $users = $users->reject(function ($user) {
                return $user->isAdmin();
            })->values();

            // Check if there are selected users
            if ($users->isEmpty()) {
                throw new \Exception('No users selected for this meeting.');
            }

            // Attach users to the meeting using the pivot table meeting_user
            $meeting->users()->attach($users->pluck('id')->toArray());

            // Commit transaction if all operations are successful
            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            // Rollback transaction if there is an error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        return new MeetingResource($meeting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingRequest $request, Meeting $meeting)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Update meeting data
            $meeting->update($validated);

            // Initialize an empty collection for users
            $users = collect();

            // Include all users if specified
            if ($request->has('include_all_users') && $request->include_all_users) {
                $users = User::where('status', 'active')->get();
            }
            // Include users based on department
            elseif ($request->has('department_id')) {
                $users = User::whereHas('division', function ($query) use ($validated) {
                    $query->where('department_id', $validated['department_id']);
                })->where('status', 'active')->get();
            }
            // Include users based on division
            elseif ($request->has('division_id')) {
                $users = User::where('division_id', $validated['division_id'])
                    ->where('status', 'active')
                    ->get();
            }
            // Include users based on specific user IDs
            elseif ($request->has('user_ids')) {
                $users = User::whereIn('id', $validated['user_ids'])
                    ->where('status', 'active')
                    ->get();
            }
            // Include users based on role if Coordinator is set up
            elseif ($request->has('coordinator_meeting') && $request->coordinator_meeting) {
                $coordinators = User::whereIn('role_id', [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])
                    ->where('status', 'active')
                    ->get();
                $users = $users->merge($coordinators);
            }
            // Include core team members (Sekretaris and Bendahara) if 'core_meeting' is set up
            elseif ($request->has('core_meeting') && $request->core_meeting) {
                $core_members = User::whereIn('role_id', [2, 4])
                    ->where('status', 'active')
                    ->get();
                $users = $users->merge($core_members);
            }

            // Filter out admin users
            $users = $users->reject(function ($user) {
                return $user->isAdmin();
            })->values();

            // Check if there are selected users
            if ($users->isEmpty()) {
                throw new \Exception('No users selected for this meeting.');
            }

            // Synchronize users to the meeting (update users associated with the meeting)
            $meeting->users()->sync($users->pluck('id')->toArray());

            // Commit transaction if all operations are successful
            DB::commit();

            return new MeetingResource($meeting);
        } catch (\Exception $e) {
            // Rollback transaction if there is an error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        DB::beginTransaction();

        try {
            // Detach the meeting from all users before deleting
            $meeting->users()->detach();

            // Delete the meeting
            $meeting->delete();

            // Commit transaction if all operations are successful
            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            // Rollback transaction if there is an error
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getUsersForMeeting($id)
    {
        // Check if the meeting with the given ID exists
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json(['error' => 'Meeting not found.'], 404);
        }

        // Retrieve users associated with this meeting
        $users = $meeting->users;

        // Return the list of users as JSON
        return response()->json(['users' => $users], 200);
    }

    /**
     * Get ongoing meetings where the end_time is still in the future.
     */
    public function ongoingMeetings()
    {
        $ongoingMeetings = Meeting::where('start_time', '<=', Carbon::now())
            ->where('end_time', '>', Carbon::now())
            ->get();

        if ($ongoingMeetings->isEmpty()) {
            return response()->json(['message' => 'No ongoing meetings found'], 404);
        }
        return MeetingResource::collection($ongoingMeetings);
    }

    /**
     * Get completed meetings where the end_time is in the past.
     */
    public function completedMeetings()
    {
        $now = Carbon::now();
        $query = Meeting::where('end_time', '<=', $now->format('Y-m-d H:i:s'));

        $completedMeetings = $query->get();

        if ($completedMeetings->isEmpty()) {
            return response()->json(['message' => 'No completed meetings found'], 404);
        }

        return MeetingResource::collection($completedMeetings);
    }

    public function upcomingMeetings()
    {
        $now = Carbon::now();
        $nearFuture = $now->copy()->addHours(24); // Taking a meeting in the next 24 hours
    
        $query = Meeting::where('start_time', '>', $now)
                        ->where('start_time', '<=', $nearFuture)
                        ->orderBy('start_time', 'asc');
    
        $upcomingMeetings = $query->get();
    
        if ($upcomingMeetings->isEmpty()) {
            return response()->json(['message' => 'No upcoming meetings found'], 404);
        }
    
        $upcomingMeetingsWithTime = $upcomingMeetings->map(function ($meeting) use ($now) {
            $hoursUntilStart = $now->diffInHours($meeting->start_time, false);
            $meeting->hours_until_start = round($hoursUntilStart);
            return $meeting;
        });
    
        return MeetingResource::collection($upcomingMeetingsWithTime);
    }
}
