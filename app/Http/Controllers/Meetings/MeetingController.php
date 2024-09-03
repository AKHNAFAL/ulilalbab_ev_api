<?php

namespace App\Http\Controllers\Meetings;

use App\Models\User;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'type' => ['required', 'string', 'in:online,offline,hybrid'],
        'start_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:tomorrow'],
        'end_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:start_time'],
        'location_id' => ['required', 'int', 'exists:locations,id'],
        'user_ids' => ['array'],
        'user_ids.*' => ['int', 'exists:users,id'],
        'division_id' => ['int', 'exists:division,id'],
        'department_id' => ['int', 'exists:departments,id'],
        'include_all_users' => ['boolean'],
    ]);

    DB::beginTransaction();

    try {
        $meeting = Meeting::create($validated);

        if ($request->has('include_all_users') && $request->include_all_users) {
            $users = User::all();
        } elseif ($request->has('department_id')) {
            $users = User::whereHas('division', function ($query) use ($validated) {
                $query->where('department_id', $validated['department_id']);
            })->get();
        } elseif ($request->has('division_id')) {
            $users = User::where('division_id', $validated['division_id'])->get();
        } else {
            $users = User::whereIn('id', $validated['user_ids'])->get();
        }

        // Mengecek apakah ada user yang dipilih
        if ($users->isEmpty()) {
            throw new \Exception('No users selected for this meeting.');
        }

        // Menghubungkan users ke meeting menggunakan tabel pivot meeting_user
        $meeting->users()->attach($users->pluck('id')->toArray());

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        return response()->noContent();
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();

        return response()->json(['error' => $e->getMessage()], 400);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}
