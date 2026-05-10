<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scene_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->float('pitch'); // vertical angle
            $table->float('yaw');   // horizontal angle
            $table->enum('type', ['info', 'navigation'])->default('info');
            $table->foreignId('linked_scene_id')->nullable()->constrained('scenes')->nullOnDelete();
            $table->foreignId('artifact_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotspots');
    }
};
