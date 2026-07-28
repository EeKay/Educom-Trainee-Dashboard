<?php

namespace App\Http\Controllers;

use App\Services\HulpchatService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HulpchatController extends Controller
{
    public function ask(Request $request, HulpchatService $hulpchat): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1000'],
            // Free-form ID from the frontend (e.g. a UUID per chat session) —
            // restricted to a safe charset since it gets forwarded downstream.
            'sessionId' => ['required', 'string', 'max:64', 'regex:/^[a-zA-Z0-9\-_]+$/'],
            // Accepts "true"/"false", "1"/"0", or real booleans from the client.
            'faqRejected' => ['required', 'boolean'],
        ]);

        // Must-have: traineeId derived server-side from the authenticated
        // user, never trusted from the request body.
        $traineeId = $request->user()->id;

        // $request->boolean() reliably normalizes "true"/"false" strings,
        // "1"/"0", or actual booleans into a real PHP bool.
        $faqRejected = $request->boolean('faqRejected');

        $result = $hulpchat->ask(
            traineeId: $traineeId,
            question: $validated['question'],
            sessionId: $validated['sessionId'],
            faqRejected: $faqRejected,
        );

        return response()->json($result);
    }
}
?>