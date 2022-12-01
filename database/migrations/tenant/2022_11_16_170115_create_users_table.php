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
        Schema::connection('tenant')->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedTinyInteger('user_role')->default('2')->comment('2=>Admin,3=> Accountant,4=>User');
            $table->unsignedTinyInteger('status')->default('1')->comment('1=>Active,0=>Inactive');
            $table->unsignedTinyInteger('can_deletable')->default('0')->comment('on the basis of current order|1=>Yes,0=>No');
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
        Schema::connection('tenant')->dropIfExists('users');
    }
};
