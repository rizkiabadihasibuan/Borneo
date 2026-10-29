<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'access_code_hash',
        'reporter_name',
        'reporter_phone',
        'province',
        'regency',
        'district',
        'village',
        'incident_location',
        'latitude',
        'longitude',
        'incident_time',
        'incident_type',
        'description',
        'photo_path',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_code_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Relasi ke riwayat status laporan.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReportStatusHistory::class)->orderBy('created_at', 'asc');
    }

    /**
     * Relasi ke seluruh disposisi dan penerusan laporan ini ke instansi/MPA.
     */
    public function dispatches(): HasMany
    {
        return $this->hasMany(ReportDispatch::class)->latest();
    }

    /**
     * Verifikasi kode akses pengguna terhadap hash yang tersimpan.
     */
    public function verifyAccessCode(string $code): bool
    {
        return Hash::check(trim($code), $this->access_code_hash);
    }

    /**
     * Label status bahasa Indonesia yang ramah pengguna.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Menunggu Verifikasi', 'menunggu_verifikasi', 'diterima' => 'Menunggu Verifikasi',
            'Sedang Diverifikasi', 'dalam_verifikasi', 'sedang_diverifikasi' => 'Sedang Diverifikasi',
            'Dalam Penanganan', 'ditindaklanjuti', 'dalam_penanganan' => 'Dalam Penanganan',
            'Selesai Ditangani', 'selesai', 'selesai_ditangani' => 'Selesai Ditangani',
            'Ditolak', 'ditolak' => 'Ditolak',
            default => ucwords(str_replace('_', ' ', $this->status)),
        };
    }
}
