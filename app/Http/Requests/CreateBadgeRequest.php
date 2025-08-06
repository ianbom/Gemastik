<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBadgeRequest extends FormRequest
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
            'description' => 'required|string',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul badge wajib diisi',
            'title.string' => 'Judul badge harus berupa teks',
            'title.max' => 'Judul badge maksimal 255 karakter',
            'description.string' => 'Deskripsi harus berupa teks',
            'icon_url.image' => 'File harus berupa gambar',
            'icon_url.mimes' => 'File harus berformat jpeg, png, jpg, gif, atau svg',
            'icon_url.max' => 'Ukuran file maksimal 2MB',
        ];
    }


}
