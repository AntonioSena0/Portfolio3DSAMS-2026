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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->string('cover', 255)->nullable()->comment('Path do arquivo de capa');
            $table->enum('status', ['draft', 'under_review', 'published', 'archived'])->default('draft');
            $table->text('rejection_reason')->nullable()->comment('Preenchido pelo admin ao rejeitar');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->index('professor_id', 'subjects_professor_id_index');
            $table->index('category_id', 'subjects_category_id_index');
            $table->index('status', 'subjects_status_index');
            $table->index('slug', 'subjects_slug_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};