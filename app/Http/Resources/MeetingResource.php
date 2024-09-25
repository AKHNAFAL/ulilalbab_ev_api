<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'start_time' => $this->start_time->format('Y-m-d H:i:s'), // Sesuaikan dengan format date di request
            'end_time' => $this->end_time->format('Y-m-d H:i:s'), // Sesuaikan dengan format date di request
            'location_id' => $this->location_id, // Tambahkan jika diperlukan dari location_id request
            // 'include_all_users' => $this->when(isset($this->include_all_users), $this->include_all_users),
            // 'department_id' => $this->when(isset($this->department_id), $this->department_id),
            // 'division_id' => $this->when(isset($this->division_id), $this->division_id),
            // 'user_ids' => $this->whenLoaded('users', fn() => $this->users->pluck('id')->all()), // Mengambil IDs user jika relasi users di-load
            // 'coordinator_meeting' => $this->when(isset($this->coordinator_meeting), $this->coordinator_meeting),
            // 'core_meeting' => $this->when(isset($this->core_meeting), $this->core_meeting),
            // 'meeting_type' => $this->meeting_type,
            'hours_until_start' => $this->when(isset($this->hours_until_start), $this->hours_until_start),
        ];
    }
}
