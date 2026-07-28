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
