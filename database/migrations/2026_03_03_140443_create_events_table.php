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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('drone_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('zone_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->string('event_type');
            $table->string('snapshot_path')->nullable();
            $table->string('video_path')->nullable();
            
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
