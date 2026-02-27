<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('tipe', ['aset', 'bhp'])->default('aset')->after('kategori_id');
            $table->string('satuan')->nullable()->after('nama_barang'); // e.g., Pcs, Rim, Box
            $table->integer('min_stok')->default(0)->after('stok'); // for alert
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'satuan', 'min_stok']);
        });
    }
};
