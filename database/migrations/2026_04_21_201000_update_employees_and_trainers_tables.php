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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('sex', ['male', 'female'])->nullable()->after('name');
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->enum('sex', ['male', 'female'])->nullable()->after('last_name');
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sex');
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['sex', 'user_id']);
        });
    }
};
