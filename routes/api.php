<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\Meetings\MeetingController;
use App\Http\Controllers\Meetings\LocationController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:sanctum', 'adminAccess'])->group(function () {
    Route::get('meetings/users/{meeting}', [MeetingController::class, 'getUsersForMeeting']);
    Route::get('meetings/ongoing', [MeetingController::class, 'ongoingMeetings']);
    Route::get('meetings/completed', [MeetingController::class, 'completedMeetings']);
    Route::get('meetings/upcoming', [MeetingController::class, 'upcomingMeetings']);

    Route::apiResource('meetings', MeetingController::class); 
    Route::apiResource('users', UserDataController::class);
    Route::apiResource('locations', LocationController::class);
});