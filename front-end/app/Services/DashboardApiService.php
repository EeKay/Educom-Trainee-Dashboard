<?php
namespace App\Services;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


class DashboardApiService {
    /**
     * @return array{ok: bool, token: ?string, role: ?string, message: ?string}
     */
    public function login(string $name, string $password) : array
    {
        $response = Http::post(config('app.API_URL').'/login', [
                        'name' => $name,
                        'password' => $password,
                    ]);

        $body = $response->json() ?? [];
        
        return([
            'ok' => $response->successful() && !empty($body['token']),
            'token' => $body['token'] ?? null,
            'role' => $body['role'] ?? null,
            'message' => $body['message'] ?? null,
        ]);
    }

}

