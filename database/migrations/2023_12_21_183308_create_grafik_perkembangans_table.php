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
        Schema::create('grafik_perkembangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_anak');
            $table->integer('berat_badan')->nullable();
            $table->integer('tinggi_badan')->nullable();
            $table->integer('bmi')->nullable();
            $table->string('nik_anak')->nullable();
            $table->foreign('nik_anak')->references('nik_anak')->on('data_anaks')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grafik_perkembangans');
    }
};
