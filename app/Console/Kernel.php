<?php

namespace App\Console;

use App\Console\Commands\UpdateAmazonToken;
use App\Console\Commands\GetSettlementReports;
use App\Console\Commands\GetSettlementReportDetail;
use App\Console\Commands\GetFinancialEventGroup;
use App\Console\Commands\ProcessAmazonTransactionType;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Middleware\SetDatabaseConnection;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateAmazonToken::class,
        GetSettlementReports::class,
        GetSettlementReportDetail::class,
        GetFinancialEventGroup::class,
        ProcessAmazonTransactionType::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('updateaccesstoken:amazon')->everyMinute();
        //$schedule->command('getsettlement:reports')->everyMinute();
        //$schedule->command('getsettlementreport:detail')->everyMinute();
        //$schedule->command('getfinancialevent:group')->everyMinute();
        //$schedule->command('processamazon:transactiontype')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
