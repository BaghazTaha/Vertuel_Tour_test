<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE hotspots MODIFY COLUMN type ENUM('employee', 'scene', 'trainer', 'schedule') NOT NULL");

        Schema::table('hotspots', function (Blueprint $table) {
            $table->foreignId('trainer_id')->nullable()->after('target_scene_id')->constrained('trainers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->dropColumn('trainer_id');
        });
        
        // This down could fail if the table already contains new types, but standard Laravel rollback handles basic cases.
        DB::statement("ALTER TABLE hotspots MODIFY COLUMN type ENUM('employee', 'scene') NOT NULL");
    }
};
