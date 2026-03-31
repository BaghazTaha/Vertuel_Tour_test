// database/migrations/xxxx_create_hotspots_table.php
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
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('target_scene_id')->nullable()->constrained('spaces')->nullOnDelete();
            $table->enum('type', ['employee', 'scene']);
            $table->string('label')->nullable();
            $table->float('pitch', 8, 5)->default(0);
            $table->float('yaw', 8, 5)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotspots');
    }
};