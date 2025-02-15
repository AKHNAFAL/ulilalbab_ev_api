<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\Meetings\MeetingController;
use App\Http\Controllers\Meetings\LocationController;
use App\Http\Controllers\Attendance\CheckInController;
use App\Http\Controllers\Attendance\CheckOutController;
use App\Http\Controllers\Attendance\CheckUserActiveController;
use App\Http\Controllers\Attendance\HasOngoingMeetingController;
use App\Http\Controllers\Attendance\UserAttendanceHistoryController;
use App\Http\Controllers\Attendance\AdminAttendanceMonitoringController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user/has-ongoing-meeting', HasOngoingMeetingController::class);
    Route::post('/user/check-in', CheckInController::class);
    Route::post('/user/check-out', CheckOutController::class);
    Route::get('/user/check-user-in-meeting', CheckUserActiveController::class);
    Route::get('/user/attendances-history', UserAttendanceHistoryController::class);

});

Route::middleware(['auth:sanctum', 'adminAccess'])->group(function () {
    Route::get('meetings/users/{meeting}', [MeetingController::class, 'getUsersForMeeting']);
    Route::get('meetings/ongoing', [MeetingController::class, 'ongoingMeetings']);
    Route::get('meetings/completed', [MeetingController::class, 'completedMeetings']);
    Route::get('meetings/upcoming', [MeetingController::class, 'upcomingMeetings']);
    Route::get('meetings/{meetingId}/attendance', [AdminAttendanceMonitoringController::class, 'monitorMeetingAttendance']);
    
    Route::apiResource('meetings', MeetingController::class); 
    Route::apiResource('users', UserDataController::class);
    Route::apiResource('locations', LocationController::class);
});