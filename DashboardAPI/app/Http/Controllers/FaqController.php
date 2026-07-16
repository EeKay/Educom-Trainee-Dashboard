<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /*
    * Returns all faq items
    */
    public function getAll()
    {
        return \App\Models\Faq::latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * creates a faq item
    * params
    * string question
    * string answer
    */
    public function create(Request $request) 
    {
        $question = $request->input('question', 'Who am I?');
        $answer = $request->input('question', 'Dunno');
        \App\Models\Faq::create([
                        'question' => $question,
                        'answer' => $answer,
                        'is_active' => true 
                    ]);
    }

    /*
    * updates the specified faq item
    * params
    * int id
    * string question
    * string answer
    */
    public function update(Request $request, string $id)
    {
        $question = $request->input('question', 'Who am I?');
        $answer = $request->input('question', 'Or maybe I do');
        \App\Models\Faq::where('id', $id)->update([
                                        'question' => $question,
                                        'answer' => $answer
                                    ]);
    }

    /*
    * sets the specified faq item to active
    * params
    * int id
    */
    public function activate(string $id)
    {
        \App\Models\Faq::where('id', $id)->update([
                                        'is_active' => true 
                                    ]);
    }

    /*
    * sets the specified faq item to inactive
    * params
    * int id
    */
    public function deactivate(string $id)
    {
        \App\Models\Faq::where('id', $id)->update([
                                        'is_active' => false 
                                    ]);
    }

    /*
    * deletes the specified faq item
    * params
    * int id
    */
    public function delete(string $id)
    {
        \App\Models\Faq::where('id', $id)->delete();
    }
}
