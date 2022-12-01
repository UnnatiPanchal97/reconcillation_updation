<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;
use App\Models\StoreCredentials;

class Store extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stores';
    protected $fillable=['parent_store_id', 'user_id', 'store_name', 'store_marketplace', 'store_type', 'store_config_id', 'is_sqs_registered', 'currency_code', 'is_active', 'amazon_advertising_region', 'is_master_store', 'max_quantity_post', 'created_by', 'updated_by'];
    public function storeCredentials()
    {
        return $this->hasOne(StoreCredentials::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }
    public function storeConfig()
    {
        return $this->belongsTo(StoreConfig::class);
    }

}