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
        Schema::create('wallet_offers', function (Blueprint $table) {
            $table->id();

            $table->string('wallet_offer_id', 36);
            $table->integer('total_amount');
            $table->integer('total_coin');
            $table->integer('discount');
            $table->string('message')->nullable();
            $table->boolean('is_new')->default(false);
            
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
        Schema::dropIfExists('wallet_offers');
    }
};
