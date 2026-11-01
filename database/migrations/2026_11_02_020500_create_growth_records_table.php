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
        // Junction between posyandu sessions and children: one measurement per
        // child per session, so monthly development can be charted over time.
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->date('measured_on');
            $table->unsignedSmallInteger('age_in_months');

            $table->decimal('weight_kg', 5, 2);
            $table->decimal('height_cm', 5, 1)->nullable()->comment('length (lying) or height (standing)');
            $table->decimal('head_circumference_cm', 4, 1)->nullable();
            $table->decimal('arm_circumference_cm', 4, 1)->nullable();

            // Derived values, filled by the application when a record is saved.
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('nutritional_status', 30)->nullable();

            $table->text('notes')->nullable();
            $table->foreignId('measured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['posyandu_session_id', 'resident_id']);
            $table->index(['resident_id', 'measured_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_records');
    }
};
