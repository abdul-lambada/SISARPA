<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('activity');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('properties')->nullable(); // Untuk menyimpan data lama vs data baru
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // Migration untuk Maintenance Reminder
        Schema::table('barang', function (Blueprint $table) {
            $table->date('tgl_servis_berikutnya')->nullable()->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('tgl_servis_berikutnya');
        });
    }
};
