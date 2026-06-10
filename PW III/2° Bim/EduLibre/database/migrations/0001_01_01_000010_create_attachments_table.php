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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->nullable();
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade')->nullable();
            $table->string('name', 255);
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->unsignedInteger('size')->comment('Tamanho em bytes');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->index('subject_id', 'attachments_subject_id_index');
            $table->index('video_id', 'attachments_video_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
