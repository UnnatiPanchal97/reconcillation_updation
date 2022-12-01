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
        Schema::create('quickbooks_journal_master', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->length(11)->nullable(false);
            $table->integer('store_id')->length(10)->nullable();
            $table->unsignedBigInteger('amazon_settlement_id')->length(20)->nullable();
            $table->bigInteger('journal_entry_id')->nullable()->comment('Unique Id from quickbook');
            $table->string('jourrnal_name', 55)->nullable();
            $table->date('journal_from_date')->nullable();
            $table->date('journal_to_date')->nullable();
            $table->string('currency_code', 5)->nullable();
            $table->string('private_note', 100)->nullable();
            $table->double('total_amount', 12, 2)->nullable();
            $table->dateTime('journal_entry_date')->nullable();
            $table->enum('is_processed', ['0', '1'])->default('0')->nullable();
            $table->enum('is_deleted', ['0', '1'])->nullable()->default('0');
            $table->timestamp('created_at')->nullable(false)->useCurrent();
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
        Schema::dropIfExists('quickbooks_journal_master');
    }
};
