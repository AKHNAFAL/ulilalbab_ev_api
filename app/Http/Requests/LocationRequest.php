<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidCoordinate($value)) {
                        $fail('The '.$attribute.' is not a valid coordinate.');
                    }
                },
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidCoordinate($value)) {
                        $fail('The '.$attribute.' is not a valid coordinate.');
                    }
                },
            ],
            'name' => 'required|string|max:100|unique:locations,name', // Perbaikan sesuai tabel
        ];
    }

    // Jika ingin menangkap IP user untuk validasi lebih ketat
    // https://shouts.dev/articles/get-user-geographical-location-in-laravel#:~:text=We%E2%80%99ll%20use%20laravel-geoip%20package.%20Install%20the%20package%20using,Open%20up%20config%2Fapp.php%20and%20find%20the%20providers%20key.

    protected function isValidCoordinate($value)
    {
        // Implementasi validasi koordinat yang lebih ketat
        // Misalnya, memeriksa jumlah desimal, format, dll.
        return preg_match('/^-?\d+(\.\d{1,6})?$/', $value);
    }
}
