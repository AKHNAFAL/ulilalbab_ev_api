<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AttendanceHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dateTime = Carbon::parse($this['time']);
        
        return [
            'type' => $this['type'],
            'date' => $dateTime->format('Y-m-d'),
            'time' => $dateTime->format('H:i'),
            'meeting' => $this['meeting'] ? [
                'id' => $this['meeting']->id,
                'title' => $this['meeting']->title,
                'start_time' => Carbon::parse($this['meeting']->start_time)->format('H:i'),
                'end_time' => Carbon::parse($this['meeting']->end_time)->format('H:i'),
            ] : null,
            'location' => $this['location'] ? [
                'id' => $this['location']->id,
                'name' => $this['location']->name,
            ] : null,
        ];
    }
}