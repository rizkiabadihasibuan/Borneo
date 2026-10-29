<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDispatchHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_dispatch_id',
        'admin_id',
        'previous_status',
        'new_status',
        'notes',
    ];

    /**
     * Relasi ke disposisi induk.
     */
    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(ReportDispatch::class, 'report_dispatch_id');
    }

    /**
     * Relasi ke administrator yang melakukan pembaruan status disposisi.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
