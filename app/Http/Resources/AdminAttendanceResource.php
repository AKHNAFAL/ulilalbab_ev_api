<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminAttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'member_id' => $this->user->member_id,
            ],
            'check_in' => $this->check_in ? $this->check_in->format('Y-m-d H:i:s') : null,
            'check_out' => $this->check_out ? $this->check_out->format('Y-m-d H:i:s') : null,
            'location' => $this->location ? [
                'id' => $this->location->id,
                'name' => $this->location->name,
            ] : null,
            'status' => $this->getAttendanceStatus(),
        ];
    }

    private function getAttendanceStatus()
    {
        if ($this->check_in && $this->check_out) {
            return 'Completed';
        } elseif ($this->check_in) {
            return 'Checked In';
        } else {
            return 'Absent';
        }
    }
}