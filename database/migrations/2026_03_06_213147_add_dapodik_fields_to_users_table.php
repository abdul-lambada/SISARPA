<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('id'); // NISN / NIP
            $table->string('no_induk')->nullable()->after('username');    // Additional No Induk
            $table->enum('jenis_user', ['guru', 'siswa', 'staf'])->default('staf')->after('name');
            $table->string('kelas')->nullable()->after('jenis_user');     // For students
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'no_induk', 'jenis_user', 'kelas']);
        });
    }
};
