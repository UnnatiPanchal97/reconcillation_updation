<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonCronErrorLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function logError($storeId, $batchId, $module, $submodule, $errorContent){
        $errorLog = new AmazonCronErrorLog;
        $errorLog->store_id = $storeId;
        $errorLog->sheet_id = $batchId;
        $errorLog->module = $module;
        $errorLog->submodule = $submodule;
        $errorLog->error_content = serialize($errorContent);
        $errorLog->save();
    }
}