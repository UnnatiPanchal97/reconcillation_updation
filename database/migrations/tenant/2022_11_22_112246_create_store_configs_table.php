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
        Schema::create('store_configs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->nullable(false)->comment('Primary Key (Auto Increment)');
            $table->enum('store_marketplace',['Amazon'])->collation('utf8mb4_unicode_ci')->nullable(false)->comment('Main marketplace of the store');
            $table->enum('store_type',['Amazon US','Amazon CA','Amazon UK','Amazon ES','Amazon FR','Amazon DE','Amazon IT','Amazon JP','Amazon CN','Amazon IN','Amazon MX','Amazon AUS'])->collation('utf8mb4_unicode_ci')->nullable(false)->comment('Type of the store');
            $table->enum('store_country',['US','UK','AU','CA'])->collation('utf8mb4_unicode_ci')->nullable(false)->comment('Store country code');
            $table->string('store_url',30)->collation('utf8mb4_unicode_ci')->nullable(false)->comment('Main URL of the Marketplace');
            $table->char('amazon_marketplace_id',14)->collation('utf8mb4_unicode_ci')->nullable()->comment('MarketplaceId of respective Amazon Marketplace');
            $table->char('amazon_seller_id',14)->collation('utf8mb4_unicode_ci')->nullable()->comment('Seller id of Amazon itself on respective marketplace');
            $table->string('amazon_ecs_region',10)->collation('utf8mb4_unicode_ci')->nullable()->comment('This is used to indentify the region/location to call for product advertisement api');
            $table->string('amazon_aws_region',20)->collation('utf8mb4_unicode_ci')->nullable()->comment('Aws Region we will use the the SQS service for this marketplace');
            $table->string('amazon_mws_application_name',20)->collation('utf8mb4_unicode_ci')->nullable()->comment('All MWS requests must contain a User-Agent header. The application name and version defined are used in creating this value');
            $table->string('amazon_mws_application_version',10)->collation('utf8mb4_unicode_ci')->nullable()->comment('All MWS requests must contain a User-Agent header. The application * name and version defined below are used in creating this value.');
            $table->string('amazon_service_url_order',100)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('amazon_service_url_product',100)->collation('utf8mb4_unicode_ci')->nullable()->comment('You access Amazon Marketplace Web Service (Amazon MWS) Product through a URL endpoint for your Amazon marketplace');
            $table->string('amazon_service_url_report',100)->collation('utf8mb4_unicode_ci')->nullable()->comment('You access Amazon Marketplace Web Service (Amazon MWS) Report through a URL endpoint for your Amazon marketplace');
            $table->string('amazon_service_url_subscription',100)->collation('utf8mb4_unicode_ci')->nullable()->comment('You access Amazon Marketplace Web Service (Amazon MWS) Subscription through a URL endpoint for your Amazon marketplace');
            $table->string('aws_endpoint',150)->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->char('store_currency',3)->collation('utf8mb4_unicode_ci')->nullable(false)->comment('Each eBay site maps to a unique eBay global ID.');
            $table->enum('aws_region',['North America','Europe','Far East'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('North America')->comment('Main marketplace of the store');
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
        Schema::dropIfExists('store_configs');
    }
};
