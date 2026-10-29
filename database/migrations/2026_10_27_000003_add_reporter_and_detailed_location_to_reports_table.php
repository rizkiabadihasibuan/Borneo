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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('reporter_name', 100)->after('access_code_hash');
            $table->string('reporter_phone', 50)->after('reporter_name');
            $table->string('province', 100)->after('reporter_phone');
            $table->string('regency', 100)->after('province');
            $table->string('district', 100)->nullable()->after('regency');
            $table->string('village', 100)->nullable()->after('district');
            $table->string('incident_type', 50)->default('Kebakaran Lahan Gambut')->after('incident_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'reporter_name',
                'reporter_phone',
                'province',
                'regency',
                'district',
                'village',
                'incident_type',
            ]);
        });
    }
};
