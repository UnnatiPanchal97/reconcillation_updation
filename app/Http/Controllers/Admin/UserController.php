<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\MasterLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj=new MasterLogin;
        $user=$obj->where([
                ['parent_id', '=', Auth::user()->id],
                ['user_role', '!=', '2'],
                ['deleted_at','=',NULL]
            ])->get();
        return view('users.user-index',compact('user'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $userObj = new User;
        $user=$request->all();
        $user['password']=bcrypt($request->password);
        $userObj->create($user);
        $last_insert_id = DB::getPdo()->lastInsertId();
        $masterObj=new MasterLogin;
        $user['parent_id']=Auth::user()->id;
        $user['company_name'] = Auth::user()->company_name;
        $user['child_id'] = $last_insert_id;
        $masterObj->create($user);
        return redirect()->back()->with('status', 'User Added Successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id=$request->id;
        $fetchData=$request->all();
        $masterUser = MasterLogin::find($id);
        $user=User::find($masterUser['child_id']);
        $user->update($fetchData);
        $masterUser->update($fetchData);
        return redirect()->back()->with('status', 'User Updated Successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $masterUser = MasterLogin::find($id);
        $user=User::find($masterUser['child_id']);
        $user->delete();
        $masterUser->delete();
        return redirect()->back()->with('delete', 'User Deleted Successfully.');
    }
}
