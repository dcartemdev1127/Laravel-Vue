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
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->integer('person_id')->after('user_id');
            $table->integer('booking_id')->after('person_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->dropColumn('booking_id');
        });
    }
};
