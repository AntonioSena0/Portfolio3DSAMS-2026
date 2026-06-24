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
            $table->enum('grade', ['1', '2', '3'])->nullable()->after('course_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('target_grade', ['1', '2', '3'])->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('target_grade');
        });
    }
};
