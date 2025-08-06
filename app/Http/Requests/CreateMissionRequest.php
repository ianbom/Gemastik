<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMissionRequest extends FormRequest
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
            'report_id' => [
                'nullable',
                'exists:reports,id',
                // Validasi bahwa report_id belum memiliki mission lain
                Rule::unique('missions', 'report_id')
            ],
            'province_id' => 'required',
            'thumbnail_url' => 'required',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|in:open,on-progress,completed,cancelled',
            'scheduled_date' => 'nullable|date|after:now',
            'assigned_to_type' => 'nullable|in:community,volunteer',
            'assigned_volunteer_id' => 'nullable|exists:users,id',
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
            'report_id.exists' => 'Report tidak ditemukan.',
            'report_id.unique' => 'Report ini sudah memiliki mission. Satu report hanya bisa memiliki satu mission.',
            'city_id.required' => 'Kota harus dipilih.',
            'city_id.exists' => 'Kota tidak ditemukan.',
            'district_id.required' => 'Kecamatan harus dipilih.',
            'district_id.exists' => 'Kecamatan tidak ditemukan.',
            'title.required' => 'Judul misi harus diisi.',
            'title.max' => 'Judul misi maksimal 255 karakter.',
            'description.required' => 'Deskripsi misi harus diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada antara -90 dan 90.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada antara -180 dan 180.',
            'address.max' => 'Alamat maksimal 1000 karakter.',
            'status.in' => 'Status harus salah satu dari: open, on-progress, completed, cancelled.',
            'scheduled_date.date' => 'Tanggal terjadwal harus berupa tanggal yang valid.',
            'scheduled_date.after' => 'Tanggal terjadwal harus setelah waktu sekarang.',
            'assigned_to_type.in' => 'Tipe penugasan harus community atau volunteer.',
            'assigned_volunteer_id.exists' => 'Volunteer tidak ditemukan.',
        ];
    }



}
