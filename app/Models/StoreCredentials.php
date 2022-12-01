<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class StoreCredentials extends Model
{
    use HasFactory;
    protected $table = 'store_credentials';
    protected $fillable = ['store_id', 'merchant_id', 'mws_auth_token', 'instance_id', 'refresh_token', 'access_token', 'mws_access_key_id', 'mws_secret_key', 'aws_access_key_id','aws_secret_key','amazon_aws_region', 'ads_refresh_token', 'ads_access_token', 'ads_client_id', 'ads_client_secret_key', 'sqs_query_url', 'is_fetch_order', 'order_fetching_start_date', 'return_order_fetch_date', 'seller_shipment_start_date', 'is_return_order_fetched', 'is_production', 'created_by', 'updated_by'];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    /*
        @Description    : Function to get the store specefic configurations
        @Author         : Nirbhay Sharma
        @Input          : store_id
        @Output         : Object of store credentials and config
        @Date           : 22-11-2022
    */
    public static function getStoreConfig($storeId)
    {
        // If store id is not empty
        if (!empty($storeId)) {
            // Set credentials and config data for store id
            // return self::where('store_id', $storeId)
            //     ->with(['store' => function ($query) {
            //         $query->select('id', 'store_type', 'user_id', 'store_name', 'currency_code', 'amazon_advertising_region', 'store_config_id', 'max_quantity_post')
            //         ->active()->with('storeConfig');
            //     }])
            //     ->whereHas('store', function ($query) {
            //         $query->active();
            //     })->first();
           return DB::table('stores')
                ->join('store_credentials', 'store_credentials.store_id', '=', 'stores.id')
                ->join('store_configs', 'store_configs.store_type', '=', 'stores.store_type')
                ->where('stores.id', '=', $storeId)
                ->first();
        }
        return [];
    }
}
