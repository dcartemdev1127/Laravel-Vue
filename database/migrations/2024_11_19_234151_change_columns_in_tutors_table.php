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
        Schema::table('tutors', function (Blueprint $table) {
            $table->string('hourlyrate')->nullable()->change();
            $table->string('dailyrate')->nullable()->change();
            $table->string('termlyrate')->nullable()->change();
            $table->string('annualrate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->string('hourlyrate');
            $table->string('dailyrate');
            $table->string('termlyrate');
            $table->string('annualrate');
        });
    }
};
