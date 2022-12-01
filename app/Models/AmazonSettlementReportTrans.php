<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class AmazonSettlementReportTrans extends Model
{
    use HasFactory;
    protected $table = 'amazon_settlement_report_trans';
    protected $fillable = ['amazon_order_id', 'settlement_report_id', 'merchant_order_item_id', 'amazon_transaction_type_id', 'transaction_type', 'merchant_order_id', 'adjustment_id', 'shipment_id', 'marketplace_name', 'amount_type', 'amount_description', 'amount', 'fulfillment_id', 'amazon_order_Item_code', 'merchant_adjustment_item_id', 'posted_date_time', 'promotion_id', 'sku', 'quantity_purchased', 'updated', 'inserted_date', 'last_modified'];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static function getAmazonSettelementData(){
     
        return AmazonSettlementReportTrans::distinct()->select(DB::raw("CONCAT(amazon_settlement_report_trans.amount_description,' - ',amazon_settlement_report_trans.marketplace_name,' - ',amazon_settlement_report_trans.transaction_type,' - ',amazon_settlement_report_trans.amount_type) as type"))
        ->get()
        ->toArray();
    }
    
    public static function getAmazonSettelementDataRecord(){

        return AmazonSettlementReportTrans::distinct()->select("amazon_settlement_report_trans.id as amazon_trans_id",DB::raw("CONCAT(amazon_settlement_report_trans.amount_description,' - ',amazon_settlement_report_trans.marketplace_name,' - ',amazon_settlement_report_trans.transaction_type,' - ',amazon_settlement_report_trans.amount_type) as type"))
        ->where('amazon_settlement_report_trans.amazon_transaction_type_id',NULL)
        ->get()
        ->toArray();
    }

    public static function updateAmazonTransactionTypeID($update_amazon_transaction_id_Arr){
        if(isset($update_amazon_transaction_id_Arr) && !empty($update_amazon_transaction_id_Arr)){
            foreach($update_amazon_transaction_id_Arr as $val){
                if(!empty($val['id']) && !empty($val['amazon_transaction_type_id'])){
                    AmazonSettlementReportTrans::where('id', $val['id'])
                    ->update([
                        'amazon_transaction_type_id' => $val['amazon_transaction_type_id']
                    ]);
                }
            }
           return true;
        }
    }

    public static function updateColumTbl(){
        $results = DB::table('amazon_settlement_report_trans')
        ->select('amazon_settlement_report_list.*','amazon_settlement_report_trans.id','amazon_settlement_report_trans.settlement_report_id','amazon_settlement_report_trans.marketplace_name as trans_marketplace_name')
        ->join('amazon_settlement_report_list','amazon_settlement_report_list.amazon_settlement_id','=','amazon_settlement_report_trans.settlement_report_id')
        ->where(['amazon_settlement_report_list.marketplace_name' => null])
        ->get();

       if(isset($results) && !empty($results)){
            foreach($results as $key => $val){
                if($val->amazon_settlement_id == $val->settlement_report_id){
                    if($val->marketplace_name == null){
                        DB::table('amazon_settlement_report_list')->where('amazon_settlement_id',$val->amazon_settlement_id)->where('marketplace_name', null)->update(['marketplace_name' => $val->trans_marketplace_name]);
                    }
                }
            }

            return true;
       }
    }
}
