<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatusHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_id',
        'previous_status',
        'new_status',
        'notes',
        'admin_id',
    ];

    /**
     * Relasi balik ke laporan.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke admin yang memperbarui status jika ada.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Label status baru dalam bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->new_status) {
            'Menunggu Verifikasi', 'menunggu_verifikasi', 'diterima' => 'Menunggu Verifikasi',
            'Sedang Diverifikasi', 'dalam_verifikasi', 'sedang_diverifikasi' => 'Sedang Diverifikasi',
            'Dalam Penanganan', 'ditindaklanjuti', 'dalam_penanganan' => 'Dalam Penanganan',
            'Selesai Ditangani', 'selesai', 'selesai_ditangani' => 'Selesai Ditangani',
            'Ditolak', 'ditolak' => 'Ditolak',
            default => ucwords(str_replace('_', ' ', (string) $this->new_status)),
        };
    }
}
