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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title');
            $table->text('video_link');
            $table->boolean('is_demo')->default(0);
            $table->integer('position')->default(1);
            $table->string('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_videos');
    }
};
