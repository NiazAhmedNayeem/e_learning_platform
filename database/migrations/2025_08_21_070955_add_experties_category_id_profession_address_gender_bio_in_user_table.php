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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('expertise_category_id')->nullable()->after('image');
            $table->string('profession')->nullable()->after('expertise_category_id');
            $table->enum('gender', ['male','female','other'])->nullable()->after('profession');
            $table->text('bio')->nullable()->after('gender');
            $table->string('address')->nullable()->after('bio');

            // foreign key
            $table->foreign('expertise_category_id')
                  ->references('id')->on('categories')
                  ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['expertise_category_id']);
            $table->dropColumn([
                'expertise_category_id',
                'profession',
                'gender',
                'bio',
                'address'
            ]);
        });
    }
};
