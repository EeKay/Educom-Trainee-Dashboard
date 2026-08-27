<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    * Returns all Users
    */
    public function getUsers() 
    {
        return \App\Models\User::latest()->get()->toJson();
    }

    public function create(Request $request)
    {
        $request->validate([
            'team' => 'required',
            'name' => 'required',
            'email' => 'required',
            'key_alias' => 'required',
            'password' => 'required'
        ]);


        \App\Models\User::create($request->all());
    }

    public function update(Request $request, string $id)
    {
        if(isset($request['password'])) {
            $password = $request['password'];
            $request['password'] = Hash::make($request['password']);
        }
        \App\Models\User::where('id', $id)->update($request->all());
    }

    public function delete(string $id) 
    {
        \App\Models\User::where('id', $id)->delete();
    }
}
