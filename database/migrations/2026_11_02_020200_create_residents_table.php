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
        // Every person living in a household: head, spouse, children, parents,
        // relatives. Children are tracked at the posyandu through growth_records.
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->string('nik', 16)->nullable()->unique();
            $table->string('name');
            $table->string('gender', 10)->comment('male|female');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('relationship', 20)->default('other')->comment('head|spouse|child|parent|sibling|other');

            // Optional parent links, mainly to connect children to their mother and father.
            $table->foreignId('father_id')->nullable()->constrained('residents')->nullOnDelete();
            $table->foreignId('mother_id')->nullable()->constrained('residents')->nullOnDelete();

            $table->string('occupation')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('health_insurance_number')->nullable();
            $table->string('status', 20)->default('active')->comment('active|moved|deceased');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['household_id', 'relationship']);
            $table->index('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
