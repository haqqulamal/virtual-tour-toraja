<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('panorama_image');
            $table->string('thumbnail')->nullable()->after('image_path');
            $table->boolean('is_active')->default(true)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'thumbnail', 'is_active']);
        });
    }
};