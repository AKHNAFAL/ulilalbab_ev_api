<?php

namespace App\Http\Controllers\Meetings;

use App\Models\User;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            // if ($request->has('include_all_users') && $request->include_all_users) {
            //     $users = User::all();
            // } elseif ($request->has('department_id')) {
            //     $users = User::whereHas('division', function ($query) use ($validated) {
            //         $query->where('department_id', $validated['department_id']);
            //     })->get();
            // } elseif ($request->has('division_id')) {
            //     $users = User::where('division_id', $validated['division_id'])->get();
            // } else {
            //     $users = User::whereIn('id', $validated['user_ids'])->get();
            // }

            $users = collect();

            // Include all users if specified
            if ($request->has('include_all_users') && $request->include_all_users) {
                $users = User::all();
            }
            // Include users based on department
            elseif ($request->has('department_id')) {
                $users = User::whereHas('division', function ($query) use ($validated) {
                    $query->where('department_id', $validated['department_id']);
                })->get();
            }
            // Include users based on division
            elseif ($request->has('division_id')) {
                $users = User::where('division_id', $validated['division_id'])->get();
            }
            // Include users based on specific user IDs
            elseif ($request->has('user_ids')) {
                $users = User::whereIn('id', $validated['user_ids'])->get();
            }

            // Add users with specific roles if 'coordinator_meeting' is set
            if ($request->has('coordinator_meeting') && $request->coordinator_meeting) {
                $coordinators = User::whereIn('role_id', [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])->get();
                $users = $users->merge($coordinators);
            }

            // Add core team members (Sekretaris and Bendahara) if 'core_meeting' is set
            if ($request->has('core_meeting') && $request->core_meeting) {
                $core_members = User::whereIn('role_id', [2, 4])->get();
                $users = $users->merge($core_members);
            }

            // Filter out admin users
            $users = $users->reject(function ($user) {
                return $user->isAdmin();
            })->values();

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
    
            // Inisialisasi collection kosong untuk users
            $users = collect();
    
            // Cek apakah user harus di-update berdasarkan kondisi
            if ($request->has('include_all_users') && $request->include_all_users) {
                $users = User::all();
            } elseif ($request->has('department_id')) {
                $users = User::whereHas('division', function ($query) use ($validated) {
                    $query->where('department_id', $validated['department_id']);
                })->get();
            } elseif ($request->has('division_id')) {
                $users = User::where('division_id', $validated['division_id'])->get();
            } elseif ($request->has('user_ids')) {
                $users = User::whereIn('id', $validated['user_ids'])->get();
            }
    
            // Tambahkan users berdasarkan role jika 'coordinator_meeting' diatur
            if ($request->has('coordinator_meeting') && $request->coordinator_meeting) {
                $coordinators = User::whereIn('role_id', [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])->get();
                $users = $users->merge($coordinators);
            }
    
            // Tambahkan core team members (Sekretaris dan Bendahara) jika 'core_meeting' diatur
            if ($request->has('core_meeting') && $request->core_meeting) {
                $core_members = User::whereIn('role_id', [2, 4])->get();
                $users = $users->merge($core_members);
            }
    
            // Filter out admin users (jika ada logika untuk memfilter admin)
            $users = $users->reject(function ($user) {
                return $user->isAdmin();
            })->values();
    
            // Mengecek apakah ada user yang dipilih
            if ($users->isEmpty()) {
                throw new \Exception('No users selected for this meeting.');
            }
    
            // Sinkronisasi users ke meeting (update user yang dihubungkan dengan meeting)
            $meeting->users()->sync($users->pluck('id')->toArray());
    
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
    
            return new MeetingResource($meeting);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
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
            // Memutuskan hubungan meeting dengan semua users sebelum menghapus
            $meeting->users()->detach();

            // Menghapus meeting
            $meeting->delete();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getUsersForMeeting($id)
    {
        // Cek apakah meeting dengan ID tersebut ada
        $meeting = Meeting::find($id);
    
        if (!$meeting) {
            return response()->json(['error' => 'Meeting not found.'], 404);
        }
    
        // Ambil user yang terkait dengan meeting ini
        $users = $meeting->users;
    
        // Kembalikan daftar user dalam bentuk JSON
        return response()->json(['users' => $users], 200);
    }
}
