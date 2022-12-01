<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;

class CreateTenantDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Create:TenantDB {db_name} {db_username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create tenant database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $db_name = $this->argument('db_name');
            $username = $this->argument('db_username');
            $password = \config('params.tenant_db_password');
            $this->createDatabaseSchema($db_name);
            $this->createDatabaseUser($username, $password);
            $this->grantPrivilegeToUser($db_name, $username);
            $this->configureConnection($username, $password, $db_name);
            //$this->sqlModeChange();
            Artisan::call('migrate', array('--database' => 'tenant', '--path' => 'database/migrations/tenant', '--force' => true));

            //create default seeder records
            //Artisan::call('db:seed', array('--class' => 'ShippingCarriersSeeder','--force' => true));
            //Artisan::call('db:seed', array('--class' => 'DefaultCategorySeeder','--force' => true));
        } catch (Exception $ex) {}
    }

    /**
     * Creates a new database schema.
     * @param string $schemaName The new schema name.
     * @return bool
     */
    private function createDatabaseSchema($schemaName)
    {
        //`statement` method from the connection class for create database
        return DB::connection('master')->statement('CREATE DATABASE ' . $schemaName . ' DEFAULT CHARACTER SET utf8;');
    }

    /**
     * Creates a new database user.
     * @param string $username ,$password.
     * @return bool
     */
    private function createDatabaseUser($username, $password)
    {
        return DB::connection('master')->statement('CREATE USER \'' . $username . '\'@\'localhost\' IDENTIFIED BY \'' . $password . '\';');
    }

    /**
     * Grant privilege to created user
     * @param string $username ,$password.
     * @return bool
     */
    private function grantPrivilegeToUser($db_name, $username)
    {
        return DB::connection('master')->statement('GRANT ALL PRIVILEGES ON ' . $db_name . '.* TO \'' . $username . '\'@\'localhost\' WITH GRANT OPTION;');
        //return DB::connection('master')->statement("GRANT ALL PRIVILEGES ON ' . $db_name . '.* TO '" . $username . "'@'localhost' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;");
    }

    /**
     * Configures a tenant's database connection.
     * @param string $db_name The database name.
     * @return void
     */
    private function configureConnection($username, $password, $db_name)
    {
        Config::set('database.connections.tenant',
            [
                "host" => '127.0.0.1',
                "database" => $db_name,
                "username" => $username,
                "password" => $password,
                "driver" => 'mysql',
            ]);

        DB::purge('tenant');
        DB::setDefaultConnection('tenant');

    }
}
