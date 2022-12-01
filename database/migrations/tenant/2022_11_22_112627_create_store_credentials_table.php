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
        Schema::create('store_credentials', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->nullable(false)->comment('Primary Key (Auto Increment)');
            $table->unsignedInteger('store_id')->nullable(false)->comment('id of store from the stores table');
            $table->char('merchant_id',14)->collation('utf8mb4_unicode_ci')->nullable()->comment('Seller Id - Merchant Id of the user to access the mws services of this marketplace');
            $table->string('mws_auth_token',500)->collation('utf8mb4_unicode_ci')->nullable()->comment('Seller-Developer Authorization Token');
            $table->string('instance_id',50)->collation('utf8mb4_unicode_ci')->nullable()->comment('Wix instance id');
            $table->string('refresh_token',500)->collation('utf8mb4_unicode_ci')->nullable()->comment('Seller-Developer Refresh Token');
            $table->string('access_token',500)->collation('utf8mb4_unicode_ci')->nullable()->comment('Seller-Developer Access Token');
            $table->string('mws_access_key_id')->collation('utf8mb4_unicode_ci')->nullable()->comment('MWS Access Id of the user to access the mws services of this marketplace');
            $table->string('mws_secret_key',100)->collation('utf8mb4_unicode_ci')->nullable()->comment('Secret key of the user to access the mws services of this marketplace');
            $table->string('aws_access_key_id',50)->collation('utf8mb4_unicode_ci')->nullable()->comment('AWSAccessKeyId of the user to access the aws services of this marketplace');
            $table->string('aws_secret_key',100)->collation('utf8mb4_unicode_ci')->nullable()->comment('AWS Secret Key of the user to access the aws services of this marketplace');
            $table->string('amazon_aws_region',25)->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('ads_refresh_token')->collation('utf8mb4_unicode_ci');
            $table->text('ads_access_token')->collation('utf8mb4_unicode_ci');
            $table->string('ads_client_id',128)->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('ads_client_secret_key')->collation('utf8mb4_unicode_ci');
            $table->string('sqs_query_url',250)->collation('utf8mb4_unicode_ci')->nullable()->comment('Amazon SQS Query URL to fetch notifications');
            $table->enum('is_fetch_order',['1','0'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('1')->comment('fetch order for specific stores');
            $table->dateTime('order_fetching_start_date')->nullable()->comment('Date & time since when we should fetch the orders from the marketplace');
            $table->dateTime('return_order_fetch_date')->nullable()->comment('Date & time since when we should fetch the orders from the marketplace');
            $table->dateTime('seller_shipment_start_date')->nullable()->comment('Date & time since when we should fetch the shipments from the marketplace');
            $table->enum('is_return_order_fetched',['0','1'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('0')->comment('0 = no , 1 = yes');
            $table->enum('is_production',['0','1'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('1')->comment('0 = Sandbox or testing environment, 1 = production environment');
            $table->unsignedInteger('created_by')->nullable(false)->comment('user_id who inserted this record');
            $table->unsignedInteger('updated_by')->nullable(false)->comment('user_id who last modified this record');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_credentials');
    }
};
