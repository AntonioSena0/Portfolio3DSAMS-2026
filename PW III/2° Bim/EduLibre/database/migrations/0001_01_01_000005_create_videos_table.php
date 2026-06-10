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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->string('url', 500)->comment('URL do vídeo (YouTube embed, Vimeo, etc)');
            $table->unsignedInteger('duration')->nullable()->comment('Duração em segundos');
            $table->smallInteger('order')->unsigned()->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->unique(['subject_id', 'slug'], 'videos_subject_slug_unique');
            $table->index('subject_id', 'videos_subject_id_index');
            $table->index('professor_id', 'videos_professor_id_index');
            $table->index('status', 'videos_status_index');
            $table->index(['subject_id', 'order'], 'videos_order_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};