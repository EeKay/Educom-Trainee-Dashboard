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

    //TODO make migration to replace this, for testing purposes only
    public function AddMe() {
        //puts me in the database for testing, do not call if already present in database
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'role' => 'admin',
            'email' => 'paulhoi541@gmail.com',
            'key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678']);
        dd($col);
    }

    public function AddEdu() {
        //puts me in the database for testing, do not call if already present in database
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Educom LLM',
            'email' => 'email',
            'key_alias' => 'Educom Dashboard LLM Key',
            'password' => '12345678']);
        dd($col);
    }

    public function AddRuLian() {
        //puts me in the database for testing, do not call if already present in database
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Ru Lian Wang',
            'email' => 'email1',
            'key_alias' => 'educom_openclaw_key_0909wanggmailcom',
            'password' => '12345678']);
        dd($col);
    }

    public function AddLoek() {
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Loek de Kleijn',
            'email' => 'email2',
            'key_alias' => 'educom_openclaw_key_loekdekleijn03gmailc',
            'password' => '12345678']);
        dd($col);
    }
}
