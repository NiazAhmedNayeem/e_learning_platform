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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('price')->nullable();
            $table->string('discount_price')->nullable();
            $table->text('prerequisite')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
