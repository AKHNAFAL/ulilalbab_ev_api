<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:online,offline,hybrid'],
            'start_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'end_time' => ['required', 'date_format:Y-m-d H:i:s', 'after:start_time'],
            'location_id' => ['required', 'int', 'exists:locations,id'],
            'include_all_users' => 'sometimes|boolean',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'division_id' => 'sometimes|integer|exists:divisions,id',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'integer|exists:users,id',
            'coordinator_meeting' => 'sometimes|boolean',
            'core_meeting' => 'sometimes|boolean',
        ];
    }
}
