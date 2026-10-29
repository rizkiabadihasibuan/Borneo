<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom pendukung WhatsApp & verifikasi kanal pada direktori penerima
        Schema::table('report_recipients', function (Blueprint $table) {
            $table->string('whatsapp_number', 50)->nullable()->after('phone');
            $table->json('verified_channels')->nullable()->after('preferred_channel');
            $table->string('preferred_channel', 32)->default('email')->change();
            $table->text('admin_notes')->nullable()->after('address');
        });

        // 2. Perluas tabel disposisi untuk mendukung WhatsApp & pelacakan status per-kanal
        Schema::table('report_dispatches', function (Blueprint $table) {
            $table->string('channel', 32)->default('email')->change();
            $table->string('provider_message_id', 150)->nullable()->after('reference_id');
            $table->timestamp('delivered_at')->nullable()->after('sent_at');
            $table->timestamp('read_at')->nullable()->after('delivered_at');
            $table->unsignedInteger('retry_count')->default(0)->after('status');

            $table->index('provider_message_id');
        });

        // 3. Tabel log webhook WhatsApp resmi untuk menjamin idempoten dan audit
        Schema::create('whatsapp_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message_id', 150)->nullable()->index();
            $table->string('recipient_id', 50)->nullable();
            $table->string('event_status', 50); // sent, delivered, read, failed
            $table->string('idempotency_key', 200)->unique();
            $table->json('raw_payload')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_webhook_logs');

        Schema::table('report_dispatches', function (Blueprint $table) {
            $table->dropIndex(['provider_message_id']);
            $table->dropColumn(['provider_message_id', 'delivered_at', 'read_at', 'retry_count']);
        });

        Schema::table('report_recipients', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_number', 'verified_channels', 'admin_notes']);
        });
    }
};
