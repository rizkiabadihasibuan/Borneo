<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackReportRequest extends FormRequest
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
            'ticket_number' => ['required', 'string', 'max:32'],
            'access_code' => ['required', 'string', 'min:6', 'max:32'],
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'ticket_number.required' => 'Nomor tiket pengaduan wajib diisi.',
            'access_code.required' => 'Kode akses pengaduan wajib diisi.',
            'access_code.min' => 'Kode akses minimal terdiri dari 6 karakter.',
        ];
    }
}
