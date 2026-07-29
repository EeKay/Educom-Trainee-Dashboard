<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use inertia\inertia;

class ViewController extends Controller
{

//   const [users, setUsers] = useState([]);
//   //const [currentUser, setCurrentUser] = useState(null);
//   const currentUser = 1; //change later and change the calls to currentUser.id
//   const [monthlyStats, setMonthlyStats] = useState({tokens: 0, spend: 0});
//   const [dailyStats, setDailyStats] = useState({ tokens: 0, spend: 0 });
//   const [weeklyStats, setWeeklyStats] = useState({ tokens: 0, spend: 0 });
//   const [rangeStats, setRangeStats] = useState([]);
//   const [leaderboardUsers, setLeaderboardUsers] = useState([]);

    public function Dashboard(Request $request ){
        $currentUser = 1;
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
        $currentUser = 1; //hardcoded for now
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'start_date and end_date are required'], 400);
        }

    $rangeUsage = Http::get('http://127.0.0.1:9000/api/ai/spend/period/daily/user/'.$currentUser
        . '?start_date=' . $startDate . '&end_date=' . $endDate);

    return response()->json((array) $rangeUsage->json());
    }

    public function Faq(Request $request){
        $faqs = Http::get('http://127.0.0.1:9000/api/faq');
        $faqs = (array) $faqs->json();

        return Inertia::render('Faq', [
            'faqs' => $faqs
        ]);
    }

    public function ChatBot(Request $request){
        $chatbot = Http::get('http://127.0.0.1:9000/api/nan');
        $chatbot = (array) $chatbot->json();
    }
}


    // useEffect(() =>{
    //     fetch(`${API_BASE}/nan`, {
    //         method: 'POST',
    //         headers:{
    //             "Content-Type": "application/json",
    //             "accept" : "application/json",
    //             "Access-Control-Allow-Origin": "*",
    //             "Access-Control-Allow-Methods": "POST",
    //             "Authorization": "Bearer 463|H7ztAS1gkOUb4V5CYjHhTVtSFNSIbPyWDtvRxJjm549eebc2"//hardcoded for now
    //         },
    //         // credentials: "include",
    //         body: JSON.stringify({
    //             question: "question", 
    //             faqRejected: "false",
    //         })
    //     })
        
    //     .then(response => response.text())   
    //     .then(data => {
    //         console.log(data);
    //     })
    //     .catch(error => console.error ("error: ", error));

    // }, []);
