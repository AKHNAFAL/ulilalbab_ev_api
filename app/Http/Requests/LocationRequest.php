<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->isAdmin();
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
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('locations')->ignore($this->route('location')),
            ],
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
                'regex:/^-?\d{1,2}(\.\d{1,6})?$/',
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
                'regex:/^-?\d{1,3}(\.\d{1,6})?$/',
            ],
            'description_address' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'latitude.regex' => 'The latitude must have up to 6 decimal places.',
            'longitude.regex' => 'The longitude must have up to 6 decimal places.',
        ];
    }
}

// Jika ingin menangkap IP user untuk validasi lebih ketat
// https://shouts.dev/articles/get-user-geographical-location-in-laravel#:~:text=We%E2%80%99ll%20use%20laravel-geoip%20package.%20Install%20the%20package%20using,Open%20up%20config%2Fapp.php%20and%20find%20the%20providers%20key.