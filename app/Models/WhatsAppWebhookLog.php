<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppWebhookLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_webhook_logs';

    protected $fillable = [
        'message_id',
        'recipient_id',
        'event_status',
        'idempotency_key',
        'raw_payload',
        'is_processed',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'is_processed' => 'boolean',
        ];
    }
}
