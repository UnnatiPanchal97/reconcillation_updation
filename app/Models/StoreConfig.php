<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreConfig extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'store_configs';
    protected $guarded = [];
}
