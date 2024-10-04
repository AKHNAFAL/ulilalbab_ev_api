<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'start_time',
        'end_time',
        'location_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_user', 'meeting_id', 'user_id');
    }

    // Accessor to determine meeting type
    public function getMeetingTypeAttribute()
    {
        // Check if meeting is an all members meeting
        $allActiveUsersCount = User::where('status', 'active')->count();
        if ($this->include_all_users || $this->users->count() === $allActiveUsersCount) {
            return 'All Members Meeting';
        }

        // Check if meeting is division meeting first
        elseif ($this->division_id && $this->users->contains(function ($user) {
            return $user->division_id == $this->division_id;
        })) {
            return 'Division Meeting';
        }

        // Check if meeting is department meeting
        elseif ($this->department_id && $this->users->contains(function ($user) {
            return $user->division && $user->division->department_id == $this->department_id;
        })) {
            return 'Department Meeting';
        }

        // Check if meeting is coordinator meeting
        elseif ($this->users->contains(function ($user) {
            return in_array($user->role_id, [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]);
        })) {
            return 'Coordinator Meeting';
        }

        // Check if meeting is core meeting
        elseif ($this->users->contains(function ($user) {
            return in_array($user->role_id, [2, 4]);
        })) {
            return 'Core Meeting';
        }

        // Default to general meeting type if none match
        return 'Custom Meeting';
    }
}
