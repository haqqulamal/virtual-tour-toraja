<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->text('content')->nullable()->after('description');
            $table->string('image_path')->nullable()->after('content');
            $table->foreignId('target_scene_id')->nullable()->after('linked_scene_id')->constrained('scenes')->nullOnDelete();
        });

        DB::statement("ALTER TABLE hotspots MODIFY type ENUM('info', 'scene', 'artifact') NOT NULL DEFAULT 'info'");
    }

    public function down(): void
    {
        // Convert any 'artifact' type hotspots back to 'info' before modifying enum
        DB::statement("UPDATE hotspots SET type = 'info' WHERE type = 'artifact'");
        
        DB::statement("ALTER TABLE hotspots MODIFY type ENUM('info', 'scene') NOT NULL DEFAULT 'info'");

        Schema::table('hotspots', function (Blueprint $table) {
            $table->dropConstrainedForeignId('target_scene_id');
            $table->dropColumn(['content', 'image_path']);
        });
    }
};