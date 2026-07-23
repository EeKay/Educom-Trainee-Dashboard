<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HulpchatService
{
    /**
     * Send a trainee's question to the n8n Helpdesk workflow.
     *
     * traineeId must always come from the authenticated request
     * (never from client input) — enforced by the caller (controller).
     * timestamp is generated here, server-side, rather than trusting
     * the client's clock.
     */
    public function ask(
        int $traineeId,
        string $question,
        string $sessionId,
        bool $faqRejected
    ): array {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Helpdesk-Secret' => config('services.n8n.secret'),
                ])
                ->post(config('services.n8n.url') . config('services.n8n.webhook_path'), [
                    'traineeId' => $traineeId,
                    'question' => $question,
                    'sessionId' => $sessionId,
                    'timestamp' => now()->toIso8601String(),
                    'faqRejected' => $faqRejected,
                ]);

            if ($response->failed()) {
                Log::warning('Helpdesk: n8n returned an error response', [
                    'status' => $response->status(),
                    'traineeId' => $traineeId,
                    'sessionId' => $sessionId,
                ]);

                return $this->escalationFallback('n8n_error_response');
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Helpdesk: could not reach n8n', [
                'message' => $e->getMessage(),
                'traineeId' => $traineeId,
                'sessionId' => $sessionId,
            ]);

            return $this->escalationFallback('helpdesk_unavailable');
        }
    }

    private function escalationFallback(string $reason): array
    {
        return [
            'answer' => null,
            'resolved' => false,
            'source' => 'escalated',
            'reason' => $reason,
        ];
    }
}
?>