<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'school_name', 'value' => 'SMK NEGERI CONTOH SISARPA'],
            ['key' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Telp: (021) 1234567'],
            ['key' => 'school_website', 'value' => 'www.sisarpa.sch.id'],
            ['key' => 'school_email', 'value' => 'info@sisarpa.sch.id'],
            ['key' => 'school_logo', 'value' => 'default_logo.png'],
            ['key' => 'school_city', 'value' => 'KOTA ADMINISTRASI SEKOLAH'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
