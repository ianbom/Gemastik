<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContentRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'content_type' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,pdf|max:10240',
            'media_urls' => 'nullable|array',
            'media_urls.*' => 'nullable|url',
            'media_types' => 'nullable|array',
            'media_types.*' => 'required|in:image,video',
        ];
    }

     public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter',
            'body.required' => 'Konten harus diisi',
            'content_type.in' => 'Tipe konten harus berupa: article, news, atau guide',
            'media.*.mimes' => 'File harus berupa gambar (jpeg, png, jpg, gif, svg) atau video (mp4, avi, mov, wmv)',
            'media.*.max' => 'Ukuran file tidak boleh lebih dari 10MB',
        ];
    }
}
