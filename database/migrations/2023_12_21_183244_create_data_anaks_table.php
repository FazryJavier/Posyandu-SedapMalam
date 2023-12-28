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
        Schema::create('data_anaks', function (Blueprint $table) {
            $table->string('nik_anak')->primary();
            $table->string('nama_anak');
            $table->integer('umur');
            $table->integer('berat_badan')->nullable();
            $table->integer('tinggi_badan')->nullable();
            $table->integer('lingkar_kepala')->nullable();
            $table->integer('lingkar_lengan')->nullable();
            $table->integer('bmi')->nullable();
            $table->foreignId('IdOrangtua')->constrained('data_orangtuas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_anaks');
    }
};
