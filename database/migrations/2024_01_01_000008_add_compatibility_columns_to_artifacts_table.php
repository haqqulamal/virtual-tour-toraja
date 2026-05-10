<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artifacts', function (Blueprint $table) {
            $table->string('title_id')->nullable()->after('category_id');
            $table->string('title_en')->nullable()->after('title_id');
            $table->text('description_id')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_id');
            $table->string('image_path')->nullable()->after('image');
            $table->string('material')->nullable()->after('image_path');
            $table->boolean('is_featured')->default(true)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('artifacts', function (Blueprint $table) {
            $table->dropColumn([
                'title_id',
                'title_en',
                'description_id',
                'description_en',
                'image_path',
                'material',
                'is_featured',
            ]);
        });
    }
};