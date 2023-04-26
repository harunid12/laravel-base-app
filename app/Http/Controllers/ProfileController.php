<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $profile = Auth::user();
        return view('profile.view', ['profile' => $profile]);
    }

    public function update(Request $request, )
    {
        $profile = User::where('username', Auth::user()->username)->first();

        $request->validate([
            'name' => 'max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'username' => 'max:255',
            'password' => 'max:255'

        ]);

        // jika password dan image profile diganti
        if($request['password'] && $request->hasFile('image')){

            // upload ne image
            $image = $request->file('image');
            $image->storeAs('users', $image->hashName());

            // delete old image
            Storage::delete('users/'.$profile->image);

            // hash password
            $password = Hash::make($request->password);

            // update to database
            $profile->update([
                'name' => $request->name,
                'image' => $image->hashName(),
                'username' => $request->username,
                'password' => $password,
                'status' => 'active'
            ]);

        }
        // jika hanya password yang diganti
        elseif ($request['password']) {
            // hash password
            $password = Hash::make($request->password);

            // update to database
            $profile->update([
                'name' => $request->name,
                'username' => $request->username,
                'password' => $password,
                'status' => 'active'
            ]);
        }
        // jika hanya image yang diganti
        elseif ($request->hasFile('image')){
            // upload ne image
            $image = $request->file('image');
            $image->storeAs('users', $image->hashName());

            // delete old image
            Storage::delete('users/'.$profile->image);

            // update to database
            $profile->update([
                'name' => $request->name,
                'image' => $image->hashName(),
                'username' => $request->username,
                'status' => 'active'
            ]);
        }
        // jika tidak mengganti password atau image
        // hanya mengganti data form saja
        else {
            $profile->update([
                'name' => $request->name,
                'username' => $request->username,
                'status' => 'active'
            ]);
        }

        return redirect('/profile/'.$profile->username)->with('toast_success', 'Update Profile Successfully!');
    }
}
