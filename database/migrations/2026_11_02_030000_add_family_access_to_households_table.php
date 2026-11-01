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
        // Secret link that lets a family view its children's growth without logging in.
        Schema::table('households', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->unique()->after('notes');
            $table->timestamp('access_token_generated_at')->nullable()->after('access_token');
            $table->timestamp('last_viewed_at')->nullable()->after('access_token_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropUnique(['access_token']);
            $table->dropColumn(['access_token', 'access_token_generated_at', 'last_viewed_at']);
        });
    }
};
