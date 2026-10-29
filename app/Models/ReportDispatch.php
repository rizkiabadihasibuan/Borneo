<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class ReportDispatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_id',
        'recipient_id',
        'admin_id',
        'channel',
        'dispatch_notes',
        'include_photo',
        'include_reporter_contact',
        'status',
        'retry_count',
        'recipient_status',
        'reference_id',
        'provider_message_id',
        'delivery_error',
        'recipient_feedback',
        'sent_at',
        'delivered_at',
        'read_at',
        'confirmed_at',
        'completed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'include_photo' => 'boolean',
            'include_reporter_contact' => 'boolean',
            'retry_count' => 'integer',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'read_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke pengaduan masyarakat asal.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke penerima laporan (instansi atau MPA).
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(ReportRecipient::class, 'recipient_id');
    }

    /**
     * Relasi ke administrator yang melakukan disposisi.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Riwayat kronologis status disposisi.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ReportDispatchHistory::class, 'report_dispatch_id')->orderBy('created_at', 'asc');
    }

    /**
     * Dapatkan URL bertanda tangan (cryptographically signed URL) dengan masa berlaku 7 hari
     * untuk membagikan foto bukti secara aman tanpa eksposur URL publik langsung.
     */
    public function getSecurePhotoUrlAttribute(): ?string
    {
        if (!$this->include_photo || !$this->report || !$this->report->photo_path) {
            return null;
        }

        return URL::temporarySignedRoute(
            'dispatch.photo.view',
            now()->addDays(7),
            ['dispatch' => $this->id]
        );
    }
}
