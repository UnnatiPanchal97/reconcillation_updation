<?php
namespace App\Traits;
use App\Models\StoreCredentials;
use Illuminate\Support\Facades\Log;

trait ConsoleAuthentication {

    public function index($storeId) {
        
        // Get store config for store id
        $storeConfig = StoreCredentials::getStoreConfig($storeId);

        // If store config found
        if (!isset($storeConfig->id)) {
            
            Log::info('ERROR-App-Traits-ConsoleAuthentication-index = Store id not found!');
            return false;
        }
        $configArr = [
            'access_token' => $storeConfig->access_token,
            'marketplace_ids' => [$storeConfig->amazon_marketplace_id ?? ''],
            'access_key' => $storeConfig->aws_access_key_id,
            'secret_key' => $storeConfig->aws_secret_key,
            'region' => $storeConfig->amazon_aws_region,
            'host' => $storeConfig->aws_endpoint,
            'reportTypes' => 'GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE_V2',
        ];
       
        if(!$configArr){
            $this->error('Authentication failed!');
            Log::info('Authentication failed!');
            return false;
        }
        return $configArr;
    }
}