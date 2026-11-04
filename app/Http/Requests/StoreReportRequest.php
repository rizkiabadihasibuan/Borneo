<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs for validation to support both new administrative codes and backwards compatibility.
     */
    protected function prepareForValidation(): void
    {
        $province = $this->input('province') ?: $this->input('province_name');
        $provinceName = $this->input('province_name') ?: $this->input('province');

        $regency = $this->input('regency') ?: $this->input('regency_name');
        $regencyName = $this->input('regency_name') ?: $this->input('regency');

        $district = $this->input('district') ?: $this->input('district_name');
        $districtName = $this->input('district_name') ?: $this->input('district');

        $village = $this->input('village') ?: $this->input('village_name');
        $villageName = $this->input('village_name') ?: $this->input('village');

        $locationDetail = $this->input('location_detail');
        $incidentLocation = $this->input('incident_location') ?: $locationDetail;

        // Jika incident_location masih kosong namun wilayah dipilih, susun otomatis
        if (empty($incidentLocation) && !empty($regency) && !empty($province)) {
            $parts = [];
            if (!empty($village)) $parts[] = 'Desa ' . $village;
            if (!empty($district)) $parts[] = 'Kec. ' . $district;
            $parts[] = $regency;
            $parts[] = $province;
            $incidentLocation = implode(', ', $parts);
        }

        $this->merge([
            'province' => $province,
            'province_name' => $provinceName,
            'regency' => $regency,
            'regency_name' => $regencyName,
            'district' => $district,
            'district_name' => $districtName,
            'village' => $village,
            'village_name' => $villageName,
            'location_detail' => $locationDetail ?: $incidentLocation,
            'incident_location' => $incidentLocation,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reporter_name' => ['required', 'string', 'min:3', 'max:100'],
            'reporter_phone' => ['required', 'string', 'min:8', 'max:25', 'regex:/^[0-9+\-\s()]+$/'],
            
            // Wilayah Administratif
            'province_code' => ['nullable', 'string', 'max:20'],
            'province_name' => ['nullable', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],

            'regency_code' => ['nullable', 'string', 'max:20'],
            'regency_name' => ['nullable', 'string', 'max:100'],
            'regency' => ['required', 'string', 'min:3', 'max:100'],

            'district_code' => ['nullable', 'string', 'max:20'],
            'district_name' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],

            'village_code' => ['nullable', 'string', 'max:25'],
            'village_name' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],

            'location_detail' => ['nullable', 'string', 'max:500'],
            'incident_location' => ['required', 'string', 'min:3', 'max:255'],

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'incident_time' => ['required', 'date', 'before_or_equal:now'],
            'incident_type' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'min:15', 'max:3000'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'reporter_name.required' => 'Nama lengkap pelapor wajib diisi.',
            'reporter_name.min' => 'Nama pelapor minimal terdiri dari 3 karakter.',
            'reporter_name.max' => 'Nama pelapor maksimal 100 karakter.',
            'reporter_phone.required' => 'Nomor kontak pelapor wajib diisi untuk koordinasi lapangan.',
            'reporter_phone.min' => 'Nomor kontak pelapor minimal terdiri dari 8 karakter.',
            'reporter_phone.max' => 'Nomor kontak pelapor maksimal 25 karakter.',
            'reporter_phone.regex' => 'Format nomor kontak tidak valid. Gunakan angka atau format telepon resmi.',
            'province.required' => 'Provinsi lokasi kejadian wajib dipilih.',
            'regency.required' => 'Kabupaten atau Kota lokasi kejadian wajib diisi.',
            'regency.min' => 'Nama kabupaten/kota minimal terdiri dari 3 karakter.',
            'incident_location.required' => 'Alamat atau keterangan lokasi kejadian wajib diisi.',
            'incident_location.min' => 'Alamat lokasi kejadian minimal terdiri dari 3 karakter.',
            'incident_location.max' => 'Alamat lokasi kejadian maksimal 255 karakter.',
            'latitude.numeric' => 'Latitude harus berupa angka koordinat yang valid.',
            'latitude.between' => 'Nilai Latitude harus berada di antara -90 dan 90.',
            'longitude.numeric' => 'Longitude harus berupa angka koordinat yang valid.',
            'longitude.between' => 'Nilai Longitude harus berada di antara -180 dan 180.',
            'incident_time.required' => 'Waktu kejadian kebakaran wajib diisi.',
            'incident_time.date' => 'Format waktu kejadian tidak valid.',
            'incident_time.before_or_equal' => 'Waktu kejadian tidak boleh mendahului waktu saat ini.',
            'incident_type.required' => 'Jenis kejadian kebakaran wajib dipilih.',
            'description.required' => 'Deskripsi situasi kejadian kebakaran wajib diisi.',
            'description.min' => 'Deskripsi situasi kejadian minimal terdiri dari 15 karakter agar informasi jelas.',
            'description.max' => 'Deskripsi situasi maksimal 3000 karakter.',
            'photo.image' => 'File bukti harus berupa gambar visual.',
            'photo.mimes' => 'Format foto bukti yang diperbolehkan hanya JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto bukti maksimal 5 Megabyte (5120 KB).',
        ];
    }
}
