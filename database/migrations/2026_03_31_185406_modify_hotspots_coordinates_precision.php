<?php
// database/migrations/2026_03_31_185406_modify_hotspots_coordinates_precision.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->decimal('pitch', 12, 6)->change();
            $table->decimal('yaw', 12, 6)->change();
        });
    }

    public function down(): void
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->float('pitch', 8, 5)->change();
            $table->float('yaw', 8, 5)->change();
        });
    }
};
