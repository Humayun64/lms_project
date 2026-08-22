<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove old constraints so certificates can support offline (no account) recipients
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'course_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('registration_id')->nullable()->after('course_id')
                  ->constrained('offline_registrations')->nullOnDelete();
            $table->string('recipient_name')->nullable()->after('registration_id');
            $table->enum('source', ['online', 'offline'])->default('online')->after('recipient_name');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['registration_id']);
            $table->dropColumn(['registration_id', 'recipient_name', 'source']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
