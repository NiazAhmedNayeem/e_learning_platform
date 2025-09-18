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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('target_role', ['all','admin','teacher','student']);
            $table->unsignedBigInteger('target_course_id')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->json('attachments')->nullable(); 
            $table->enum('status', ['active','inactive'])->default('active');
            $table->string('image')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
