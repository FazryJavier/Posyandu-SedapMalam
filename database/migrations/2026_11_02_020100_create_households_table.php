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
        // One row per family card (Kartu Keluarga). The head of the household is
        // the resident with relationship = "head"; members live in `residents`.
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('neighborhood_id')->constrained()->restrictOnDelete();
            $table->string('family_card_number', 16)->nullable()->unique();
            $table->text('address');
            $table->string('phone', 20)->nullable();

            // Household conditions that matter for child health and stunting prevention.
            $table->boolean('has_clean_water')->default(false);
            $table->boolean('has_sanitary_toilet')->default(false);
            $table->boolean('receives_social_assistance')->default(false);
            $table->string('health_insurance')->nullable()->comment('e.g. JKN-KIS, private, none');

            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('neighborhood_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
