<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReedemsRequest extends FormRequest
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
            'merchandise_id' => 'required|exists:merchandise,id',
            'points_spent' => 'required|integer|min:1',
            'shipping_address' => 'required|string|min:10|max:500',
        ];
    }

    public function messages()
    {
        return [
            'merchandise_id.required' => 'Merchandise harus dipilih',
            'merchandise_id.exists' => 'Merchandise tidak ditemukan',
            'points_spent.required' => 'Jumlah poin harus diisi',
            'points_spent.integer' => 'Jumlah poin harus berupa angka',
            'points_spent.min' => 'Jumlah poin minimal 1',
            'shipping_address.required' => 'Alamat pengiriman harus diisi',
            'shipping_address.min' => 'Alamat pengiriman minimal 10 karakter',
            'shipping_address.max' => 'Alamat pengiriman maksimal 500 karakter',
        ];
    }
}
