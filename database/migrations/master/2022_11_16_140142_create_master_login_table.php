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
        Schema::connection('master')->create('master_login', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('child_id')->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('email', 150);
            $table->string('password');
            $table->rememberToken();
            $table->string('phone', 20);
            $table->unsignedTinyInteger('user_role')->default('4')->comment('1=>SuperAdmin,2=> Admin,3=>Accountant,4=>User');
            $table->string('database_name', 100)->nullable();
            $table->string('database_username', 100)->nullable();
            $table->string('database_password', 100)->nullable();
            $table->unsignedTinyInteger('status')->default('1')->comment('1=>Active,0=> Inactive');
            $table->datetime('last_login_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable()->comment('system trials without card details');
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
        Schema::dropIfExists('master_login');
    }
};
