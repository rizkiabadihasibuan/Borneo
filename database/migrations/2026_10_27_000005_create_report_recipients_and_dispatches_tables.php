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
        // 1. Tabel Direktori Penerima Laporan (Instansi Pemerintah & Kelompok MPA)
        Schema::create('report_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama resmi instansi / regu
            $table->enum('category', ['pemerintah', 'komunitas_mpa']); // Pemerintah atau MPA
            $table->string('agency_type'); // E.g. Manggala Agni (KLHK), BPBD, Dinas Kehutanan, MPA, Relawan
            $table->string('province'); // Wilayah kerja provinsi
            $table->string('regency')->nullable(); // Wilayah kerja kabupaten/kota
            $table->string('contact_person')->nullable(); // Nama narahubung / PJ piket
            $table->string('phone'); // Nomor telepon / kontak darurat
            $table->string('email')->nullable(); // Alamat email resmi
            $table->text('address')->nullable(); // Alamat kantor / deskripsi posko
            $table->enum('preferred_channel', ['email', 'manual'])->default('email');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'province', 'is_active']);
        });

        // 2. Tabel Disposisi dan Penerusan Laporan
        Schema::create('report_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('report_recipients')->restrictOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['email', 'manual'])->default('email');
            $table->text('dispatch_notes'); // Catatan arahan / instruksi admin
            $table->boolean('include_photo')->default(false); // Lampirkan foto jika aman
            $table->boolean('include_reporter_contact')->default(false); // Lampirkan kontak pelapor hanya jika diizinkan
            $table->string('status', 32)->default('Menunggu Pengiriman'); // Draf, Menunggu Pengiriman, Berhasil Dikirim, Gagal Dikirim
            $table->string('recipient_status', 32)->default('Menunggu Konfirmasi Penerima'); // Menunggu Konfirmasi Penerima, Dikonfirmasi Penerima, Sedang Ditindaklanjuti, Selesai Ditindaklanjuti
            $table->string('reference_id')->nullable(); // Nomor surat / ID referensi pengiriman
            $table->text('delivery_error')->nullable(); // Keterangan jika pengiriman gagal
            $table->text('recipient_feedback')->nullable(); // Catatan konfirmasi / respon dari penerima
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['report_id', 'recipient_id', 'status']);
        });

        // 3. Tabel Riwayat Perubahan Status Disposisi
        Schema::create('report_dispatch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_dispatch_id')->constrained('report_dispatches')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('previous_status', 32)->nullable();
            $table->string('new_status', 32);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['report_dispatch_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_dispatch_histories');
        Schema::dropIfExists('report_dispatches');
        Schema::dropIfExists('report_recipients');
    }
};
