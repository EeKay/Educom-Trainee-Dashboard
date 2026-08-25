<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use inertia\inertia;

class FaqController extends Controller
{
        public function Faq(Request $request){
        $faqs = Http::withToken(session('token'))->get(env('API_URL').'/faq');
        $faqs = (array) $faqs->json();

        return Inertia::render('Faq', [
            'faqs' => $faqs
        ]);
    }

        public function FaqAdmin(Request $request){

        $faqs = Http::withToken(session('token'))->get(env('API_URL').'/faq');
        $faqs = (array) $faqs->json();

        return Inertia::render('FaqAdmin', [
            'faqs' => $faqs,
        ]);
    }

    public function FaqCreate(Request $request){
        $question = $request->query('question');
        $answer = $request->query('answer');

        if (!$question) {
            return response()->json(['error' => 'question required'], 400);
        }

        if (!$answer) {
            return response()->json(['error' => 'answer required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->post(env('API_URL').'/faq/create', [
                'question' => $question,
                'answer' => $answer,
            ]);

        return response()->json(
            $response->json(),
            $response->status()
        );
    }

    public function FaqDelete(Request $request, $id = null){
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->delete(env('API_URL').'/faq/delete/'.$id);

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqActivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put(env('API_URL').'/faq/activate/'.$id);

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqDeactivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put(env('API_URL').'/faq/deactivate/'.$id);

        $body = $response->json() ?? [];

        return response()->json($body, $response->status());
    }
}

