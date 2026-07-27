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
            'traineeId' => 'required',
            'question' => 'required',
            'sessionId' => 'required',
            'faqRejected' => 'required'
        ]);

        $response = Http::withHeaders(['n8n-secret' => env('NAN_KEY')])->post('https://ai.educom.nu/t/23090/webhook-test/helpdesk-chat', [
            'traineeId' => $fields['traineeId'],
            'question' => $fields['question'],
            'sessionId' => $fields['sessionId'],
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
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;

        $response = Http::withHeaders(['n8n-secret' => env('NAN_KEY')])->post('https://ai.educom.nu/t/23090/webhook-test/n8n-reset-request', [
            'email' => $user->email
        ]);
        return $response->json();
    }
}
