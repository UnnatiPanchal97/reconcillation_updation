<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\AmazonSettlementReportList;
use App\Models\AmazonSettlementReportTrans;
use App\Models\FinancialEventGroup;
use App\Models\Store;
use App\Models\StoreCredentials;
use App\Models\MasterLogin;
use App\Models\AmazonTransactionType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;
use App\Traits\ConsoleAuthentication;
use Illuminate\Support\Facades\Log;
use App\Helpers\SetConnection;
use Validator;

class ProcessAmazonTransactionType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processamazon:transactiontype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all amazon transaction type ids';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        //getting new company users...
        $companies = MasterLogin::getAllCompaniesDBDetails();
        if ($companies->count() > 0) {
            foreach ($companies as $company) {
                if(isset($company->database_name) && !empty($company->database_name) && isset($company->database_username) && !empty($company->database_username) && isset($company->database_password) && !empty($company->database_password)){
                    //set company users database connection
                    SetConnection::set_tenant_connection($company->database_name, $company->database_username, $company->database_password);
                    $stores= Store::where('store_marketplace',config('params.market_place.amazon'))->where('is_active',1)->get();
                    if ($stores->count() > 0) {

                        //--- Insert data in amazon_transaction_type table
                        $this->getOrderSettlementData();

                        //--- update amazon transaction type ids in amazon_settlement_report_trans table
                        $this->updateAmazonOrderSettlementData();

                        //---Update Currency Conversion rate...
                        $this->updateCurrencyConversationRate();
                    }
                }
            }
        }
    }

    /*
    @Description : Get All amazon order settelement data
    @Author      : Nirbhay Sharma
    @Output      : Insert data in amazon_transaction_type table
    @Date        : 24-11-2022
    */

    public function getOrderSettlementData()
    {
        $insert_trans_type = array();

        // Get amazon_order_settlement_trans table data
        $amazon_settlement_data = AmazonSettlementReportTrans::getAmazonSettelementData();
        $trans_type_data = $this->getArrayColumn($amazon_settlement_data, null, 'type');
       
        // Get amazon_transaction_type table data
        $amazon_trans_type_data = AmazonTransactionType::getAmazonTransData();
        $trans_type_order_data = $this->getArrayColumn($amazon_trans_type_data, null, 'transaction_description');
       
        if(count($trans_type_data)>0)
        {
            foreach($trans_type_data as $trans_type)
            {
                if(isset($trans_type) && !is_null($trans_type)){
                    if(!in_array($trans_type,$trans_type_order_data))
                    {
                        $trans_explode_data = explode(' - ',$trans_type);
                        
                        if(!empty($trans_explode_data[0]) && !empty($trans_explode_data[1]) && !empty($trans_explode_data[2]) && !empty($trans_explode_data[3])){
    
                            $displayTransDescription =  $trans_explode_data[0].' - '.$trans_explode_data[1].' ( '.$trans_explode_data[2].' - '.$trans_explode_data[3].' ) ';
    
                        }else if(!empty($trans_explode_data[0]) && !empty($trans_explode_data[2]) && !empty($trans_explode_data[3])){
                            $displayTransDescription =  $trans_explode_data[0].' ( '.$trans_explode_data[2].' - '.$trans_explode_data[3].' ) ';
                        }else{
                            $displayTransDescription = null;
                        }
    
                        $insert_trans_type[] = array(
                            'amount_description' => (isset($trans_explode_data[0]) && $trans_explode_data[0] !== "") ? $trans_explode_data[0] : NULL,
                            'marketplace_name' => (isset($trans_explode_data[1]) && $trans_explode_data[1] !== "") ? $trans_explode_data[1] : NULL,
                            'transaction_type'=> (isset($trans_explode_data[2]) && $trans_explode_data[2] !== "") ? $trans_explode_data[2] : NULL,
                            'amount_type' => (isset($trans_explode_data[3]) && $trans_explode_data[3] !== "") ? $trans_explode_data[3] : NULL,
                            'transaction_description' => $trans_type,
                            'display_transaction_description' => $displayTransDescription,
                            'inserted_date' => date('Y-m-d H:i:s')
                        );
                    }
                }
            }

            if(!empty($insert_trans_type))
            {
                    AmazonTransactionType::saveSettlementTransData($insert_trans_type);
                    self::info('Amazon settlement transaction type data save');
                    Log::info('Amazon settlement transaction type data save');
            }
        }
    }

    /*
        @Description : Get All amazon order settelement data
        @Author      : Nirbhay Sharma
        @Output      : Insert data in amazon_transaction_type table
        @Date        : 24-11-2022
    */
    public function updateAmazonOrderSettlementData()
    {
        $update_amazon_transaction_id = array();

        // Get amazon_order_settlement_trans table data
        $amazon_settlement_data = AmazonSettlementReportTrans::getAmazonSettelementDataRecord();
        $amazon_sellement_type_data = $this->getArrayColumn($amazon_settlement_data, 'amazon_trans_id', 'type');
        
        // Get amazon_transaction_type table data
        $amazon_trans_type_data = AmazonTransactionType::getAmazonTransData();
        $trans_type_order_data = $this->getArrayColumn($amazon_trans_type_data,'id','transaction_description');
        
        if(count($amazon_sellement_type_data)>0)
        {
            foreach($amazon_sellement_type_data as $amazon_trans_id => $amazon_trans_type)
            {
                if(in_array($amazon_trans_type,$trans_type_order_data))
                {   
                    $amazon_transaction_type_id = array_search($amazon_trans_type,$trans_type_order_data);
                    $update_amazon_transaction_id[] = array(
                        'id' => $amazon_trans_id,
                        'amazon_transaction_type_id' => $amazon_transaction_type_id
                    );
                }
            }
            
            if(!empty($update_amazon_transaction_id))
            {
                AmazonSettlementReportTrans::updateAmazonTransactionTypeID($update_amazon_transaction_id);
                self::info('Updated Amazon transaction type table id');
                Log::info('Updated Amazon transaction type table id');
            }
        }
    }

    /*
        @Description : Get Spacific columns from array
        @Author      : Nirbhay Sharma
        @Output      : Get Spacific columns from array
        @Date        : 24-11-2022
    */
    public function getArrayColumn($arr, $id, $type){
       $result = [];
       if(isset($arr) && !empty($arr)){
            $result = array_column($arr, $type, $id);
       }
       return $result;
    }

    /*
        @Description : Currency conversion rate
        @Author      : Nirbhay Sharma
        @Output      : Updated currency conversion rate
        @Date        : 25-11-2022
    */
    public function updateCurrencyConversationRate(){

        $amazon_data = AmazonSettlementReportList::getAllCurrencyCodeSummeryReport();
        if(count($amazon_data) > 0)
        {
            $update_amazon_currency = array();
            foreach($amazon_data as $amazon_currency_data)
            {           
                $date = date('Y-m-d', strtotime($amazon_currency_data['end_date']));
                $total_amount  = $amazon_currency_data['total_amount'];
                $store_id = $amazon_currency_data['store_id'];

                $where_data = [
                    'date'=>$date,
                    'total_amount'=>$total_amount,
                    'store_id'=>$store_id,
                ];

                $amazon_financial_event = FinancialEventGroup::getAllAmazonFinancialEventReport($where_data);
                if(isset($amazon_financial_event)){
                    $converted_total_amount = $amazon_financial_event->converted_total_amount;

                    $original_total_amount = $amazon_financial_event->original_total_amount;
                    
                    if(!empty($converted_total_amount))
                    {
                        $currency_rate = $converted_total_amount/$original_total_amount;
                    
                        $update_amazon_currency[] = array(
                            'id' => $amazon_currency_data['id'],
                            'currency_rate' => $currency_rate
                        );
                    }                   
                }
            }

            if(!empty($update_amazon_currency))
            {
                AmazonSettlementReportList::updateCurrencyConversionRate($update_amazon_currency);
                self::info('Updated currency conversion rate');
                Log::info('Updated currency conversion rate');
            }
        }
    }
}
