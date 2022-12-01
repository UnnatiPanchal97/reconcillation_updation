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
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->nullable(false)->comment('Primary Key (Auto Increment)');
            $table->integer('parent_store_id')->nullable()->comment('Parent store id for copy reference');
            $table->integer('user_id')->nullable()->comment('id of store_master as child store');
            $table->string('store_name')->nullable(false)->collation('utf8mb4_unicode_ci')->comment('Name of the marketplace store');
            $table->enum('store_marketplace',['Amazon'])->nullable(false)->collation('utf8mb4_unicode_ci')->comment('Main marketplace of the store');
            $table->enum('store_type', ['Amazon US', 'Amazon CA', 'Amazon UK', 'Amazon ES', 'Amazon FR', 'Amazon DE', 'Amazon IT', 'Amazon JP', 'Amazon CN', 'Amazon IN', 'Amazon MX', 'Amazon AUS'])->nullable()->collation('utf8mb4_unicode_ci')->comment('Type of the store');
            $table->integer('store_config_id')->nullable(false)->default('0');
            $table->enum('is_sqs_registered',['1','0'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('0');
            $table->string('currency_code')->collation('utf8mb4_unicode_ci')->nullable();
            $table->enum('is_active',['1','0'])->collation('utf8mb4_unicode_ci')->nullable(false)->default('1')->comment('1 - Account is active, 0 - account is deactive');
            $table->string('amazon_advertising_region')->collation('utf8mb4_unicode_ci')->nullable()->comment('Region');
            $table->tinyInteger('is_master_store')->nullable(false)->default('0')->comment('is_master_store = user first store');
            $table->integer('max_quantity_post')->nullable(false)->default('0')->comment('max qty post');
            $table->unsignedInteger('created_by')->nullable(false)->comment('user_id who inserted this record');
            $table->unsignedInteger('updated_by')->nullable(false)->comment('user_id who last modified this record');
            $table->softDeletes();
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
        Schema::dropIfExists('stores');
    }
};
