<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'content_type' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,pdf|max:10240',
            'media_urls' => 'nullable|array',
            'media_urls.*' => 'nullable|url',
            'media_types' => 'nullable|array',
            'media_types.*' => 'nullable|in:image,video',
        ];
    }
}
