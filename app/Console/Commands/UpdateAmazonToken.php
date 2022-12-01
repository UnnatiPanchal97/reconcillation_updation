<?php
namespace App\Console\Commands;

use App\Models\Store;
use App\Models\StoreConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Authentication;
use App\Models\StoreCredentials;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Models\MasterLogin;
use App\Helpers\SetConnection;
use Exception;

class UpdateAmazonToken extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateaccesstoken:amazon';
    protected $currency = '';
    protected $cron = [
        // Set cron data
        'hour' => '',
        'date' => '',
        'cron_title' => 'UPDATE_AMAZON_ACCESS_TOKEN',
        'cron_name' => '',
        'store_id' => '',
        'fetch_report_log_id' => '',
        'report_source' => '1',//SP API
        'report_freq' => '2',//Daily
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
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
                            $this->updateAccessToken($store->id);
                        }
                    }
                }
            }
        }
    }

    /*
    @Description    : Function to update the amazon access token in database
    @Author         : Nirbhay Sharma
    @Input          :
    @Output         :
    @Date           : 22-11-2022
     */
    private function updateAccessToken($storeId = null)
    {
        if(!empty($storeId)){
            $storeConfig = StoreCredentials::getStoreConfig($storeId);
            // If store config found
            if (!isset($storeConfig->id)) {
                return;
            }
           
            // Make credential array
            $sellerConfig = [
                "refresh_token"    => $storeConfig->refresh_token,
                "client_id" => $storeConfig->mws_access_key_id,
                "client_secret"  => $storeConfig->mws_secret_key,
                'marketplace_ids' => [$storeConfig->amazon_marketplace_id ?? ''],
                'access_key' => $storeConfig->aws_access_key_id,
                'secret_key' => $storeConfig->aws_secret_key,
                'region' => $storeConfig->amazon_aws_region
            ];
            
            $amazonSpApi = new Authentication($sellerConfig['client_id'], $sellerConfig['client_secret']);
            $response = $amazonSpApi->getAccessTokenFromRefreshToken('refresh_token', $sellerConfig['refresh_token']);
            $responseArr = json_decode(json_encode($response), true);
            
            // update access token
            if (isset($responseArr) && !empty($responseArr) && isset($responseArr['access_token'])) {
                $storeCredential = StoreCredentials::where('store_id', $storeId)->first();
                $storeCredential->update(['access_token' => $responseArr['access_token']]);
                if ($storeCredential->access_token==$responseArr['access_token']) {
                    return $this->info("Access token updated");
                }
            }else{
                return $this->error("Access token missing!");
            }
        }
    }
}
