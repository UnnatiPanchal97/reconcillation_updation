<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Tops\AmazonSellingPartnerAPI\Api\FinancesApi;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Traits\ConsoleAuthentication;
use App\Models\FinancialEventGroup;
use App\Models\MasterLogin;
use App\Models\Store;
use Validator;
use DateTime;
use App\Helpers\SetConnection;

class GetFinancialEventGroup extends Command
{
    use ConsoleAuthentication;
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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getfinancialevent:group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the each of every other transaction or chargis';

    /**
     * The cron data
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        //getting new company users...
        $companies = MasterLogin::getAllCompaniesDBDetails();
        if ($companies->count() > 0) {
            foreach ($companies as $company) {
                if(isset($company->database_name) && !empty($company->database_name) && isset($company->database_username) && !empty($company->database_username) && isset($company->database_password) && !empty($company->database_password)){
                    //set company users database connection
                    SetConnection::set_tenant_connection($company->database_name, $company->database_username, $company->database_password);
                    $stores= Store::where('store_marketplace',config('params.market_place.amazon'))->where('is_active',1)->get();
                    if ($stores->count() > 0) {
                        foreach ($stores as $store) {
                            //Authentication
                            $this->configArr = self::index($store->id);

                            // Get financial events group from FBA api...
                            $latest_financial_start_date = FinancialEventGroup::where('store_id', $store->id)->max('financial_event_start_date');

                            if(isset($latest_financial_start_date) && $latest_financial_start_date != '')
                            {
                                $event_start_date	= date('Y-m-d H:i:s', strtotime($latest_financial_start_date.' -2 months'));
                                $event_end_date	= date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . ' -2 minutes'));
                            }
                            else
                            {
                                // Initially, get the data from January 2022
                                $event_start_date = date('Y-m-d H:i:s', strtotime('2022-08-01 00:00:00'));
                                $event_end_date   = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . ' -2 minutes'));
                            }
                            $current_date = date('Y-m-d H:i:s');

                            if($event_end_date < $current_date)
                            {		
                                $this->START_DATE = date('Y-m-d\TH:i:s.u\Z', strtotime($event_start_date));
                                $this->END_DATE   = date('Y-m-d\TH:i:s.u\Z', strtotime($event_end_date));

                                //Initialize obj
                                $amazonSpApi = new FinancesApi($this->configArr);
                                //parameter for group events
                                $group_param = array(
                                    'MaxResultsPerPage'                => 30,
                                    'FinancialEventGroupStartedAfter'  => $this->START_DATE,
                                    'FinancialEventGroupStartedBefore' => $this->END_DATE,
                                );
                                if(!empty($store->id)){
                                    $response = self::get_financial_event_group($store->id, $group_param);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *  Description	: Function to get financial events group from FBA
     * @param : string Financial event start after date
     * @param : string start befor date //optional
     * @param : string next token //optional
     * @param : int maxresult per page  //optional
     * @author: Nirbhay Sharma
     * Date	  : 23-11-2022
     */
	public function get_financial_event_group($storeId, $param, $token=null)
	{	
        if(isset($param) && !empty($param) && isset($storeId) && !empty($storeId)){
            //Initialize obj
            $amazonSpApi = new FinancesApi($this->configArr);
        
            if(isset($token) && $token!='' && $token!=null){
                $param['NextToken'] = $token;
            }
            
            $event_list_result = $amazonSpApi->listFinancialEventGroups($param);
            
            if(isset($event_list_result['payload']) && !empty($event_list_result['payload']))
            {
                $event_list_payload = $event_list_result['payload'];
               
                if(!empty($event_list_payload['FinancialEventGroupList']))
                {
                    $event_group_list = $event_list_payload['FinancialEventGroupList'];
                    self::insert_event_details($event_group_list, $storeId);
                }

                if(isset($event_list_payload['NextToken']) && $event_list_payload['NextToken'] != '' && $storeId != '')
                {
                    $next_token = $event_list_payload['NextToken'];
                    self::get_financial_event_group($storeId, $param, $next_token);
                }
            }
        }
	}

    /**
     * @Description	: Function to insert financial events into table
	 * @author : Nirbhay Sharma
     * @param : event group list array
     * @datae : 23-11-2022 
     */
	public function insert_event_details($event_group_list, $storeId)
	{
		if(!empty($event_group_list) && !empty($storeId))
		{	
			foreach ($event_group_list as $event_key => $event) 
			{	
				$event_id =  $event['FinancialEventGroupId'];

				$insert_event_list = array(
					'event_group_id'                 => isset($event['FinancialEventGroupId']) ? $event['FinancialEventGroupId'] : NULL,
					'store_id'                       => $storeId,
					'processing_status'              => isset($event['ProcessingStatus']) ? $event['ProcessingStatus'] : NULL,
					'fund_transfer_status'           => isset($event['FundTransferStatus']) ? $event['FundTransferStatus'] :NULL,
					'original_total_amount'          => isset( $event['OriginalTotal']['CurrencyAmount']) ? $event['OriginalTotal']['CurrencyAmount'] : NULL,
					'origin_amount_currency_code'    =>  isset($event['OriginalTotal']['CurrencyCode']) ? $event['OriginalTotal']['CurrencyCode'] : NULL,
					'converted_total_amount'         =>  isset($event['ConvertedTotal']['CurrencyAmount']) ? $event['ConvertedTotal']['CurrencyAmount'] : NULL,
					'converted_amount_currency_code' =>   isset($event['ConvertedTotal']['CurrencyCode']) ? $event['ConvertedTotal']['CurrencyCode'] : NULL,
					'beginning_balance'              =>  isset( $event['BeginningBalance']['CurrencyAmount']) ? $event['BeginningBalance']['CurrencyAmount'] : NULL,
					'beginning_balance_currency_code' =>   isset($event['BeginningBalance']['CurrencyCode']) ? $event['BeginningBalance']['CurrencyCode'] : NULL,
					'fund_transfer_date'             =>  isset($event['FundTransferDate']) ? date('Y-m-d H:i:s',strtotime($event['FundTransferDate'])) : NULL,
					'financial_event_start_date'     =>  isset($event['FinancialEventGroupStart']) ? date('Y-m-d H:i:s',strtotime($event['FinancialEventGroupStart'])) : NULL,
					'financial_event_end_date'       =>  isset($event['FinancialEventGroupEnd']) ? date('Y-m-d H:i:s',strtotime($event['FinancialEventGroupEnd'])) : NULL,
					'trace_id'                       =>  isset($event['TraceId']) ? $event['TraceId'] : NULL,
					'account_tail'                   =>  isset( $event['AccountTail']) ? $event['AccountTail'] : NULL,
				);

                FinancialEventGroup::updateOrCreate(
                    [
                        'store_id' => $storeId,
                        'event_group_id' => $event_id
                    ],$insert_event_list
                );
			}    
            self::info('Financial event detail save successfully');
            Log::info('Financial event detail save successfully'); 
            return true;
		}
        self::error('Financial event detail error');
        Log::info('Error-Console-Commands-GetFinancialEventGroup-insert_event_details Financial event detail error'); 
        return false;
	}
}
