<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class SetConnection
{
    /**
     * Create the helper.
     *
     * @return void
     */
    public static function set_tenant_connection($db_name,$username,$password)
    {
        Config::set('database.connections.tenant',
            [
                "host" => '127.0.0.1',
                "database" => $db_name,
                "username" => $username,
                "password" => !empty($password) ? $password : '',
                "driver" => 'mysql',
            ]);

        DB::purge('tenant');
        DB::setDefaultConnection('tenant');
    }
}
