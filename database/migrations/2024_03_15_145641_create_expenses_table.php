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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expenses_id')->nullable();
            $table->string('expenses_amount')->nullable();
            $table->string('expenses_description')->nullable();
            $table->datetime('expenses_date_time')->nullable();
            $table->string('expenses_date')->nullable();
            $table->string('expenses_time')->nullable();
            $table->string('expenses_group_id')->nullable();
            $table->string('expenses_paidBy')->nullable();
            $table->string('expenses_paidById')->nullable();
            $table->string('expenses_splitType')->nullable();
            $table->boolean('expenses_is_deleted')->default(false);
            $table->boolean('expenses_is_paid')->default(false);
            $table->datetime('expenses_paid_date_time')->nullable();

            $table->string('expenses_created_by')->nullable();
            $table->datetime('expenses_created_on')->nullable();
            $table->string('expenses_modified_by')->nullable();
            $table->datetime('expenses_modified_on')->nullable();
            $table->string('expenses_deleted_by')->nullable();
            $table->datetime('expenses_deleted_on')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
