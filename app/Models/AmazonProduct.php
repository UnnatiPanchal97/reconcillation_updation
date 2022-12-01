<?php

namespace App\Models;

use App\Models\Store;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

	public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    /*
    @Description    : Function to get only actvie products
    @Author         : Sanjay Chabhadiya
    @Input          : 
    @Output         : where condition
    @Date           : 22-03-2021
     */
    public function scopeActive($query)
    {
    	return $query->whereIn('amazon_products.is_active', ['0', '1']);
    }

    /*
    @Description    : Function to get list of all the existing SKUs in particular store
    @Author         : Sanjay Chabhadiya
    @Input          : storeId, sellerId
    @Output         : array of SKU
    @Date           : 09-03-2021
     */
    public static function existingList($data = [])
    {
        extract($data);
        // Get all amazon products from db
        $products = self::select('id', 'store_id', 'user_id', 'sku', 'title', 'category_id', 'brand', 'color', 'asin', 'price', 'qty', 'main_image', 'is_active', 'product_category_id', 'category_name', 'material_type', 'item_height', 'item_length', 'item_width', 'item_weight', 'is_adult_product', 'size')
        ->where('store_id', $storeId);
        if (!empty($asin)) {
            $products->whereIn('asin', $asin);
        }

        $products = $products->get();
        // Make array of fetched SKU
        $productList = [];
        if ($products->count() > 0) {
            foreach ($products as $product) {
                $productList[$product->user_id][$product->store_id][$product->$fieldName] = $product;
            }
        }

        return $productList;
    }

    /*
    @Description    : Function to get list of all the existing SKUs AND ASINs in particular store
    @Author         : Sanjay Chabhadiya
    @Input          : store_id
    @Output         : array of SKU and ASIN
    @Date           : 20-07-2021
    */
    public static function existingProductsList($storeId = null)
    {
        $products = self::select('id', 'sku', 'asin')
        ->where('store_id', $storeId)
        ->get();

        $productList = [];

        if ($products->count() > 0) {
            foreach ($products as $row)  {
                $productList['sku'][$row->sku] = $row;
                $productList['asin'][$row->asin] = $row;
                $productList[$row->sku][$row->asin] = $row;
            }
        }

        return $productList;
    }

    /*
    @Description    : Function to insert new SKUs from Amazon to database
    @Author         : Sanjay Chabhadiya
    @Input          : SKU Array
    @Output         :
    @Date           : 10-03-2021
     */

    public static function insertNewSkus($data = [])
    {
        if (is_array($data) && count($data) > 0) {
            self::insert($data);
        }
    }

    /*
    @Description    : Function to update the existing SKUs from Amazon to database
    @Author         : Sanjay Chabhadiya
    @Input          : SKU Array
    @Output         :
    @Date           : 10-03-2021
     */

    public static function batchUpdate($data = [])
    {
        if (is_array($data) && count($data) > 0) {
            \Batch::update(new self, $data, 'id');
        }
    }

    /*
    @Description    : Function to get list of all the existing SKUs in particular store
    @Author         : Lekhraj Verma
    @Input          : storeId, sellerId
    @Output         : array of SKU
    @Date           : 10-03-2021
     */
    public static function getUnprocessAsinList($sellerId = null, $storeId = null)
    {
        // Get all amazon products from db
        return self::select('id', 'asin')
            ->where('store_id', $storeId)
            ->where('user_id', $sellerId)
            ->where('is_product_process', '1')
            ->limit(30)->get();
    }

    /*
    @Description  : Function to get amazon products
    @Author     : Sanjay Cha bhadiya
    @Input      :
    @Output     :
    @Date     : 19-03-2021
    */
    public static function getProducts($limit = null)
    {
        return self::where('if_product_detail_updated', "1")
        ->whereNull('product_id')->whereNotNull('title')
        ->orderBy('id', 'ASC')->limit($limit)
        ->get();
    }

    /*
    @Description    : Function to get list of all the existing SKUs in particular store for FBA Products
    @Author         : Sanjay Chabhadiya
    @Input          : 
    @Output         : 
    @Date           : 28-05-2021
    */
    
    public static function fbaProductExistingSku($storeId)
    {
        return self::select('id', 'sku', 'product_id', 'asin', 'price', 'qty', 'is_active','fnsku','qty','fba_inbound_qty','fba_total_qty','is_fba_archived','afn_reserved_quantity','afn_inbound_working_quantity','afn_inbound_shipped_quantity','afn_inbound_receiving_quantity','reserved_fc_transfers','reserved_fc_processing','reserved_customerorders')
        ->where('store_id', $storeId)
        ->where('if_fulfilled_by_amazon', '1')
        ->get()
        ->keyBy('sku');
    }

    public function amazonOrderItem()
    {
      return $this->hasMany(AmazonOrderItem::class);
    }

    public function fbaProductReferralFeeSku($storeId)
    {
        return self::select('id', 'sku', 'product_id', 'asin', 'price', 'qty', 'is_active','fnsku','qty','estimated_fee_total','estimated_referral_fee_per_unit','expected_fulfillment_fee_per_unit','if_fulfilled_by_amazon')
        ->where('store_id', $storeId)
        ->where('if_fulfilled_by_amazon', '1')
        ->get()
        ->keyBy('sku');
    }

    public function getAmazonProductByAsin($asin)
    {
        return self::select('id','sku','fnsku','asin','units_at_amazon','in_stock','afn_inbound_working_quantity','afn_inbound_shipped_quantity','afn_inbound_receiving_quantity','qty','afn_reserved_quantity','is_active')
            ->where('asin', $asin)
            ->get();
    }

    public function getActiveAmazonProducts()
    {
        return self::select('id','sku','fnsku','asin','units_at_amazon','in_stock','afn_inbound_working_quantity','afn_inbound_shipped_quantity','afn_inbound_receiving_quantity','qty','afn_reserved_quantity','reserved_fc_transfers','reserved_fc_processing','reserved_customerorders','is_active','deleted_at',
                DB::raw("SUM(units_at_amazon) as total_units_at_amazon"), 
                DB::raw("SUM(in_stock) as total_in_stock"), 
                DB::raw("SUM(afn_inbound_working_quantity) as total_afn_inbound_working_quantity"), 
                DB::raw("SUM(afn_inbound_shipped_quantity) as total_afn_inbound_shipped_quantity"), 
                DB::raw("SUM(afn_inbound_receiving_quantity) as total_afn_inbound_receiving_quantity"), 
                DB::raw("SUM(qty) as total_qty"), 
                DB::raw("SUM(afn_reserved_quantity) as total_afn_reserved_quantity"), 
                DB::raw("SUM(reserved_fc_transfers) as total_reserved_fc_transfers"), 
                DB::raw("SUM(reserved_fc_processing) as total_reserved_fc_processing"), 
                DB::raw("SUM(reserved_customerorders) as total_reserved_customerorders"), 
                DB::raw("GROUP_CONCAT(sku SEPARATOR ', ') as all_sku"), 
                DB::raw("GROUP_CONCAT(fnsku SEPARATOR ', ') as all_fnsku")
            )
            ->whereNULL('deleted_at')
            ->groupBy('asin')
            ->get();
    }
}
