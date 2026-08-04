<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use inertia\inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ViewController extends Controller
{
    public function Dashboard(Request $request ){
        $currentUser = 2;
        $today = date('Y-m-d');


        $users = Http::get('http://127.0.0.1:9000/api/users');
        $users = (array) $users->json();

        $user_daily_usage = Http::get('http://127.0.0.1:9000/api/ai/spend/period/user/'.$currentUser.'?start_date='.$today.'&end_date='.$today);
        $user_daily_usage = (array) $user_daily_usage->json();

        $user_weekly_usage = Http::get('http://127.0.0.1:9000/api/ai/spend/week/user/'.$currentUser);
        $user_weekly_usage = (array) $user_weekly_usage->json();

        $user_monthly_usage = Http::get('http://127.0.0.1:9000/api/ai/spend/month/user/'.$currentUser);
        $user_monthly_usage = (array) $user_monthly_usage->json();

        $users_leaderboard = Http::get('http://127.0.0.1:9000/api/ai/spend/month');
        $users_leaderboard = (array) $users_leaderboard->json();
                
        return Inertia::render('Dashboard', [
            'users' => $users, 
            'user_daily_usage' => $user_daily_usage, 
            'user_weekly_usage' => $user_weekly_usage,
            'user_monthly_usage' => $user_monthly_usage,
            'users_leaderboard' => $users_leaderboard
        ]);
    }

    public function RangeUsage(Request $request){
        $currentUser = 2; //hardcoded for now
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'start_date and end_date are required'], 400);
        }

    $rangeUsage = Http::get('http://127.0.0.1:9000/api/ai/spend/period/daily/user/'.$currentUser
        . '?start_date=' . $startDate . '&end_date=' . $endDate);

    return json_encode((array) $rangeUsage->json());
    }

    public function Faq(Request $request){
        $faqs = Http::get('http://127.0.0.1:9000/api/faq');
        $faqs = (array) $faqs->json();

        return Inertia::render('Faq', [
            'faqs' => $faqs
        ]);
    }

    public function Login(Request $request){
        if($request -> isMethod('post')){
            $validated = $request->validate([
                'name'    => 'required|string',
                'password' => 'required|string',
            ]);

            $login = Http::post('http://127.0.0.1:9000/api/login', [
                'name' => $validated['name'],
                'password' => $validated['password']
            ]);

            $login = (array) $login->json();
            session(['token' => $login['token'] ?? null]);

            Log::info('Session Token: ' . session('token'));

            if (!session('token')) {
                return Inertia::render('Login', [
                    'token' => null,
                    'errorMessage' => 'Failed to retrieve token from API.'
                ]);
            }

            return redirect('/dashboard');
        }

        return Inertia::render('Login', [
            'token' => null,
            'errorMessage' => null
        ]);
    }


    public function ChatBot(Request $request)
    {
        $validated = $request->validate([
            'question'    => 'required|string',
            'faqRejected' => 'sometimes|boolean',
        ]);

        $chatbot = Http::withToken(session('token')) 
            ->post('http://127.0.0.1:9000/api/nan', [
                'question'    => $validated['question'],
                'faqRejected' => $validated['faqRejected'] ?? false,
            ]);

        Log::info('Nan workflow status: ' . $chatbot->status());
        Log::info('Nan workflow body: ' . $chatbot->body());
        $chatbot = (array) $chatbot->json();

        return response()->json($chatbot);
    }
}


