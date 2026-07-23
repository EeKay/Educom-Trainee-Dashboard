<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class nanController extends Controller
{
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

    public function resetPassword(Request $request) 
    {
        $fields = $request->validate(['email' => 'required']);

        $response = Http::withHeaders(['n8n-secret' => env('NAN_KEY')])->post('https://ai.educom.nu/t/23090/webhook-test/n8n-reset-request', [
            'email' => $fields['email']
        ]);
        return $response->json();
    }
}
