<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\Attachment;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckInController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'meeting_id' => 'required|exists:meetings,id',
                'location_id' => 'required|exists:locations,id',
                'selfie_photo' => 'required|file|image|max:5120', // Max 5MB
                'note' => 'nullable|string|max:1000',
            ]);

            $user = Auth::user();
            $meeting = Meeting::findOrFail($request->meeting_id);

            // Check if the user is part of the meeting
            if (!$meeting->users->contains($user->id)) {
                return response()->json(['message' => 'You are not part of this meeting.'], 403);
            }

            // Check if the meeting is ongoing
            $now = Carbon::now();
            if ($now->lt($meeting->start_time) || $now->gt($meeting->end_time)) {
                return response()->json(['message' => 'Check-in is only allowed during the meeting time.'], 400);
            }

            // Check if the user has already checked in
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->where('meeting_id', $meeting->id)
                ->first();

            if ($existingAttendance) {
                return response()->json(['message' => 'You have already checked in for this meeting.'], 400);
            }

            DB::beginTransaction();

            // Handle file upload
            $path = $request->file('selfie_photo')->store('attachments', 'public');

            // Create attachment
            $attachment = Attachment::create([
                'file_url' => $path,
                'note' => $request->input('note'),
            ]);

            // Create attendance record
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'meeting_id' => $request->input('meeting_id'),
                'check_in' => $now,
                'location_id' => $request->input('location_id'),
                'attachment_id' => $attachment->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Check-in successful',
                'attendance' => $attendance,
                'attachment' => $attachment
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            // Delete the uploaded file if it exists
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            Log::error('Check-in failed: ' . $e->getMessage());
            return response()->json(['message' => 'Check-in failed: ' . $e->getMessage()], 500);
        }
    }
}