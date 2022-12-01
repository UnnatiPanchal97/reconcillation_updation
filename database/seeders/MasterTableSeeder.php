<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::connection('master')->table('master_login')->insert([
            'firstname' => 'Nirbhay',
            'lastname' => 'Sharma',
            'email' => 'nirbhay@topsinfosolutions.com',
            'password' => bcrypt('12345678'),
            'user_role' => config('params.user_role.super_admin'),
            'database_name' => 'reconcilations',
            'database_username' => 'root',
            'database_password' => null,
            'status' => config('params.status.active'),
        ]);
    }
}
