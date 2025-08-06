<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya pengguna yang terautentikasi yang bisa melakukan request ini
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = $this->user()->id;

        return [
            'name'        => ['nullable', 'string', 'max:255'],
            'email'       => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id'     => ['nullable', 'integer', 'exists:cities,id'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
            'profile_url' => ['nullable', 'file', 'image', 'max:2048'],
            'current_password' => ['required_with:password', 'current_password:web'],
            'community.name' => ['nullable', 'string', 'max:255'],
            'community.description' => ['nullable', 'string'],
            'community.member_count' => ['nullable', 'integer'],
            'role' => 'nullable'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.unique'    => 'The email address is already taken.',
            'password.min'    => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'province_id.exists' => 'The selected province is invalid.',
            'city_id.exists'     => 'The selected city is invalid.',
            'district_id.exists' => 'The selected district is invalid.',
        ];
    }
}
