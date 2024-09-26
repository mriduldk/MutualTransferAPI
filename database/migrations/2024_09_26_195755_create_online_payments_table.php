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
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();

            $table->string('online_payment_id')->unique();
            $table->string('fk_user_id');
            $table->string('payment_date')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_status')->nullable();

            $table->string('amount_refunded')->nullable();
            $table->string('refund_status')->nullable();
            $table->string('captured')->nullable();
            $table->string('card_id')->nullable();
            $table->string('bank')->nullable();
            $table->string('wallet')->nullable();
            $table->string('vpa')->nullable();
            $table->string('fee')->nullable();
            $table->string('tax')->nullable();

            $table->string('order_id')->nullable();
            $table->string('entity')->nullable();
            $table->string('coins')->nullable();
            $table->string('amount')->nullable();
            $table->string('amount_paid')->nullable();
            $table->string('amount_due')->nullable();
            $table->string('currency')->nullable();
            $table->string('receipt')->nullable();
            $table->string('offer_id')->nullable();
            $table->string('status')->nullable();
            $table->string('attempts')->nullable();
            $table->string('notes')->nullable();
            $table->string('order_created_at')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();

            $table->string('error_code')->nullable();
            $table->string('error_description')->nullable();
            $table->string('error_source')->nullable();
            $table->string('error_step')->nullable();
            $table->string('error_reason')->nullable();
            $table->string('error_field')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payments');
    }
};
