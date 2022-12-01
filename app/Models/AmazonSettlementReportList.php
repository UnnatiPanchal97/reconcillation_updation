<?php

namespace App\Models;
use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AmazonSettlementReportList extends Model
{
    use HasFactory;
    protected $table = 'amazon_settlement_report_list';
    protected $fillable=['report_id', 'store_id', 'actual_store_id', 'report_request_id', 'report_availible_date', 'processed', 'is_quickbook_processed', 'amazon_settlement_id', 'total_amount', 'total_amount_usd', 'start_date', 'end_date', 'deposit_date', 'currency_rate', 'currency_code', 'settlement_file', 'is_deleted', 'last_modified'];
    
    public static function getSettlementReport($userId, $storeId, $reportType = null)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('is_processed', '0')
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE')
            ->first();
    }
    public static function getAmazonFbaReportLog($userId, $storeId, $reportType = null)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('is_processed', '0')
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA')
            ->first();
    }
    public static function getAmazonFeesReportLog($userId, $storeId, $reportType = null)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('is_processed', '0')
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_REFERRAL_FEE_PREVIEW_REPORT')
            ->first();
    }
    public static function getAmazonReportLogByRequestedDate($userId, $storeId, $reportType = null, $requestedDate)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            // ->where('is_processed', '0')
            ->where('requested_date', $requestedDate)
            ->whereDate('created_at', date('Y-m-d'))
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_MERCHANT_LISTINGS_DATA')
            ->first();
    }
    public static function getAmazonReportLogByRequestedDateForDownload($userId, $storeId, $reportType = null, $requestedDate)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('is_processed', '0')
            ->where('requested_date', $requestedDate)
            ->whereDate('created_at', date('Y-m-d'))
            // ->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, ?, created_at)) >= 30', Now())
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_MERCHANT_LISTINGS_DATA')
            ->first();
    }

    public static function getAllCurrencyCodeSummeryReport(){
      
        $result = self::select('amazon_settlement_report_list.id','amazon_settlement_report_list.currency_rate','amazon_settlement_report_list.currency_code','amazon_settlement_report_list.start_date','amazon_settlement_report_list.end_date','amazon_settlement_report_list.total_amount','amazon_settlement_report_list.report_availible_date','amazon_settlement_report_list.amazon_settlement_id','amazon_settlement_report_list.store_id')

        ->where('amazon_settlement_report_list.currency_code', '<>', 'USD')
        ->where('amazon_settlement_report_list.processed', '1')
        ->where('amazon_settlement_report_list.currency_rate', '1')
        ->orderBy('amazon_settlement_report_list.id', 'desc')
        ->get()
        ->toArray();

        return $result;
    }

    public static function updateCurrencyConversionRate($update_amazon_currency){
        if(isset($update_amazon_currency) && count($update_amazon_currency) > 0){
            foreach($update_amazon_currency as $val){
                if(!empty($val['id']) && !empty($val['currency_rate'])){
                    self::where('id', $val['id'])
                    ->update([
                        'currency_rate' => $val['currency_rate']
                    ]);
                }
            }
           return true;
        }
    }
}
