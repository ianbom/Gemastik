<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'reply_id' => 'nullable|exists:comments,id',
            'report_id' => 'required|exists:reports,id',
            'comment' => 'nullable|required_without:media|string|max:1000',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB
            'media_type' => 'nullable|in:image,video',
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
            'reply_id.exists' => 'Parent comment tidak ditemukan.',
            'report_id.required' => 'Report ID harus diisi.',
            'report_id.exists' => 'Report tidak ditemukan.',
            'comment.required_without' => 'Komentar harus diisi jika tidak ada media.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
            'media.file' => 'Media harus berupa file.',
            'media.mimes' => 'Media harus berupa gambar (jpeg, png, jpg, gif) atau video (mp4, mov, avi).',
            'media.max' => 'Ukuran media maksimal 10MB.',
            'media_type.in' => 'Tipe media harus image atau video.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-detect media type based on file extension if not provided
        if ($this->hasFile('media') && !$this->media_type) {
            $extension = $this->file('media')->getClientOriginalExtension();
            $imageExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi'];

            if (in_array(strtolower($extension), $imageExtensions)) {
                $this->merge(['media_type' => 'image']);
            } elseif (in_array(strtolower($extension), $videoExtensions)) {
                $this->merge(['media_type' => 'video']);
            }
        }
    }
}
