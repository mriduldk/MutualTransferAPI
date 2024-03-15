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
        Schema::create('expenses_contacts', function (Blueprint $table) {
            $table->id();

            $table->string('contact_id')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->boolean('contact_paidBy')->default(false);
            $table->boolean('contact_excludedFromEqualShare')->default(false);
            $table->double('contact_paidAmount')->default(0.0);
            $table->double('contact_equalShare')->default(0.0);
            $table->double('contact_extraShare')->default(0.0);
            $table->double('contact_totalShare')->default(0.0);
            $table->double('contact_amount_get')->default(0.0);
            $table->double('contact_amount_give')->default(0.0);
            $table->string('contact_amount_get_from')->nullable();
            $table->string('amount_give_to')->nullable();
            
            $table->string('fk_expenses_id')->nullable();

            $table->string('contact_created_by')->nullable();
            $table->datetime('contact_created_on')->nullable();
            $table->string('contact_modified_by')->nullable();
            $table->datetime('contact_modified_on')->nullable();
            $table->string('contact_deleted_by')->nullable();
            $table->datetime('contact_deleted_on')->nullable();
            $table->boolean('contact_is_deleted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_contacts');
    }
};
