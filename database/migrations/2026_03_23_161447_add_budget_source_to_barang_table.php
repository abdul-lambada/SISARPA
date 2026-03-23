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
        Schema::table('barang', function (Blueprint $table) {
            $table->string('sumber_dana')->nullable()->after('satuan'); // BOS, BOSDA, KOMITE, dll.
            $table->decimal('harga_perolehan', 15, 2)->default(0)->after('sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['sumber_dana', 'harga_perolehan']);
        });
    }
};
