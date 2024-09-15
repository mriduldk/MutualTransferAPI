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
        Schema::table('user_details', function (Blueprint $table) {
            $table->boolean('is_mutually_transferred')->default(false);
            $table->string('transfer_remarks')->nullable();
            $table->boolean('amalgamation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('is_mutually_transferred');
            $table->dropColumn('transfer_remarks');
        });
    }
};
