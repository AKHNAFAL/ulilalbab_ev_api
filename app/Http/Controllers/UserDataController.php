<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}

// <?php

// use App\Models\Post;
// use Illuminate\Http\Request;
// use App\Http\Resources\PostResource;
// use App\Http\Resources\PostDetailResource;

// class PostController extends Controller
// {
//     public function index()
//     {
//         $posts = Post::all();
//         return PostResource::collection($posts);
//     }

//     public function show($id)
//     {
//         $post = Post::findOrFail($id);
//         return new PostDetailResource($post);
//     }
// }
