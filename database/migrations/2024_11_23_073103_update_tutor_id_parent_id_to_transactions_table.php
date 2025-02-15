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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn('tutor_id');
            $table->dropColumn('parent_id');
            $table->unsignedBigInteger('tutor_id');
            $table->foreign('tutor_id')->references('user_id')->on('tutors');
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('user_id')->on('parents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('tutor_id');
            $table->dropColumn('parent_id');
            $table->dropForeign(['tutor_id']);
            $table->dropForeign(['parent_id']);
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->constrained()->onDelete('cascade');
        });
    }
};
