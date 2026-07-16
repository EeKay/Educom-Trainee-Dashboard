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
        return \App\Models\User::latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    //TODO make migration to replace this, for testing purposes only
    public function AddMe() {
        //puts me in the database for testing, do not call if already present in database
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'email' => 'paulhoi541@gmail.com',
            'key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678']);
        dd($col);
    }
}
