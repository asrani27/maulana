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
        Schema::create('hasil_saw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->foreignId('keluarga_id')->constrained('keluarga')->onDelete('cascade');
            $table->decimal('nilai_akhir', 6, 4);
            $table->integer('ranking');
            $table->enum('status_kelayakan', ['Layak', 'Tidak Layak']);
            $table->timestamps();

            $table->unique(['periode_id', 'keluarga_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_saw');
    }
};
