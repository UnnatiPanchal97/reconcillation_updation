<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonTransactionType extends Model
{
    use HasFactory;
    protected $table = 'amazon_transaction_type';
    protected $guarded = [];

    public static function getAmazonTransData(){
     
        return AmazonTransactionType::distinct()->select(array('amazon_transaction_type.id','amazon_transaction_type.transaction_type',
        'amazon_transaction_type.amount_type','amazon_transaction_type.amount_description','amazon_transaction_type.transaction_description'))
        ->get()
        ->toArray();
    }

    public static function saveSettlementTransData($insert_trans_type){
        if(isset($insert_trans_type) && !empty($insert_trans_type)){
           return AmazonTransactionType::insert($insert_trans_type);
        }
    }
}

