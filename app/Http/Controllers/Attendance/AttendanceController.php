<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::all();
        return AttendanceResource::collection($attendance);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}


// public function index()
// {
//     $posts = Post::all();
//     return PostResource::collection($posts);
// }

// public function show($id)
// {
//     $post = Post::with('writer:id,username')->findOrFail($id);
//     return new PostDetailResource($post);
// }

// public function show2($id)
// {
//     $post = Post::findOrFail($id);
//     return new PostDetailResource($post);
// }