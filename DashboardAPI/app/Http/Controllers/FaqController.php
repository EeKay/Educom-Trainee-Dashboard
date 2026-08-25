<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /*
    * Returns all faq items
    */
    public function getFaqEntries()
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
        //TODO remove default values, default values for testing purposes only
        $question = $request->input('question', 'Who am I?');
        $answer = $request->input('answer', 'Dunno');
        $faq = \App\Models\Faq::create([
                        'question' => $question,
                        'answer' => $answer,
                        'is_active' => true 
                    ]);
        return json_encode($faq);
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
        //TODO remove default values, default values for testing purposes only
        $question = $request->input('question', 'Who am I?');
        $answer = $request->input('answer', 'Or maybe I do');
        $faq = \App\Models\Faq::where('id', $id)->update([
                                        'question' => $question,
                                        'answer' => $answer
                                    ]);
        return json_encode($faq);
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
