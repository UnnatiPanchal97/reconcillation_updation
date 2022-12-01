<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\MasterLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show-profile');
    }
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $master=MasterLogin::find($request->id);
        $userTable=User::find($master['child_id']);
        $data= $userTable->fill($request->all());
        $data->save();
        $validated=$request->user()->fill($request->validated());
        if ($request->file('avatar')){
            $destinationPath = 'avatars/';
            $file = $request->file('avatar');
            $profileAvatar = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileAvatar);
            $request->user()->avatar= $destinationPath.$profileAvatar;
        }
        if ($request->user()->isDirty('email')) {   
            $request->user()->email_verified_at = null;
        }
        $validated->save();
        return Redirect::route('profile.edit')->with('profile', 'Profile Updated Successfully.');
    }
    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
