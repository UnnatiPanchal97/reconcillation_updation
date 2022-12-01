<?php
namespace App\Console\Commands;
use App\Helpers\CommonHelper;
use App\Models\AmazonSettlementReportList;
use App\Models\AmazonSettlementReportTrans;
use App\Models\Store;
use App\Models\StoreCredentials;
use App\Models\MasterLogin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;
use App\Traits\ConsoleAuthentication;
use Illuminate\Support\Facades\Log;
use App\Helpers\SetConnection;
use Validator;

class GetSettlementReportDetail extends Command
{
    use ConsoleAuthentication;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getsettlementreport:detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get amazon settlement report details from FBA';
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
                        foreach ($stores as $store) {
                            //Authentication
                            $this->configArr = self::index($store->id);

                            // Call the FBA Shipment List
                            self::fetchProducts($store->id);
                        }
                    }
                }
            }
        }
    }    

    /*
    @Description  : Function to get the report details from FBA 
    @Author         : Nirbhay Sharma
    @Date           : 23-11-2022
    */
    private function fetchProducts($storeId)
    {
        $amazonSpApi = new ReportsApi($this->configArr);
        $settlementReportList=AmazonSettlementReportList::where('processed','0')->limit(2)->get();
        if($settlementReportList->count() > 0){
            foreach ($settlementReportList as $key => $value) { 

                //get report details
                $result = $amazonSpApi->getReport($value['report_id']);
               
                //report document details
                $report_document = $amazonSpApi->getReportDocument($result['payload']['reportDocumentId']);
                
                //download report
                $response = self::downloadDocument($report_document, $result['payload']['reportId']);
                
                if(isset($response) && $response!=''){
    
                    //update report file on appropriat reportId
                    $filename = $result['payload']['reportId'] . '.txt';                
                    AmazonSettlementReportList::where('report_id', $result['payload']['reportId'])->update(['settlement_file' => $filename]);
    
                    self::saveProducts($response, $result['payload']['reportId'], $storeId);
                    AmazonSettlementReportTrans::updateColumTbl();
                    AmazonSettlementReportList::where('report_id', $result['payload']['reportId'])->update([
                        "processed" => "1",
                    ]);
                    
                    self::info('Amazon settlement report detail added successfully = `report_id:`'.$result['payload']['reportId']);
                    Log::info('Amazon settlement report detail added successfully = `report_id:`'.$result['payload']['reportId']);
    
                }else{
                    //update as process with error
                    AmazonSettlementReportList::where('report_id', $result['payload']['reportId'])->update([
                        "is_processed" => "2"
                    ]);
                    self::error('Amazon settlement report detail process error = `report_id`'.$result['payload']['reportId']);
                    Log::info('Amazon settlement report detail process error = `report_id`'.$result['payload']['reportId']);
                }
            }
        }
    }

    /*@Description  : Function to download document file into our system from FBA
    @Author         : Nirbhay Sharma
    @Input          : download document response and report id
    @Date           : 23-11-2022
     */
    private function downloadDocument($response, $reportId)
    {
        if (isset($response['payload']) && !empty($response['payload'])) {
            $reportDetails = $response['payload'];
            $iv = base64_decode(CommonHelper::getValue($reportDetails, 'encryptionDetails|initializationVector'));
            $key = base64_decode(CommonHelper::getValue($reportDetails, 'encryptionDetails|key'));
            $url = CommonHelper::getValue($reportDetails, 'url');
            $reportData = openssl_decrypt(file_get_contents($url), "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv);
            $reportData = CommonHelper::extractFileContent($reportData, $reportId);
        }
        return $reportData ?? [];
    }

    /*@Description  : Function to get the report details
                        - We have removed the fist row of report because it will be title text
                        - In the second row we will get the total of the entire report so we will add into report_list tbl
    @Author         : Nirbhay Sharma
    @Input          : reportId, reportData as a string
    @Date           : 23-11-2022
     */
    private function saveProducts($reportData, $reportId, $storeId)
    {
        if(isset($storeId) && !empty($storeId)){
            // triming data
            $reportData = rtrim($reportData, "\n");

            // replace tab with new line in report data
            $reportData = str_replace("\t\n", "\n", $reportData);
            $excelColumnMapping = $this->getExcelColumnMapping($reportData);
           
            $report_array = array();

            // Manilpulating data from report
            if(isset($reportData) && !empty($reportData)){
                foreach (explode("\n", $reportData) as $key => $row) {
                
                    if ($key > 0)
                    {
                        $column_data = explode("\t", $row);
                        
                        if(count($excelColumnMapping) > count($column_data))
                        {
                            $difference = count($excelColumnMapping)- count($column_data);
    
                            for ($i=0; $i < $difference ; $i++) 
                            { 
                                $column_data[] = "";
                            }
                        }
                        
                        if (count($excelColumnMapping) == count($column_data)) {
                            $report_array[] = array_combine(array_keys($excelColumnMapping), $column_data);
                        }
                    }
                }
            }
            
            if(isset($report_array) && !empty($report_array)){
                foreach ($report_array as $key => $value) {
                
                    if ($key > 0) {
                        AmazonSettlementReportTrans::updateOrCreate(
                        [
                            "settlement_report_id"        => trim($value['settlement-id']) !== "" ? $value["settlement-id"] : NULL,
                            "transaction_type"            => trim($value['transaction-type']) !== "" ? $value["transaction-type"] : NULL,
                            "amazon_order_id"             => trim($value['order-id']) !== "" ? $value["order-id"] : NULL,
                            "merchant_order_id"           => trim($value['merchant-order-id']) !== "" ? $value["merchant-order-id"] : NULL,
                            "adjustment_id"               => trim($value['adjustment-id']) !== "" ? $value["adjustment-id"] : NULL,
                            "shipment_id"                 => trim($value['shipment-id']) !== "" ? $value["shipment-id"] : NULL,
                            "marketplace_name"            => trim($value['marketplace-name']) !== "" ? $value["marketplace-name"] : NULL,
                            "amount_type"                 => trim($value['amount-type']) !== "" ? $value["amount-type"] : NULL,
                            "amount_description"          => trim($value['amount-description']) !== "" ? $value["amount-description"] : NULL,
                            "amount"                      => trim($value['amount']) !== "" ? $value["amount"] : NULL,
                            "fulfillment_id"              => trim($value['fulfillment-id']) !== "" ? $value["fulfillment-id"] : NULL,
                            "posted_date_time"            => trim($value['posted-date-time']) !== "" ? date('Y-m-d H:i:s', strtotime(trim($value["posted-date-time"], 'UTC'))) : NULL,
                            "amazon_order_Item_code"      => trim($value['order-item-code']) !== "" ? $value["order-item-code"] : NULL,
                            "merchant_order_item_id"      => trim($value['merchant-order-item-id']) !== "" ? $value["merchant-order-item-id"] : NULL,
                            "merchant_adjustment_item_id" => trim($value['merchant-adjustment-item-id']) !== "" ? $value["merchant-adjustment-item-id"] : NULL,
                            "sku"                         => trim($value['sku']) !== "" ? $value["sku"] : NULL,
                            "quantity_purchased"          => trim($value['quantity-purchased']) !== "" ? $value["quantity-purchased"] : NULL,
                            "promotion_id"                => trim($value['promotion-id']) !== "" ? $value["promotion-id"] : NULL,
                        ]);
                        self::info('Amazon settlement report trans save');
                        Log::info('Amazon settlement report trans save');
                    }
                    else
                    {
                        $value['settlement-start-date'] = date("Y-m-d h:i:s", strtotime(str_replace("UTC", "", $value['settlement-start-date'])));
                        $value['settlement-end-date'] = date("Y-m-d h:i:s", strtotime(str_replace("UTC", "", $value['settlement-end-date'])));;
                        $value['deposit-date'] = date("Y-m-d h:i:s", strtotime(str_replace("UTC", "", $value['deposit-date'])));;
                        
                        AmazonSettlementReportList::where('report_id', $reportId)->update([
                            "amazon_settlement_id" => trim($value['settlement-id']) !== "" ? $value["settlement-id"] : NULL,
                            "start_date"           => trim($value['settlement-start-date']) !== "" ? $value["settlement-start-date"] : NULL,
                            "end_date"             => trim($value['settlement-end-date']) !== "" ? $value["settlement-end-date"] : NULL,
                            "deposit_date"         => trim($value['deposit-date']) !== "" ? $value["deposit-date"] : NULL,
                            "total_amount"         => trim($value['total-amount']) !== "" ? $value["total-amount"] : NULL,
                            "currency_code"        => trim($value['currency']) !== "" ? $value["currency"] : NULL
                        ]);
                        self::info('Amazon settlement report list update');
                        Log::info('Amazon settlement report list update');
                    }
                }
            }
        }
    }
    
    /*@Description  : Function to get column mapping detail when importing product excel
    @Author         : Nirbhay Sharma
    @Date           : 23-11-2022
     */
    function getExcelColumnMapping($reportData)
    {
        $excelColumnMapping = array();
        $reportData = explode("\n", $reportData);
        if (count($reportData) > 0) {
            $reportData = $reportData[0];
            $reportData = rtrim($reportData, "\t");
            $excelColumns = explode("\t", $reportData);
            $excelColumnMapping = array_flip($excelColumns);
        }
        return $excelColumnMapping;
    }

}