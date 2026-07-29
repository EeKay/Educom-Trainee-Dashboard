<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class nanController extends Controller
{
    /*
    * Contact n8n workflow
    * params
    * int traineeId
    * string question
    * int sessionId
    * boolean faqRejected
    */
    public function contactWorkflow(Request $request) 
    {
        $fields = $request->validate([
            'question' => 'required',
            'faqRejected' => 'required'
        ]);

        $user = app(AuthController::class)->getUser($request);
        if ($user == null) {
            return response()->json(['message' => 'No valid API key provided']);
        } 
        dd($fields);
        $response = Http::withHeaders(['n8n-secret' => env('NAN_KEY')])->post('https://ai.educom.nu/t/23090/webhook-test/helpdesk-chat', [
            'traineeId' => $user->id,
            'question' => $fields['question'],
            'timestamp' => Carbon::now(),
            'faqRejected'  => $fields['faqRejected']
        ]);
        return $response->json();
    }

    /*
    * Sends reset password email to current user
    */
    public function resetPassword(Request $request) 
    {
        $user = app(AuthController::class)->getUser($request);
        if ($user == null) {
            return response()->json(['message' => 'User not found']);
        } 

        $response = Http::withHeaders(['n8n-secret' => env('NAN_KEY')])->post('https://ai.educom.nu/t/23090/webhook-test/n8n-reset-request', [
            'email' => $user->email
        ]);
        return $response->json();
    }
}
