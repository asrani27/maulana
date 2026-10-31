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
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique()->index();
            $table->string('nik_kepala', 16);
            $table->string('nama_kepala_keluarga', 100);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('desa', 50)->default('Thaibah Raya');
            $table->string('kecamatan', 50)->default('Tatah Makmur');
            $table->string('kabupaten', 50)->default('Banjar');
            $table->string('provinsi', 50)->default('Kalimantan Selatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
