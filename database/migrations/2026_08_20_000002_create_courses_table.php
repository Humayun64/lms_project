<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Which School (category) this course belongs to
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // Instructor (a user with role=instructor). No FK constraint yet
            // since the instructor module isn't built — just a nullable id.
            $table->unsignedBigInteger('instructor_id')->nullable();

            $table->string('title');
            $table->string('slug')->unique();

            // The flexible switch: each course is online OR offline
            $table->enum('type', ['online', 'offline'])->default('offline');

            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->string('language')->nullable();
            $table->string('duration')->nullable();     // e.g. "3-4 Months"
            $table->string('thumbnail')->nullable();     // image path

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('outcome')->nullable();          // career paths
            $table->text('final_project')->nullable();

            // Price is optional for now — you'll set it later
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();

            $table->boolean('certificate')->default(true);
            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
