<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drone_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 64)->default('person_detected');
            $table->string('message');
            $table->decimal('confidence', 5, 4)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index('drone_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};