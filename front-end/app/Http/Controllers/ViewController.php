<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use inertia\inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ViewController extends Controller
{
    public function Dashboard(Request $request){
        $currentUser = 2;
        $today = date('Y-m-d');
        $token = session('token');

        $users = Http::withToken($token)->get('http://127.0.0.1:9000/api/users');
        $users = (array) $users->json();

        $user_daily_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/period/user/?start_date='.$today.'&end_date='.$today);
        $user_daily_usage = (array) $user_daily_usage->json();

        $user_weekly_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/week/user/');
        $user_weekly_usage = (array) $user_weekly_usage->json();

        $user_monthly_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/month/user/');
        $user_monthly_usage = (array) $user_monthly_usage->json();

        $users_leaderboard = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/month');
        $users_leaderboard = (array) $users_leaderboard->json();

        Log::info('Session Token: ' . session('token'));
        Log::info('Session Role: ' . session('role')); 
        Log::info('Data: ' . json_encode($user_daily_usage));
                
                
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


        $users = Http::withToken($token)->get('http://127.0.0.1:9000/api/users');
        $users = (array) $users->json();

        $user_daily_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/period/user/'.$currentUser.'?start_date='.$today.'&end_date='.$today);
        $user_daily_usage = (array) $user_daily_usage->json();

        $user_weekly_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/week/user/'.$currentUser);
        $user_weekly_usage = (array) $user_weekly_usage->json();

        $user_monthly_usage = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/month/user/'.$currentUser);
        $user_monthly_usage = (array) $user_monthly_usage->json();

        $users_leaderboard = Http::withToken($token)->get('http://127.0.0.1:9000/api/ai/spend/month');
        $users_leaderboard = (array) $users_leaderboard->json();

        
        Log::info('Session Token: ' . session('token'));
        Log::info('Session Role: ' . session('role')); 
        Log::info('Data: ' . json_encode($user_daily_usage));
                
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

    $rangeUsage = Http::withToken(session('token'))->get('http://127.0.0.1:9000/api/ai/spend/period/daily/user/'
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

        $rangeUsage = Http::withToken(session('token'))->get('http://127.0.0.1:9000/api/ai/spend/period/daily/user/'.$currentUser
            . '?start_date=' . $startDate . '&end_date=' . $endDate);
        return response() -> json((array) $rangeUsage->json());
    }


    public function Faq(Request $request){
        $faqs = Http::withToken(session('token'))->get('http://127.0.0.1:9000/api/faq');
        $faqs = (array) $faqs->json();

        return Inertia::render('Faq', [
            'faqs' => $faqs
        ]);
    }

        public function FaqAdmin(Request $request){

        $faqs = Http::withToken(session('token'))->get('http://127.0.0.1:9000/api/faq');
        $faqs = (array) $faqs->json();

        // $faqCreate =  Http::withToken(session('token'))->post('http://127.0.0.1:9000/api/faq/create');
        // $faqCreate = $faqCreate->json();

        // $faqUpdate = Http::withToken(session('token'))->put('http://127.0.0.1:9000/api/faq/update/'.$id);
        // $faqUpdate = $faqUpdate->json();

        // $faqDelete = Http::withToken(session('token'))->delete('http://127.0.0.1:9000/api/faq/delete/'.$id);
        // $faqDelete = $faqDelete->json();

        return Inertia::render('FaqAdmin', [
            'faqs' => $faqs,
            // 'faqUpdate' => $faqUpdate,
            // 'faqDelete' => $faqDelete
        ]);
    }

    public function FaqDelete(Request $request, $id = null){
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put("http://127.0.0.1:9000/api/faq/delete/{$id}");

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqActivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put("http://127.0.0.1:9000/api/faq/activate/{$id}");

        return response()->json((array) $response->json(), $response->status());
    }

    public function FaqDeactivate(Request $request, $id = null)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required'], 400);
        }

        $response = Http::withToken(session('token'))
            ->put("http://127.0.0.1:9000/api/faq/deactivate/{$id}");

        $body = $response->json() ?? [];

        return response()->json($body, $response->status());
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
            session(['role' => $login['role'] ?? null]);

            Log::info('Session Token: ' . session('token'));
            Log::info('Session Role: ' . session('role')); 
            
            if (!session('token')) {
                return Inertia::render('Login', [
                    'token' => null,
                    'errorMessage' => 'Failed to retrieve token from API.'
                ]);
            }

            $role = session('role');

            if ($role === "admin"){
                return redirect('/dashboard-admin');
            }
            if ($role === "trainee"){
                return redirect('/dashboard');
            }
            return Inertia::render('Login', [
                'token' => null,
                'errorMessage' => "Logged in, but role ".$role." is not recognized."
            ]);
            
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


