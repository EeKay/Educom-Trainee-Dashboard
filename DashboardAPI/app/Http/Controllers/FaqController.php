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
        return \App\Models\Faq::where('is_active', true)->latest()->get()->toJson();
    }

    /*
    * creates a faq item
    * params
    * string question
    * string answer
    */
    public function create(Request $request) 
    {
        $fields = $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);
        $faq = \App\Models\Faq::create([
                        'question' => $fields['question'],
                        'answer' => $fields['answer'],
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
        $fields = $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);
        $faq = \App\Models\Faq::where('id', $id)->update([
                                        'question' => $fields['question'],
                                        'answer' => $fields['answer'],
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
