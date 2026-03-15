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
        Schema::table('users', function (Blueprint $table) {
            $table->string('reset_token_hash', 64)->nullable()->unique()->after('password');
            $table->dateTime('reset_token_expires_at')->nullable()->after('reset_token_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['reset_token_hash']);
            $table->dropColumn(['reset_token_hash', 'reset_token_expires_at']);
        });
    }
};
