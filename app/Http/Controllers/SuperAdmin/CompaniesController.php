<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
// use App\CompanyConfiguration;
use App\Http\Requests\ChangeProfileRequest;
use App\Http\Requests\UpdatePassword;
use App\Http\Resources\TenantCompanyShowResource;
// use App\Jobs\customer\WelcomeTenant;
// use App\Jobs\notifyNewSignupJob;
// use App\Jobs\sendAdminEmail;
use App\Models\MasterLogin;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
// use Yajra\DataTables\DataTables;
use App\Http\Requests\CompanyCreateRequest;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('store');
        // $this->middleware('is_super_admin')->except(['downloadXeroDocument', 'store']);
        // $this->middleware('signed')->only(['edit']);
    }

    /**
     * Create new company record with new database.
     *
     * @param CompanyCreateRequest $request
     * @return void
     */
    public function store(CompanyCreateRequest $request)
    {
        try {
            $company = $this->addEditCompany($request, '');
            if ($company->save()) {
                $database = $this->createDatabaseSchema($company);
                if ($database) { 
                    
                    $user = User::create([
                        'firstname' => $company->firstname,
                        'lastname' => $company->lastname,
                        'email' => $company->email,
                        'password' => $company->password,
                        'user_role' => config('params.user_role.admin'),
                        // 'xero_consumer_key' => $company->xero_consumer_key,
                        // 'xero_consumer_secret' => $company->xero_consumer_secret,
                        // 'xero_private_key' => $company->xero_private_key,
                        // 'xero_account_code' => $company->xero_account_code,
                    ]);

                   
                    //after setup ->make user to active.
                    $company->database_username = 'tenant' . $company->id;
                    $company->parent_id = $company->id;
                    $company->child_id = $user->id;
                    $company->status = config('params.status.active');
                    $company->trial_ends_at = now()->addDays(30);
                    $company->save();
                    $data['company_name'] = $company->company_name;
                    $data['firstname'] = $company->firstname;
                    $data['lastname'] = $company->lastname;
                    $data['phone'] = $company->phone;
                    $data['email'] = $company->email;
                    $data['password'] = $request->password;

                    //notify super admin to new signup
                    //notifyNewSignupJob::dispatch($data)->delay(now()->addSeconds('2'));

                    //welcome tenant email
                    //WelcomeTenant::dispatch($data)->delay(now()->addSeconds('2'));

                    //send welcome email to admin
                    if (Auth::check()) {
                        return $this->sendResponse(trans('common.message.company_created'), '200', '');
                    } else {
                        try {
                            Auth::loginUsingId($company->id);
                            $this->setDatabaseConnection($company->database_name,$company->database_username,$company->database_password);
                            return redirect()->route('new-company.info');
                        } catch (Exception $exception) {
                            return redirect()->route('login');
                        }
                    }
                }
            }
            return $this->sendResponse(trans('common.message.company_created'), '200', '');
        } catch (Exception $ex) {
            return $this->sendError(trans('common.message.something_wrong'), '500');
        }
    }

    /*
     * Create database of the company
     * @param $company object
     * @return boolean
     */
    private function createDatabaseSchema($company)
    {
        //username will be "tenant".ID
        try {
            Artisan::call('Create:TenantDB ' . $company->database_name . ' tenant' . $company->id);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

     /**
     * Common function for Add-Edit company record
     *
     * @param $request
     * @param int $id
     * @return $company object
     */
    private function addEditCompany($request, $id = null)
    {
        $input = array_map('trim', $request->all());
        if (!empty($id)) {
            $company = MasterLogin::findOrFail($id);
            if (!empty($input['password_confirmation'])) {
                $company->password = bcrypt($input['password_confirmation']);
            }
            if ($company->status != $input['status']) {
                //$company->status = $input['status'];
                MasterLogin::updateStatusOfAllCompanyUsers($id, $input['status']);
            }
        } else {
            $db_name = 'tenant_recon_' . Str::random('30');
            $company = new MasterLogin();
            $company->password = bcrypt($input['password']);
            $company->email = $input['email'];
            $company->user_role = config('params.user_role.admin');
            $company->database_name = $db_name;
            $company->database_password = '12345678';
            $company->status = config('params.status.inactive');
        }
        $company->company_name = ucfirst($input['company_name']);
        $company->firstname = ucfirst($input['firstname']);
        $company->lastname = ucfirst($input['lastname']);
        $company->phone = $input['phone'];

        return $company;
    }
}
