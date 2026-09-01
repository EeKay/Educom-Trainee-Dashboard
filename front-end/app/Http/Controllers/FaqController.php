<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FaqController extends Controller
{
        public function Faq(Request $request){
        $faqs = Http::withToken(session('token'))->get(config('app.API_URL').'/faq');
        $faqs = $faqs->json() ?? [];

        return Inertia::render('Faq', [
            'faqs' => $faqs,
        ]);
    }

        public function FaqAdmin(Request $request){

        $faqs = Http::withToken(session('token'))->get(config('app.API_URL').'/faq');
        $faqs = $faqs->json() ?? [];

        return Inertia::render('FaqAdmin', [
            'faqs' => $faqs,
        ]);
    }

    public function FaqCreate(Request $request){
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        $question = $validated['question'];
        $answer = $validated['answer'];

        $response = Http::withToken(session('token'))
            ->post(config('app.API_URL').'/faq/create', [
                'question' => $question,
                'answer' => $answer,
            ]);

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqDelete(Request $request, $id = null){
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->delete(config('app.API_URL').'/faq/delete/'.$id);

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqActivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put(config('app.API_URL').'/faq/activate/'.$id);

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqDeactivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put(config('app.API_URL').'/faq/deactivate/'.$id);

        $body = $response->json() ?? [];

        return response()->json($body, $response->status());
    }

    // TODO fix the update 
    // public function FaqUpdate(Request $request, $id = null)
    // {
    //    if (!$id){
    //     return response()->json(['error' => 'id is required'], 400);
    //    } 

    //    $response = Http::withToken(session('token'))
    //     ->post(config('app.API_URL').'/faq/update',[
    //         'question' => $question,
    //         'answer' => $answer,
    //     ]);

    //     return Inertia::render('FaqAdmin', [
    //         'question' => $question,
    //         'answer' => $answer;
    //     ]);
    // }
}

