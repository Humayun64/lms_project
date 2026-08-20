<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->enum('type', ['video', 'text', 'pdf', 'quiz'])->default('video');

            // For video lessons: either a link (main way) or an uploaded file
            $table->enum('video_source', ['link', 'upload'])->nullable();
            $table->string('video_url')->nullable();   // YouTube/Vimeo link
            $table->string('video_file')->nullable();  // uploaded mp4 path

            // For text lessons
            $table->longText('content')->nullable();

            // For pdf/file lessons
            $table->string('file_path')->nullable();

            $table->string('duration')->nullable();    // e.g. "12:30"
            $table->boolean('is_preview')->default(false); // free preview before buying
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
