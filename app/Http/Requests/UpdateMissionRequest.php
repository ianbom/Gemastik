<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMissionRequest extends FormRequest
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
            'report_id' => ['nullable', 'exists:reports,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'thumbnail_url' => 'nullable',
            'district_id' => ['required', 'exists:districts,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['open', 'on-progress', 'completed', 'cancelled'])],
            'scheduled_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'assigned_to_type' => ['nullable', Rule::in(['community', 'volunteer'])],
            'assigned_volunteer_id' => ['nullable', 'exists:users,id'],
        ];
    }

     public function messages(): array
    {
        return [
            'report_id.exists' => 'Report yang dipilih tidak valid.',
            'province_id.exists' => 'Provinsi yang dipilih tidak valid.',
            'city_id.required' => 'Kota harus dipilih.',
            'city_id.exists' => 'Kota yang dipilih tidak valid.',
            'district_id.required' => 'Kecamatan harus dipilih.',
            'district_id.exists' => 'Kecamatan yang dipilih tidak valid.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'latitude.between' => 'Latitude harus antara -90 dan 90.',
            'longitude.between' => 'Longitude harus antara -180 dan 180.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'scheduled_date.date' => 'Tanggal jadwal harus berupa tanggal yang valid.',
            'completed_at.date' => 'Tanggal penyelesaian harus berupa tanggal yang valid.',
            'assigned_to_type.in' => 'Tipe penugasan yang dipilih tidak valid.',
            'assigned_volunteer_id.exists' => 'Volunteer yang dipilih tidak valid.',
        ];
    }
}
