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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_id')->nullable();
            $table->string('group_name')->nullable();
            $table->string('group_icon_url')->nullable();
            $table->string('group_color')->nullable();
            $table->string('group_created_date_time_String')->nullable();
            $table->bigInteger('group_created_date_time_Long')->nullable();
            $table->string('group_description')->nullable();
            $table->boolean('group_is_deleted')->default(false);
            $table->boolean('group_is_pinned')->default(false);
            $table->boolean('group_is_important')->default(false);
            $table->integer('total_item_available')->default(0);


            $table->string('group_created_by')->nullable();
            $table->string('group_created_on')->nullable();
            $table->string('group_modified_by')->nullable();
            $table->string('group_modified_on')->nullable();
            $table->string('group_deleted_by')->nullable();
            $table->string('group_deleted_on')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
