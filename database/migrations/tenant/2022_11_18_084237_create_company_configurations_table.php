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
        Schema::create('company_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name', 255);
            $table->string('trading_name', 100)->nullable();
            $table->string('branch_or_division', 100)->nullable();
            $table->string('taxation_number', 50)->nullable();
            $table->tinyInteger('tax_percentage')->nullable();
            $table->string('email',150);
            $table->string('telephone',30)->nullable();
            $table->string('mobile',30)->nullable();
            $table->string('website',150)->nullable();
            $table->string('logo',255)->nullable();
            $table->string('default_branding_name',100)->nullable();
            $table->text('contact_details')->nullable();
            $table->string('contact_number',20)->nullable();
            $table->tinyInteger('delivery_address')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('billing_address')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('delivery_deadline')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('digital_sign')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('shipping')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('file_attachments')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('auto_generate_order_no')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('order_cancellation')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('payment_before_production')->default('1')->comment('1=>on,0=>off')->nullable();
            $table->tinyInteger('approval_notification')->default('0')->comment('1=>on,0=>off')->nullable();
            $table->string('approval_notify_to',150)->nullable()->comment('order approve notification send to this email');
            $table->tinyInteger('finance_notification')->default('0')->comment('1=>on,0=>off')->nullable();
            $table->string('finance_notify_to',150)->nullable()->comment('finance notification send to this email');
            $table->tinyInteger('cancellation_notification')->default('0')->comment('1=>on,0=>off')->nullable();
            $table->string('cancellation_notify_to',150)->nullable()->comment('order cancel notification send to this email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_configurations');
    }
};
