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
        Schema::create('amazon_settlement_report_trans', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->autoIncrement();
            $table->string('amazon_order_id',250)->nullable();
            $table->unsignedBigInteger('settlement_report_id')->nullable(false);
            $table->string('merchant_order_item_id',50)->nullable();
            $table->integer('amazon_transaction_type_id')->nullable()->length(11);
            $table->string('transaction_type',50)->nullable();
            $table->string('merchant_order_id',30)->nullable();
            $table->string('adjustment_id',50)->nullable();
            $table->string('shipment_id',20)->nullable();
            $table->string('marketplace_name',20)->nullable();
            $table->string('amount_type',255)->nullable();
            $table->string('amount_description',255)->nullable();
            $table->double('amount',12,2)->nullable();
            $table->char('fulfillment_id',3)->nullable();
            $table->string('amazon_order_Item_code',250)->nullable();
            $table->integer('merchant_adjustment_item_id')->nullable();
            $table->dateTime('posted_date_time')->nullable();
            $table->string('promotion_id',150)->nullable();
            $table->string('sku',100)->nullable();
            $table->integer('quantity_purchased')->nullable()->length(10);
            $table->enum('updated',['0','1'])->nullable(false)->default('0');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable(false)->useCurrent()->useCurrentOnUpdate();
            $table->index('amazon_order_id');
            $table->index('settlement_report_id');
            $table->index('updated');
            $table->index('posted_date_time');
            $table->index('amazon_transaction_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_settlement_report_trans');
    }
};
