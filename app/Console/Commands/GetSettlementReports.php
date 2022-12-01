<?php
namespace App\Console\Commands;

use App\Models\AmazonSettlementReportList;
use App\Models\Store;
use App\Models\StoreCredentials;
use App\Models\MasterLogin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;
use Illuminate\Support\Facades\Log;
use App\Traits\ConsoleAuthentication;
use App\Helpers\SetConnection;
use Validator;

class GetSettlementReports extends Command
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
    protected $signature = 'getsettlement:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get settlement reports from report api';

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
                    //set users database connection
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

    private function fetchProducts($storeId = null)
    {
        if(!empty($storeId)){
            $amazonSpApi = new ReportsApi($this->configArr);
            $results = $amazonSpApi->getReports($this->configArr);
        
            // If success to get report id
            if (isset($results['payload']) && !empty($results)) {

                foreach ($results['payload'] as $result) {

                    $ReportId = isset($result['reportId']) ? $result['reportId'] : '';
                    $ReportType = isset($result['reportType']) ? $result['reportType'] : '';
                    $ReportAvailableDate = isset($result['createdTime']) ? date('Y-m-d H:i:s', strtotime($result['createdTime'])) : null;
                    AmazonSettlementReportList::updateOrCreate([ 
                        'report_id' => $ReportId,
                        'store_id' => $storeId,
                        'report_availible_date' => $ReportAvailableDate,
                    ]);
                }
                
                self::info('Amazon settlement report added successfully');
                Log::info('Amazon settlement report added successfully');
                return true;
            }
            self::error('Authorization failed!');
            Log::info('ERROR-Console-Commands-GetsettlementReports-fetchproducts = Authorization failed');
            return false;
        }
    }
}
