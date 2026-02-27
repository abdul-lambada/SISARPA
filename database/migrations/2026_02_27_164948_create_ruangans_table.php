<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->integer('kapasitas');
            $table->text('fasilitas')->nullable();
            $table->string('foto_ruangan')->nullable();
            $table->enum('status', ['tersedia', 'perbaikan', 'tidak_tersedia'])->default('tersedia');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
