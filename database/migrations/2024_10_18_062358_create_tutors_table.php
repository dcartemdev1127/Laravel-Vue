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
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('expertise');
            $table->string('availability');
            $table->string('hourlyrate');
            $table->string('dailyrate');
            $table->string('termlyrate');
            $table->string('annualrate');
            $table->string('willingTravel');
            $table->string('willingLocation');
            $table->string('languages');
            $table->longText('qualification')->nullable();
            $table->string('document')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
