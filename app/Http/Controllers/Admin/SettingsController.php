<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
//use App\Http\Requests\CompanyInfoAddEdit;
use App\Models\CompanyConfiguration;
use App\Models\MasterLogin;
use App\Models\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    /*
     * show the basic company information form after new organization signup
     */
    public function newCompanyInfo()
    {
        $info = CompanyConfiguration::latest()->first();
        dd($info);
        if (!empty($info)){
            return redirect()->route('orders.index');
        }
        return view('settings.new_company_welcome');
    }

    /*
     * save basic company information
     */
    public function saveBasicCompanyInfo(Request $request)
    {
        $info = new CompanyConfiguration();
        $info->company_name = $request->company_name ? ucfirst(trim($request->company_name)) : null;
        $info->tax_percentage = config('params.aus_tax');
        $info->email = $request->email;
        if (!empty($request->logo)) {
            $info->logo = $request->logo;
        }
        $info->default_branding_name = ucfirst(trim($request->company_name));
        $info->contact_details = $request->display_add;
        $info->contact_number = $request->branding_telephone;
        $info->customer_service_url = !empty($request->customer_service_url)?$request->customer_service_url:null;
        $info->t_and_c = !empty($request->t_and_c)?$request->t_and_c:null;
        $info->customer_service_email = $request->email;
        if ($info->save()) {
            $this->storeImage($info);
        }
        return redirect()->route('orders.index');
    }
}
