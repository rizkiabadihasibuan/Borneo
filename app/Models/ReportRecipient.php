<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportRecipient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'agency_type',
        'province',
        'regency',
        'contact_person',
        'phone',
        'whatsapp_number',
        'email',
        'address',
        'admin_notes',
        'preferred_channel',
        'verified_channels',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'verified_channels' => 'array',
        ];
    }

    /**
     * Format nomor WhatsApp ke format internasional (misal: 628123456789).
     */
    public function getCleanWhatsappNumberAttribute(): ?string
    {
        $number = $this->whatsapp_number ?: $this->phone;
        if (!$number) {
            return null;
        }

        // Hapus karakter non-numerik
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        // Ubah 08xxx menjadi 628xxx
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Periksa ketersediaan kanal WhatsApp terverifikasi.
     */
    public function isWhatsappAvailable(): bool
    {
        $hasNumber = !empty($this->whatsapp_number) || !empty($this->phone);
        $channels = $this->verified_channels ?: [];
        return $hasNumber && in_array('whatsapp', $channels);
    }

    /**
     * Periksa ketersediaan kanal Email terverifikasi.
     */
    public function isEmailAvailable(): bool
    {
        $hasEmail = !empty($this->email);
        $channels = $this->verified_channels ?: [];
        return $hasEmail && in_array('email', $channels);
    }

    /**
     * Relasi ke seluruh disposisi yang ditujukan ke instansi/komunitas ini.
     */
    public function dispatches(): HasMany
    {
        return $this->hasMany(ReportDispatch::class, 'recipient_id');
    }

    /**
     * Label kategori dalam bahasa Indonesia.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'pemerintah' => 'Instansi Pemerintah',
            'komunitas_mpa' => 'Masyarakat Peduli Api (MPA)',
            default => ucwords(str_replace('_', ' ', $this->category)),
        };
    }
}
