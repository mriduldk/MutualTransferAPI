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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();

            $table->string('district_id', 36);
            $table->string('district_name', 200);
            $table->string('state_name', 100)->nullable();
            $table->string('state_id', 36)->nullable();
            
            $table->timestamp('created_on')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('modified_on')->nullable();
            $table->string('modified_by')->nullable();
            $table->boolean('is_delete')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
