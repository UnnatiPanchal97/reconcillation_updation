<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Self_;
class MasterLogin extends Authenticatable
{
    use HasFactory,Notifiable,SoftDeletes;
    //use Billable;
    protected $connection = 'master';
    protected $table = 'master_login';
    /**
     * The attributes that are mass not assignable.
     *
     * @var array
     */
    protected $guarded = ['database_password'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'database_password',
    ];
    //user is super admin or not.
    public function isSuperAdmin()
    {
        return $this->user_role === config('params.user_role.super_admin');
    }
    //user is admin or not.
    public function isAdmin()
    {
        return $this->user_role === config('params.user_role.admin');
    }
    //user is user or not.
    public function isUser()
    {
        return $this->user_role === config('params.user_role.user');
    }
    public static function getAllCompaniesDBDetails()
    {
        return self::where('user_role', config('params.user_role.admin'))->whereColumn('id','parent_id')->orderBy('id','asc')
            ->get();
    }
}
