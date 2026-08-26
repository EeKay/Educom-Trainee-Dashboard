<?php

namespace App\Http\Controllers;

use App\Services\DashboardApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ViewController extends Controller
{
    public function Dashboard(Request $request){
        $today = date('Y-m-d');
        $token = session('token');

        $users = Http::withToken($token)->get(config('app.API_URL').'/users');
        $users = (array) $users->json();

        $user_daily_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/period/user/?start_date='.$today.'&end_date='.$today);
        $user_daily_usage = (array) $user_daily_usage->json();

        $user_weekly_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/week/user/');
        $user_weekly_usage = (array) $user_weekly_usage->json();

        $user_monthly_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/month/user/');
        $user_monthly_usage = (array) $user_monthly_usage->json();

        $users_leaderboard = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/month');
        $users_leaderboard = (array) $users_leaderboard->json();
                
                
        return Inertia::render('Dashboard', [
            'users' => $users, 
            'user_daily_usage' => $user_daily_usage, 
            'user_weekly_usage' => $user_weekly_usage,
            'user_monthly_usage' => $user_monthly_usage,
            'users_leaderboard' => $users_leaderboard
        ]);
    }
    public function DashboardAdmin(Request $request, $currentUser = 2){
        
        $today = date('Y-m-d');
        $token = session('token');


        $users = Http::withToken($token)->get(config('app.API_URL').'/users');
        $users = (array) $users->json();

        $user_daily_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/period/user/'.$currentUser.'?start_date='.$today.'&end_date='.$today);
        $user_daily_usage = (array) $user_daily_usage->json();

        $user_weekly_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/week/user/'.$currentUser);
        $user_weekly_usage = (array) $user_weekly_usage->json();

        $user_monthly_usage = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/month/user/'.$currentUser);
        $user_monthly_usage = (array) $user_monthly_usage->json();

        $users_leaderboard = Http::withToken($token)->get(config('app.API_URL').'/ai/spend/month');
        $users_leaderboard = (array) $users_leaderboard->json();
                
        return Inertia::render('DashboardAdmin', [
            'users' => $users, 
            'user_daily_usage' => $user_daily_usage, 
            'user_weekly_usage' => $user_weekly_usage,
            'user_monthly_usage' => $user_monthly_usage,
            'users_leaderboard' => $users_leaderboard,
            'currentUser' => (int) $currentUser,
        ]);
    }

    public function RangeUsage(Request $request){
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'start_date and end_date are required'], 400);
        }

    $rangeUsage = Http::withToken(session('token'))->get(config('app.API_URL').'/ai/spend/period/daily/user/'
        . '?start_date=' . $startDate . '&end_date=' . $endDate);

    return response() -> json((array) $rangeUsage->json());
    }

    public function RangeUsageAdmin(Request $request, $currentUser=2){
        $currentUser = $request->query('current_user', 2);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'start_date and end_date are required'], 400);
            }

        $rangeUsage = Http::withToken(session('token'))->get(config('app.API_URL').'/ai/spend/period/daily/user/'.$currentUser
            . '?start_date=' . $startDate . '&end_date=' . $endDate);
        return response() -> json((array) $rangeUsage->json());
    }

    public function Login(Request $request, DashboardApiService $api){
        if(!$request->isMethod('post')) {
            return Inertia::render('Login', [
                'token' => null,
                'errorMessage' => null, 
            ]);
        }

        $validated = $request->validate([
            'name'    => 'required|string',
            'password' => 'required|string',
        ]);
        
        $result = $api->login($validated['name'], $validated['password']);

        if(!$result['ok']){
            return Inertia::render('Login', [
                'token' => null,
                'errorMessage' => $result['message'] ?? 'Failed to retrieve token from API.',
            ]);
        }

        session([
            'token' => $result['token'],
            'role' => $result['role'],
        ]);

        return match ($result['role']){
            'admin' => redirect('/dashboard-admin'),
            'trainee' => redirect('/dashboard'),
            default => Inertia::render('Login', [
                'token' => null,
                'errorMessage' => 'Logged in, but role'.$result['role'].'is not recognized.',
            ]),
        };
    }

    public function ChatBot(Request $request)
    {
        $validated = $request->validate([
            'question'    => 'required|string',
            'faqRejected' => 'sometimes|boolean',
        ]);

        $chatbot = Http::withToken(session('token')) //naar dashboardapiservice
            ->post(config('app.API_URL').'/nan', [
                'question'    => $validated['question'],
                'faqRejected' => $validated['faqRejected'] ?? false,
            ]);

        Log::info('Nan workflow status: ' . $chatbot->status());
        Log::info('Nan workflow body: ' . $chatbot->body());
        $chatbot = (array) $chatbot->json();

        return response()->json($chatbot);
    }
}


