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
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->constrained()->onDelete('cascade');
            $table->string('service');
            $table->string('amount');
            $table->string('method');
            $table->integer('status')->default(1)->comment('0:declined,1:pending,2:completed');
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
            $table->dropColumn('service');
            $table->dropColumn('amount');
            $table->dropColumn('method');
            $table->dropColumn('status');
        });
    }
};
