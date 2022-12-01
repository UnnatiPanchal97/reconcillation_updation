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
        Schema::create('amazon_settlement_report_list', function (Blueprint $table) {
            $table->integer('id')->nullable(false)->length(11)->autoIncrement();
            $table->unsignedBigInteger('report_id')->nullable();
            $table->integer('store_id')->nullable(false);
            $table->integer('actual_store_id')->nullable();
            $table->string('marketplace_name',25)->nullable();
            $table->unsignedBigInteger('report_request_id')->nullable();
            $table->dateTime('report_availible_date')->nullable();
            $table->enum('processed',['0','1','2'])->default('0')->comment('0 for not process,1 for processed,2 for error');
            $table->enum('is_quickbook_processed',['0','1'])->default('0');
            $table->unsignedBigInteger('amazon_settlement_id')->nullable();
            $table->double('total_amount',12,2)->nullable();
            $table->double('total_amount_usd',12,2)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('deposit_date')->nullable();
            $table->double('currency_rate',12,10)->default('1.0000000000');
            $table->string('currency_code',5)->nullable();
            $table->string('settlement_file',100)->nullable();
            $table->enum('is_deleted',['0','1'])->default('0');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable(true)->useCurrent()->useCurrentOnUpdate();
            // $table->primary('id');
            $table->unique('report_id');
            $table->index('actual_store_id');
            $table->index('store_id');
            $table->index('amazon_settlement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_settlement_report_list');
    }
};
