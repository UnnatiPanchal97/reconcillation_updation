<?php
namespace App\Helpers;
use Carbon\Carbon;
// use Session;
use Illuminate\Support\Facades\Session;
// use Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Imports\CollectionImport;
use App\Exports\ExcelExport;
use App\Models\MasterFileProduct;
use App\Models\AmazonProduct;
use App\Models\ProductAnalyzer;
use App\Models\ProductLog;
use App\Models\Store;
use DateInterval;
use DatePeriod;
use DateTime;
use Excel;
use Illuminate\Support\Facades\Lang;

class CommonHelper
{
    public static function getInsertedDateTime()
    {
        return Carbon::now()->today();
    }
    public static function extractFileContent($file_content, $report_id)
    {
        if (!Storage::exists('public/settlement_reports/')) {
            Storage::makeDirectory('public/settlement_reports/', 0777, true);
            print("storage");
        }
        $report_folder = storage_path("app/public/settlement_reports/");
        // dd($report_folder); die;
        $zipFile = $report_folder . $report_id . '.gz';
        // print_r($zipFile); die;
        $feedHandle = fopen($zipFile, 'w');
        // print_r($feedHandle); die;
        fclose($feedHandle);
        $feedHandle = fopen($zipFile, 'rw+');
        fwrite($feedHandle, $file_content);
        $gz = gzopen($zipFile, 'rb');
        $file_name = $report_folder . $report_id . '.txt';
        $dest = fopen($file_name, 'wb');
        stream_copy_to_stream($gz, $dest);
        gzclose($gz);
        fclose($dest);
        $report_data = file_get_contents($file_name);
        
        if (Storage::disk('public')->exists('settlement_reports/' . $report_id . '.gz')) {
            Storage::disk('public')->delete('settlement_reports/' . $report_id . '.gz');
        }
    
        return $report_data;
    }
    public static function getValue($variable, $keys = false, $default = NULL, $callable = false, $is_object = false)
    {
        if ($is_object) {
            // To build
        } else {
            if (is_array($keys)) {
                // Do nothing
            } else {
                $keys = explode('|', $keys);
            }
            $value = $variable;
            foreach ($keys as $key) {
                if (isset($value[$key])) {
                    if ($key == end($keys)) {
                        if ($callable && is_callable($callable)) {
                            return $callable($value[$key]);
                        } else {
                            return $value[$key];
                        }
                    } else {
                        $value = $value[$key];
                    }
                } else {
                    break;
                }
            }
        }
        return $default;
    }
    /*
     @Description: Function to auto generated sku
     @Author     : Sanjay Chabhadiya used Mehul Modh's code 
     @Input      : prefix and postfix
     @Output     : sku
     @Date       : 18-03-2021
    */
    public static function getSku($pre = "", $post = "")
    {
        $firstChar = Str::random(1);
        $uniqid = substr(uniqid(rand(), true), 2, 2);
        $uniqid = $firstChar . $uniqid;
        $uniqid = rtrim($uniqid, ".");
        $uniqid = strtoupper($pre . $uniqid . mt_rand() . $post);
        return strtoupper($uniqid);
    }
    public static function getBuilderParameters()
    {
        $parameters = [
            "dom" => "<'table-responsive'tr>" .
                "<'row px-3 m-0 border-top border-gray-300'" .
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li>" .
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" .
                ">",
            "stateSave" => true,
            "renderer" => 'bootstrap',
            'fixedHeader' => true,
            "processing" => true,
            "language" => [
                "processing" => "<script>$('body').addClass('page-loading1');</script>"
            ],
            "serverSide" => true,
            "lengthMenu"    => [array_keys(config('constants.PER_PAGE')), array_keys(config('constants.PER_PAGE'))],
            "bAutoWidth"    => true,
            "deferRender"   => true,
            'pageLength' => intval(request()->per_page ?? config('constants.DEFAULT_PER_PAGE')),
            "order"         => [],
            'searching' => false,
            "bLengthChange" => true, // Will Disabled Record number per page
            "bInfo" => true, //Will show "1 to n of n entries" Text at bottom
            "bPaginate" => true,
            "scrollX" => true,
            "columnDefs"    => [],
            'drawCallback' => 'function() {
                this.api().state.clear();
                // magnificPopup();
                // popOverData();
                KTMenu.createInstances();
                if ($(this).find(".dataTables_empty").length == 1) {
                   $(this).find(".dataTables_empty").text("No results found");
                }
                hide_loader();
                $("body").removeClass("page-loading1");
            }',
        ];
        $stateSave = Session::get('stateSave');
        if (!empty($stateSave)) {
            $page = Session::get('perPage');
            $parameters['displayStart'] = $page;
        }
        return $parameters;
    }
    public static function userRole($roleId)
    {
        if ($roleId == '1') {
            return 'Admin';
        } else {
            return 'User';
        }
    }
    public static function asinProductUrl($asin = '')
    {
        return "https://www.amazon.com/dp/" . $asin;
    }
    public static function priceFormat($price, $type = '')
    {
        $formatedPrice = '';
        if ($type == 'order') {
            $formatedPrice = Lang::get('messages.common.pound_sign') . ((is_null($price) || $price == '') ? '0.00' : number_format($price, 2));
        } else {
            $formatedPrice = (is_null($price) || $price == '') ? '-' : '$' . number_format($price, 2);
        }
        return $formatedPrice;
    }
    public static function in_arrayi($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
    public static function arraySearchi($search, $array)
    {
        return array_search(strtolower($search), array_map('strtolower', $array));
    }
    public static function loadFileData($file, $importClass = NULL)
    {
        if (is_null($importClass)) {
            $importClass = new CollectionImport;
        }
        $data =  Excel::toArray($importClass, $file, 'local');
        return $data;
    }
    public static function readFileInput($headerKeyArray, $data)
    {
        $fileData = [];
        if (count($data) > 1) {
            $headerRow = TRUE;
            foreach ($data as $value) {
                if ($headerRow) {
                    if (is_array($value) && count($value) > 0) {
                        $headerColumns = array_flip(array_filter($value));
                        $headerKeyArrayLower = array_change_key_case($headerKeyArray, CASE_LOWER);
                        $headerColumnsLower = array_change_key_case($headerColumns, CASE_LOWER);
                        $column = array_flip(array_diff(array_keys($headerKeyArrayLower), array_keys($headerColumnsLower)));
                        $lastHeaderColumn = end($headerColumns);
                        $newArr = [];
                        if (!empty($column)) {
                            $cnt = 0;
                            foreach ($column as $colKey => $colVal) {
                                $cnt++;
                                $newArr[$colKey] = ($lastHeaderColumn + $cnt);
                            }
                            $headerColumns = array_merge($headerColumns, $newArr);
                        }
                        // dd($headerColumns);
                        $headerColumns = array_flip(array_keys($headerColumns));
                        $fileData['header'] = $headerColumns;
                        foreach ($headerColumns as $key => $value) {
                            if ($key != '') {
                                $compareColumns[] = $key;
                                $compareColumnsNew[] = array(
                                    'data_key' => $value,
                                    'val'      => $key
                                );
                            }
                        }
                    }
                } else {
                    $excelDataTemp = array();
                    $isValid = FALSE;
                    foreach ($compareColumnsNew as $k => $val) {
                        if (isset($headerKeyArrayLower[strtolower($val['val'])])) {
                            if (isset($value[$val['data_key']]) && $value[$val['data_key']] != '') {
                                $isValid = TRUE;
                            }
                        }
                    }
                    if ($isValid) {
                        foreach ($compareColumnsNew as $k => $val) {
                            //if(isset($headerKeyArray[$val['val']]) && isset($value[$val['data_key']]))
                            if (isset($headerKeyArrayLower[strtolower($val['val'])])) {
                                $excelDataTemp[$headerKeyArrayLower[strtolower($val['val'])]] = isset($value[$val['data_key']]) ? $value[$val['data_key']] : '';
                            } else {
                                $excelDataTemp[str_replace(' ', '_', strtolower($val['val']))] = isset($value[$val['data_key']]) ? $value[$val['data_key']] : '';
                            }
                        }
                        $fileData['data'][] = $excelDataTemp;
                    }
                }
                $headerRow = FALSE;
            }
            return $fileData;
        }
    }
    public static function exportFile($exportData, $header, $fileName)
    {
        Excel::store(new ExcelExport($exportData, $header), $fileName, 'local');
    }
    public static function getFileType($type)
    {
        $fileType = '';
        if ($type == '1') {
            $fileType = 'Raw File';
        } elseif ($type == '2') {
            $fileType = 'Master File';
        } else {
            $fileType = 'Planner File';
        }
        return $fileType;
    }
    public static function formatedDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->format(config('constants.INSERT_DATE_FORMAT'));
    }
    public static function processingStatus($status)
    {
        $response = '';
        switch ($status) {
            case 'pending':
                $response = '<span class="badge badge-light-warning p-2 px-4">' . ucfirst($status) . '</span>';
                break;
            case 'completed':
                $response = '<span class="badge badge-light-success p-2 px-4">' . ucfirst($status) . '</span>';
                break;
            case 'in_process':
                $response = '<span class="badge badge-light-info p-2 px-4">' . ucfirst(str_replace('_', ' ', $status)) . '</span>';
                break;
            case 'error':
                $response = '<span class="badge badge-light-danger p-2 px-4">' . ucfirst($status) . '</span>';
                break;
            case 'failed':
                $response = '<span class="badge badge-light-danger p-2 px-4">' . ucfirst($status) . '</span>';
                break;
            default:
                $response = '<span class="badge badge-light-warning p-2 px-4">' . ucfirst($status) . '</span>';
                break;
        }
        return $response;
    }
    /**
     * Return array of last 15 days date staring from yesterday
     */
    public static function getLastFifteenDaysDatesArray()
    {
        $todayDate = date('Y-m-d', strtotime("+1 days"));
        $last15thDayDate = date('Y-m-d', strtotime("-17 days"));
        $begin = new DateTime($last15thDayDate);
        $end = new DateTime($todayDate);
        $fifteenDaysDateArray = [];
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $fifteenDaysDate = [];
        foreach ($period as $dt) {
            $fifteenDaysDate[] = $dt->format("Y-m-d");
        }
        return $fifteenDaysDate;
    }
    public static function ListingDateTimeFormat($dateTime, $listType = '')
    {
        if ($listType == 'analyzer_list') {
            $start  = strtotime($dateTime);
            $end    = time();
            $differenceDate = $end - $start;
            $seconds = $differenceDate;
            $minutes = round($differenceDate / 60);
            $hours = round($differenceDate / 3600);
            $days = round($differenceDate / 86400);
            $weeks = round($differenceDate / 604800);
            $months = round($differenceDate / 2600640);
            $years = round($differenceDate / 31207680);
            if ($seconds <= 60) {
                $dateDiff = $seconds . ' seconds ago';
            } elseif ($minutes <= 60) {
                $dateDiff = $minutes . ' minutes ago';
            } elseif ($hours <= 24) {
                $dateDiff = $hours . ' hours ago';
            } elseif ($days <= 7) {
                $dateDiff = $days . ' days ago';
            } elseif ($weeks <= 4.3) {
                $dateDiff = $weeks . ' weeks ago';
            } elseif ($months <= 12) {
                $dateDiff = $months . ' months ago';
            } else {
                $dateDiff = $years . ' years ago';
            }
            return '<span>' . Carbon::parse($dateTime)->format('m-d-y H:i') . '</span><br><span>' . $dateDiff . '</span>';
        } else {
            return Carbon::parse($dateTime)->format('m-d-y H:i');
        }
    }
    public static function ListingDateTimeFormatWithTime($dateTime)
    {
        return Carbon::parse($dateTime)->format('m-d-y h:i:s A');
    }
    public static function ListingDateFormat($dateTime)
    {
        return Carbon::parse($dateTime)->format('m-d-y');
    }
    public static function changeRecursiveDateYearAsPerFilter($inputDate, $filterYear)
    {
        $modifiedInputDate = date_create($inputDate);
        $monthYearInputDate =  date_format($modifiedInputDate, "m-d");
        $finalDate = $filterYear . "-" . $monthYearInputDate;
        return $finalDate;
    }
    public static function changeRecursiveStartEndDate($value, $filterYear)
    {
        $isRecursive = $value->is_recursive;
        $startDate = $value->order_start_date;
        $endDate = $value->order_end_date;
        if ($filterYear != null && $isRecursive == 1) {
            $holidayYear = date('Y', strtotime($value->holiday_date));
            $toAdd = $filterYear - $holidayYear;
            $newStartdate =  !empty($startDate) ? date('m-d-y', strtotime("+$toAdd year", strtotime($startDate))) : "";
            $newEnddate = !empty($endDate) ? date('m-d-y', strtotime("+$toAdd year", strtotime($endDate))) : "";
            return $newStartdate . "  -  " . $newEnddate;
        } else {
            $startDate = !empty($startDate) ? self::ListingDateFormat($startDate) : '';
            $endDate =  !empty($endDate) ? self::ListingDateFormat($endDate) : '';
        }
        return $startDate . "  -  " . $endDate;
    }
    public static function validateDate($date, $format = 'm-d-y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    function date_compare($a, $b)
    {
        $t1 = strtotime($a['holiday_date']);
        $t2 = strtotime($b['holiday_date']);
        return $t1 - $t2;
    }
    public static function validateAsinForOtherSuppliers($productObj, $data, $comment, $title_log_comment)
    {
        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        //Validation: ASIN + A pack + Case pack + UPC 
        $productArr = ProductAnalyzer::select('id')->where([
            ['asin', $productObj->asin],
            ['upc', $productObj->upc],
            ['a_pack', $productObj->a_pack],
            ['case_pack', $productObj->case_pack],
        ])->get();
        // Update all related ASIN in product analyzer
        if (!empty($productArr)) {
            foreach ($productArr as $productRow) {
                $productRow->update([
                    'is_a_pack_correct' => $data['is_a_pack_correct'],
                    'is_upc_correct' => $data['is_upc_correct'],
                    'is_potential_ip' => $data['is_potential_ip'],
                    'potential_ip_comment' => $comment,
                    'is_product_verified' => (string)$data['is_product_verified'],
                    'product_verified_by_title' => $title_log_comment
                ]);
            }
        }
        // For Master file product table
        $masterArr = MasterFileProduct::select('id')->where([
            ['asin', $productObj->asin],
            ['upc', $productObj->upc],
            ['a_pack', $productObj->a_pack],
            ['case_pack', $productObj->case_pack],
        ])->get();
        // Update all related ASIN in product analyzer
        if (!empty($masterArr)) {
            foreach ($masterArr as $masterRow) {
                $masterRow->update([
                    'is_product_verified' => (string)$productObj->is_product_verified,
                ]);
                ProductLog::create([
                    'master_product_id' => $masterRow->id,
                    'title' => $title_log_comment,
                    'description' => $productObj->is_product_verified == 1 ? "Verification done by $user" : "Verification failed by $user"
                ]);
            }
        }
    }
    public static function markUpcAsReviewed($upc)
    {
        $user = auth()->user()->first_name . " " . auth()->user()->last_name;
        $checkIsUpcReviewed = MasterFileProduct::where([
            ['upc', $upc],
            ['is_upc_reviewed', "1"]
        ])->first();
        if (!empty($checkIsUpcReviewed)) {
            $masterRows = MasterFileProduct::where([
                ['upc', $upc],
                ['is_upc_reviewed', "0"]
            ])->get();
            if (!empty($masterRows)) {
                foreach ($masterRows as $masterRow) {
                    $masterRow->update([
                        'is_upc_reviewed' => "1",
                        'upc_reviewed_log_title' => $checkIsUpcReviewed->upc_reviewed_log_title,
                        'upc_reviewed_date' => $checkIsUpcReviewed->upc_reviewed_date
                    ]);
                    ProductLog::create([
                        'master_product_id' => $masterRow->id,
                        'title' => $checkIsUpcReviewed->upc_reviewed_log_title,
                        'description' => "Reviewed by $user"
                    ]);
                }
            }
        }
    }
    public static function saveFilterInSession($filterArr, $page)
    {
        unset($filterArr['draw'], $filterArr['columns'], $filterArr['order'], $filterArr['start'], $filterArr['length'], $filterArr['_'], $filterArr['advanceSearch']);
        $filterArr = array_filter($filterArr);
        session()->forget($page);
        if (count($filterArr) > 0) {
            foreach ($filterArr as $name => $value) {
                $filterArr[$name] = base64_encode($value);
            }
            $query_string = \http_build_query($filterArr);
            $currentUrl = url()->current() . "?" . $query_string;
            session([$page => $currentUrl]);
        }
    }
    public static function saveFilterForProductAnalyzerList($filterArr)
    {
        unset($filterArr['draw'], $filterArr['columns'], $filterArr['order'], $filterArr['start'], $filterArr['length'], $filterArr['_']);
        $filterArr = array_filter($filterArr, function ($value) {
            return ($value !== null && $value !== false && $value !== '');
        });
        session()->forget('product_analyzer_list');
        if (count($filterArr) > 0) {
            foreach ($filterArr as $name => $value) {
                if ($name == "search") {
                    $filterArr[$name] = base64_encode($value);
                }
            }
            $filterAdvanceSearch = array_filter($filterArr['advanceSearch'], function ($value) {
                return ($value !== null && $value !== false && $value !== '');
            });
            $advanceSearchArr = [];
            if (!empty($filterAdvanceSearch)) {
                unset($filterAdvanceSearch['_token']);
                foreach ($filterAdvanceSearch as $key => $value) {
                    if (strpos($key, 'chk-') !== 0) {
                        $advanceSearchArr[$key] = $value;
                    }
                    if ($key == "supplier_filter") {
                        $advanceSearchArr[$key] =  implode(',', $value);
                    }
                    if ($key == "holiday_filter") {
                        $advanceSearchArr[$key] =  implode(',', $value);
                    }
                }
            }
            unset($filterArr['advanceSearch']);
            $finalArr = array_merge($filterArr, $advanceSearchArr);
            $query_string = urldecode(\http_build_query($finalArr));
            $currentUrl = url()->current() . "?" . $query_string;
            session(['product_analyzer_list' => $currentUrl]);
        }
    }
    public static function clearAllFilters($request)
    {
        session()->forget('product_analyzer_list');
        $params = $request->only(['draw', 'columns', 'order', 'start', 'length', 'search', '_', 'advanceSearch']);
        $searchArr = [];
        if (!empty($request->advanceSearch)) {
            parse_str($request->advanceSearch, $searchArr);
        }
        $params['advanceSearch'] = $searchArr;
        return $params;
    }
    /**
     * Return array of dates
     */
    public static function getDatesArray($days)
    {
        $todayDate = date('Y-m-d', strtotime("-2 days"));
        $definedDate = date('Y-m-d', strtotime("-" . $days . " days"));
        $begin = new DateTime($definedDate);
        $end = new DateTime($todayDate);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $dates = [];
        foreach ($period as $dt) {
            $dates[] = $dt->format("Y-m-d");
        }
        return $dates;
    }
    public static function calculateProfitMargin($livePrice, $costOfGood, $referralFees, $fbaFees)
    {
        $profit = round($livePrice - $costOfGood - $referralFees - $fbaFees, 3);
        $margin = round(($profit / $livePrice) * 100, 3);
        return ['profit' => $profit, 'margin' => $margin];
    }
    public static function generateNewSku()
    {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $twoDigit = substr(str_shuffle($string), 0, 2);
        $fourDigit = substr(str_shuffle($string), 0, 4);
        $lastFourDigit = substr(str_shuffle($string), 0, 4);
        $sku = $twoDigit . '-' . $fourDigit . '-' . $lastFourDigit;
        $amazonProduct = AmazonProduct::select('id')->where('sku', $sku)->first();
        if (isset($amazonProduct->id) && !empty($amazonProduct->id)) {
            return self::generateNewSku();
        } else {
            return $sku;
        }
    }
    public static function runCronForReorderListForSetting()
    {
        $command = 'cd ' . env('SHELL_EXEC_PATH') . ' && ' . env('SHELL_EXEC_URL');
        $command .= 'php artisan calculateReorderForGeneralSetting';
        shell_exec($command . " > /dev/null 2>&1 & ");
    }
    public static function runCronForReorderListForPO()
    {
        $command = 'cd ' . env('SHELL_EXEC_PATH') . ' && ' . env('SHELL_EXEC_URL');
        $command .= 'php artisan calculateReorderForPOChanges';
        shell_exec($command . " > /dev/null 2>&1 & ");
    }
    public static function runCronForReorderListForProductAnalyzer()
    {
        $command = 'cd ' . env('SHELL_EXEC_PATH') . ' && ' . env('SHELL_EXEC_URL');
        $command .= 'php artisan calculateReorderForProductAnalyzerChanges';
        shell_exec($command . " > /dev/null 2>&1 & ");
    }
}
