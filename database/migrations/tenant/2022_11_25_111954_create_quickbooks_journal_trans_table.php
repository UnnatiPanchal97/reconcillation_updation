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
        Schema::create('quickbooks_journal_trans', function (Blueprint $table) {
            $table->integer('id')->length(11)->nullable(false)->autoIncrement();
            $table->integer('journal_master_id')->length(11)->nullable();
            $table->integer('amazon_account_id')->nullable()->comment('Primary column amazon transaction type');
            $table->integer('quickbook_account_id')->length(11)->nullable();
            $table->string('posting_type', 10)->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->integer('trans_month')->length(11)->nullable();
            $table->double('trans_amount', 12, 2)->nullable();
            $table->string('trans_description', 300)->nullable();
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
        Schema::dropIfExists('quickbooks_journal_trans');
    }
};
