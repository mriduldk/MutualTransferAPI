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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->string('wallet_id', 36);
            $table->string('fk_user_id', 36);
            $table->integer('total_amount');
            $table->datetime('expired_on')->nullable();
            
            
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
        Schema::dropIfExists('wallets');
    }
};
