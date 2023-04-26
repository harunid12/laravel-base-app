<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role_id', 2)->get();
        return view('users.view', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'username' => 'required|unique:users|max:255',
            'password' => 'required|max:255',

        ]);

        // upload foto
        $image = $request->file('image');
        $image->storeAs('users', $image->hashName());

        // hash password
        $password = Hash::make($request->password);

        // create to database
        User::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'username' => $request->username,
            'password' => $password,
            'status' => 'active'
        ]);

        return redirect('users')->with('toast_success', 'Create User Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($username)
    {
        $user = User::where('username', $username)->first();
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $username)
    {
        $user = User::where('username', $username)->first();

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
            Storage::delete('users/'.$user->image);

            // hash password
            $password = Hash::make($request->password);

            // update to database
            $user->update([
                'name' => $request->name,
                'image' => $image->hashName(),
                'username' => $request->username,
                'password' => $password,
                'status' => $request->status
            ]);

        }
        // jika hanya password yang diganti
        elseif ($request['password']) {
            // hash password
            $password = Hash::make($request->password);

            // update to database
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'password' => $password,
                'status' => $request->status
            ]);
        }
        // jika hanya image yang diganti
        elseif ($request->hasFile('image')){
            // upload ne image
            $image = $request->file('image');
            $image->storeAs('users', $image->hashName());

            // delete old image
            Storage::delete('users/'.$user->image);

            // update to database
            $user->update([
                'name' => $request->name,
                'image' => $image->hashName(),
                'username' => $request->username,
                'status' => $request->status
            ]);
        }
        // jika tidak mengganti password atau image
        // hanya mengganti data form saja
        else {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'status' => $request->status
            ]);
        }

        return redirect('/users')->with('toast_success', 'User Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($username)
    {
        $deletedUser = User::where('username', $username)->first();

        // delete image
        Storage::delete('users/'.$deletedUser->image);

        // delete user
        $deletedUser->delete();

        return redirect('/users');
    }
}
