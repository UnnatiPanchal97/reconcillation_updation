<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     * added to web middleware in kernel.php
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()) {
            Config::set('database.connections.tenant',
                [
                    "host" => '127.0.0.1',
                    "database" => auth()->user()->database_name,
                    "username" => auth()->user()->database_username,
                    "password" => auth()->user()->database_password,
                    "driver" => 'mysql',
                ]);

            DB::purge('tenant');
            DB::setDefaultConnection('tenant');

            //check accounting app connection
            // if (!auth()->user()->isSuperAdmin()){
            //     \config(['params.accounting_sevice_config' => User::getCompanyAdmin()->account_service]);
            // }

        }
        //dd($next($request));
        return $next($request);
    }
}
