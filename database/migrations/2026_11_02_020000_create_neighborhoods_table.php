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
        // RT/RW service areas. Households are registered per RT.
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->string('rw', 3);
            $table->string('rt', 3);
            $table->string('head_name')->nullable()->comment('Ketua RT');
            $table->string('phone', 20)->nullable();
            $table->timestamps();

            $table->unique(['rw', 'rt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neighborhoods');
    }
};
