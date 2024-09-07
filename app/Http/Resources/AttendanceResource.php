<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'user_id' => $this->user_id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'location_id' => $this->location_id,
            'attachment_id' => $this->attachment_id,
            'meeting_id' => $this->meeting_id,
        ];
    }
}
