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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_details_id', 36);
            $table->string('fk_user_id', 36);
            $table->string('name', 200)->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('employee_code', 20)->nullable();
            $table->string('school_type', 20)->nullable();
            $table->string('teacher_type', 20)->nullable();
            $table->string('subject_type', 20)->nullable();

            $table->string('school_name', 200)->nullable();
            $table->string('udice_code', 200)->nullable();
            $table->string('school_address_vill', 100)->nullable();
            $table->string('school_address_district', 40)->nullable();
            $table->string('school_address_block', 30)->nullable();
            $table->string('school_address_state', 20)->nullable();
            $table->string('school_address_pin', 6)->nullable();

            $table->string('preferred_district_1', 100)->nullable();
            $table->string('preferred_district_2', 100)->nullable();
            $table->string('preferred_district_3', 100)->nullable();

            $table->timestamp('created_on')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('modified_on')->nullable();
            $table->string('modified_by')->nullable();
            $table->boolean('is_delete')->nullable()->default(false);
            $table->boolean('is_actively_looking')->nullable()->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
