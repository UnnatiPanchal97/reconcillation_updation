<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialEventGroup extends Model
{
    use HasFactory;
    protected $table = 'amazon_financial_event';
    protected $guarded = [];

    public static function getAllAmazonFinancialEventReport($where_data){ 
        //dd($where_data);
        if(isset($where_data) && !empty($where_data)){
            $totAmt = $where_data['total_amount'];
           
            $result = self::select(
                'amazon_financial_event.id',
                'amazon_financial_event.original_total_amount',
                'amazon_financial_event.converted_total_amount',
                'amazon_financial_event.financial_event_end_date'
            )
            ->where('amazon_financial_event.original_total_amount',$totAmt)
            ->where('amazon_financial_event.store_id',$where_data['store_id'])
            ->first();

            return $result; 
        }
    }
}
