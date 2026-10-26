<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 360 tour: scenes (panoramas) and the arrows that link them together.
    public function up(): void
    {
        Schema::create('tour_scenes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            // Uploaded 360 picture (equirectangular); null for the tiled scenes from the Marzipano Tool.
            $table->string('image_path')->nullable();
            $table->unsignedInteger('image_width')->nullable();
            // Tile settings for the legacy Marzipano scenes.
            $table->json('levels')->nullable();
            $table->unsignedInteger('face_size')->nullable();
            $table->double('initial_yaw')->default(0);
            $table->double('initial_pitch')->default(0);
            $table->double('initial_fov')->default(1.5707963267948966);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tour_hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_scene_id')->constrained('tour_scenes')->cascadeOnDelete();
            $table->foreignId('target_scene_id')->constrained('tour_scenes')->cascadeOnDelete();
            $table->double('yaw');
            $table->double('pitch');
            $table->double('rotation')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_hotspots');
        Schema::dropIfExists('tour_scenes');
    }
};
