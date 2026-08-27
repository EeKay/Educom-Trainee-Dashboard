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
    * string question
    * boolean faqRejected
    */
    public function contactWorkflow(Request $request) 
    {
        $fields = $request->validate([
            'question' => 'required',
            'faqRejected' => 'required'
        ]);

        $user = $request->user();

        $response = Http::withHeaders(['n8n-secret' => config('app.NAN_KEY')])->post(config('NAN_URL').'/helpdesk-chat', [
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
        $user = $request->user();

        $response = Http::withHeaders(['n8n-secret' => config('app.NAN_KEY')])->post(config('NAN_URL').'/n8n-reset-request', [
            'email' => $user->email
        ]);
        return $response->json();
    }
}
