<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $fields = $request->validate([
            'team' => 'required',
            'name' => 'required',
            'email' => 'required',
            'key_alias' => 'required',
            'password' => 'required'
        ]);
        $fields['role'] = $request->input('role', 'trainee');

        \App\Models\User::create([
            'team' => $fields['team'],
            'name' => $fields['name'],
            'role' => $fields['role'],
            'email' => $fields['email'],
            'key_alias' => $fields['key_alias'],
            'password' => $fields['password']
        ]);
    }

    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            'team' => 'required',
            'name' => 'required',
            'role' => 'required',
            'email' => 'required',
            'key_alias' => 'required',
            'password' => 'required'
        ]);

        \App\Models\User::where('id', $id)->update([
                                        'team' => $fields['team'],
                                        'name' => $fields['name'],
                                        'role' => $fields['role'],
                                        'email' => $fields['email'],
                                        'key_alias' => $fields['key_alias'],
                                        'password' => $fields['password']
                                    ]);
    }

    public function delete(string $id) 
    {
        \App\Models\User::where('id', $id)->delete();
    }
}
