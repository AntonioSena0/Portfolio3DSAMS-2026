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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('status', ['in_progress', 'completed', 'dropped'])->default('in_progress');
            $table->unsignedTinyInteger('progress_percent')->default(0)->comment('0 a 100');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->unique(['student_id', 'subject_id'], 'enrollments_student_subject_unique');
            $table->index('student_id', 'enrollments_student_id_index');
            $table->index('status', 'enrollments_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
