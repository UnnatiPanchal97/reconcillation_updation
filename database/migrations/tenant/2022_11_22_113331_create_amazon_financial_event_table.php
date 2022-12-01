<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_financial_event', function (Blueprint $table) {
            
            $table->unsignedBigInteger('id')->nullable(false)->autoIncrement();
            $table->integer('store_id')->nullable(false);
            $table->string('event_group_id',50)->nullable();
            $table->enum('processing_status',['open','closed'])->nullable();
            $table->string('fund_transfer_status',50)->nullable();
            $table->decimal('original_total_amount',8,2)->nullable();
            $table->char('origin_amount_currency_code',3)->nullable();
            $table->decimal('converted_total_amount',8,2)->nullable();
            $table->char('converted_amount_currency_code',3)->nullable();
            $table->decimal('beginning_balance',8,2)->nullable();
            $table->char('beginning_balance_currency_code',3)->nullable();
            $table->dateTime('fund_transfer_date')->nullable();
            $table->dateTime('financial_event_start_date')->nullable();
            $table->dateTime('financial_event_end_date')->nullable();
            $table->string('trace_id',100)->nullable();
            $table->string('account_tail',20)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable(false)->useCurrent()->useCurrentOnUpdate();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_financial_event');
    }
};
