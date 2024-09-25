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
     * Store a newly created resource in storage. DONE IN AUTH
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id', // Validasi role_id berdasarkan roles table
            'division_id' => 'nullable|exists:division,id', // Validasi division_id berdasarkan division table
            'status' => 'required|in:active,inactive', // Validasi status sesuai enum
            'member_id' => 'nullable|string|max:10|unique:users,member_id,' . $user->id, // Validasi member_id
        ]);
    
        // Update user data
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'] ?? $user->role_id; // Update role_id jika ada
        $user->division_id = $validatedData['division_id'] ?? $user->division_id; // Update division_id jika ada
        $user->status = $validatedData['status']; // Update status
        $user->member_id = $validatedData['member_id'] ?? $user->member_id; // Update member_id jika ada
    
        // Simpan perubahan
        $user->save();
    
        // Return response menggunakan UserResource
        return new UserResource($user);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete(); // Menghapus pengguna dari database
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}