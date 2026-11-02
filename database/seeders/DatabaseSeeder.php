<?php

namespace Database\Seeders;

use App\Models\ReportRecipient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Administrator Default Borneo Siaga
        User::firstOrCreate(
            ['email' => 'admin@borneosiaga.id'],
            [
                'name' => 'Admin Posko Borneo',
                'password' => Hash::make('AdminRahasia123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Direktori Instansi Tanggap Darurat & Komunitas Relawan (Default)
        $recipients = [
            [
                'name' => 'BPBD Provinsi Kalimantan Barat',
                'category' => 'pemerintah',
                'agency_type' => 'BPBD',
                'province' => 'Kalimantan Barat',
                'regency' => 'Kota Pontianak',
                'contact_person' => 'Pusdalops BPBD Kalbar',
                'phone' => '0561734567',
                'whatsapp_number' => '081234567801',
                'email' => 'pusdalops.kalbar@bpbd.go.id',
                'address' => 'Jl. Adi Sucipto No. 1, Pontianak',
                'preferred_channel' => 'both',
                'verified_channels' => ['whatsapp', 'email'],
                'is_active' => true,
            ],
            [
                'name' => 'BPBD Provinsi Kalimantan Tengah',
                'category' => 'pemerintah',
                'agency_type' => 'BPBD',
                'province' => 'Kalimantan Tengah',
                'regency' => 'Kota Palangka Raya',
                'contact_person' => 'Posko Karhutla Kalteng',
                'phone' => '05363221234',
                'whatsapp_number' => '081234567802',
                'email' => 'posko.kalteng@bpbd.go.id',
                'address' => 'Jl. Tjilik Riwut Km. 1, Palangka Raya',
                'preferred_channel' => 'both',
                'verified_channels' => ['whatsapp', 'email'],
                'is_active' => true,
            ],
            [
                'name' => 'BPBD Provinsi Kalimantan Selatan',
                'category' => 'pemerintah',
                'agency_type' => 'BPBD',
                'province' => 'Kalimantan Selatan',
                'regency' => 'Kota Banjarbaru',
                'contact_person' => 'Pusdalops BPBD Kalsel',
                'phone' => '05114772345',
                'whatsapp_number' => '081234567803',
                'email' => 'bpbd@kalselprov.go.id',
                'address' => 'Jl. Dharma Praja No. 2, Banjarbaru',
                'preferred_channel' => 'both',
                'verified_channels' => ['whatsapp', 'email'],
                'is_active' => true,
            ],
            [
                'name' => 'BPBD Provinsi Kalimantan Timur',
                'category' => 'pemerintah',
                'agency_type' => 'BPBD',
                'province' => 'Kalimantan Timur',
                'regency' => 'Kota Samarinda',
                'contact_person' => 'Tim Siaga Bencana Kaltim',
                'phone' => '0541743210',
                'whatsapp_number' => '081234567804',
                'email' => 'bpbd@kaltimprov.go.id',
                'address' => 'Jl. Jenderal Sudirman No. 1, Samarinda',
                'preferred_channel' => 'both',
                'verified_channels' => ['whatsapp', 'email'],
                'is_active' => true,
            ],
            [
                'name' => 'Masyarakat Peduli Api (MPA) Sebangau',
                'category' => 'komunitas_mpa',
                'agency_type' => 'MPA',
                'province' => 'Kalimantan Tengah',
                'regency' => 'Kota Palangka Raya',
                'contact_person' => 'Koordinator Relawan Sebangau',
                'phone' => '085249001122',
                'whatsapp_number' => '085249001122',
                'email' => 'relawan.sebangau@borneosiaga.id',
                'address' => 'Desa Kereng Bangkirai, Sebangau',
                'preferred_channel' => 'whatsapp',
                'verified_channels' => ['whatsapp'],
                'is_active' => true,
            ],
        ];

        foreach ($recipients as $recipient) {
            ReportRecipient::firstOrCreate(
                ['name' => $recipient['name']],
                $recipient
            );
        }
    }
}
