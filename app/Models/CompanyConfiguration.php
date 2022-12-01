<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyConfiguration extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getConfig()
    {
        return self::latest()->first();
    }
}
