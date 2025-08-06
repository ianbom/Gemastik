<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
         return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
            'media_urls' => 'nullable|array',
            'media_urls.*' => 'nullable|url',
            'media_types' => 'nullable|array',
            'media_types.*' => 'nullable|in:image,video',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
          'city_id.required' => 'Kota harus dipilih',
            'city_id.exists' => 'Kota yang dipilih tidak valid',
            'district_id.required' => 'Kecamatan harus dipilih',
            'district_id.exists' => 'Kecamatan yang dipilih tidak valid',
            'title.required' => 'Judul laporan harus diisi',
            'title.max' => 'Judul laporan maksimal 255 karakter',
            'description.required' => 'Deskripsi laporan harus diisi',
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 dan 90',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 dan 180',
            'media.array' => 'Media harus berupa array',
            'media.*.file' => 'Media harus berupa file',
            'media.*.mimes' => 'Media harus berupa file: jpeg, png, jpg, gif, mp4, mov, avi',
            'media.*.max' => 'Ukuran file media maksimal 10MB',
            'media_urls.array' => 'Media URLs harus berupa array',
            'media_urls.*.url' => 'Media URL harus berupa URL yang valid',
            'media_types.array' => 'Media types harus berupa array',
            'media_types.*.in' => 'Media type harus berupa image atau video',
        ];
    }
}
