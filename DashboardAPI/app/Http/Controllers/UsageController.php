<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Services\Contracts\FetchServiceInterface;

class UsageController extends Controller
{
    protected $fetchService;

    public function __construct(FetchServiceInterface $fetchService)
    {
        $this->fetchService = $fetchService;
    }

    public function fetchUsagePeriod(Request $request) 
    {
        $start_date = $request->input('start_date', null);
        $end_date = $request->input('end_date', null);

        return $this->fetchService->fetchUsagePeriod($start_date, $end_date);
    }

    public function fetchUsage()
    {
        return $this->fetchService->fetchUsage();
    }
    
    public function ffetchUsage()
    {
        //get list of teams
        $response = Http::withToken(config('app.API_KEY'))->get('https://ai.educom.nu/team/list');
        $teams = (array)$response->json();

        //loop through teams to get each trainee's ai usage data
        foreach ($teams as $team) {

            //retrieve AI usage data from LiteLLM API
            $response = Http::withToken(config('app.API_KEY'))->get('https://ai.educom.nu/team/daily/activity', [
                'team_ids' => $team['team_id'], 
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
                ]);
            $response = (array)$response->json();

            //If team has no usage data skip to next team
            if(empty($response['results'])) {
                continue;
            }
            $data = $response['results'][0];
            
            //get date associated with usage data
            $date = date($data['date']);
            
            //get all models that were used
            $models = (array)$data['breakdown']['models'];
            foreach ($models as $key => $model) {
                foreach ($model['api_key_breakdown'] as $userData) {
                    
                    //retrieve user from database
                    $user = \App\Models\User::where('litellm_key_alias', $userData['metadata']['key_alias'])->first();

                    //create entry if not yet present, otherwise update entry
                    if ($user !== null)
                    {
                        if (!\App\Models\Usage::where('user_id', $user->id)->where('model', $key)->where('date', $date)->exists())
                        {
                            $user->Usage()->create([
                                'date' => $date,
                                'model' => $key,
                                'spend' => round($userData['metrics']['spend'], 5, PHP_ROUND_HALF_UP), 
                                'tokens' => $userData['metrics']['total_tokens']
                                ]);
                        } else 
                        {
                            \App\Models\Usage::where('user_id', $user->id)
                                                ->where('model', $key)
                                                ->where('date', $date)
                                                ->update([
                                                    'spend' => round($userData['metrics']['spend'], 5, PHP_ROUND_HALF_UP), 
                                                    'tokens' => $userData['metrics']['total_tokens']
                                                ]);
                        }
                    } else
                    {
                        //TODO user not found
                    }
                }
            }
        }
        return('succes');
    }

    /*
    * Returns total spend and tokens used of every user
    */
    public function getTotalSpend()
    {
        $users = \App\Models\User::where('role', 'trainee')->get();
        $result = array();
        foreach ($users as $user) {
                $id = $user->id;
                $name = $user->name;
                $spend = round($user->Usage()->sum('spend'), 5, PHP_ROUND_HALF_UP);
                $tokens = $user->Usage()->sum('tokens');
                $result[] = ['user_id' => $id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens];
        }
        return json_encode($result);
    }

    /*
    * Returns total spend and tokens used of specified user
    * params
    * int id
    */
    public function getUserSpend(string $id)
    {
        $user = \App\Models\User::where('id', $id)->get();

        $user_id = $user[0]->id;
        $name = $user[0]->name;
        $spend = round($user[0]->Usage()->sum('spend'), 5, PHP_ROUND_HALF_UP);
        $tokens = $user[0]->Usage()->sum('tokens');

        $models = $user[0]->Usage()->get('model');
        $modelData = [];
        $all_models = [];
        foreach ($models as $model)
        {
            $model = $model['model'];
            $modelSpend = round($user[0]->Usage()->where('model', $model)->sum('spend'), 5, PHP_ROUND_HALF_UP);
            $modelTokens = $user[0]->Usage()->where('model', $model)->sum('tokens');
            $modelData[$model] = ['spend' => $modelSpend, 'tokens' => $modelTokens];

            if (!in_array($model, $all_models))
            {
                $all_models[] = $model;
            }
        }
        

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens, 'models' => $all_models, 'data' => $modelData]);
    }

    /*
    * Returns total spend and tokens used of specified user
    */
    public function getCurrentUserSpend(Request $request)
    {
        $id = $request->user()->id;

        return $this->getUserSpend($id);
    }

    /*
    * Returns total spend and tokens used of every user over given period
    * params
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getTotalSpendPeriod(Request $request)
    {
        $start_date = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $users = \App\Models\User::where('role', 'trainee')->get();
        $result = array();
        foreach ($users as $user) {
            $id = $user->id;
            $name = $user->name;
            $spend = round($user->Usage()->whereBetween('date', [$start_date, $end_date])->sum('spend'), 5, PHP_ROUND_HALF_UP);
            $tokens = $user->Usage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');
            $result[] = ['user_id' => $id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens];
            
        }
        return json_encode($result);
    }

    /*
    * Returns total spend and tokens used of specified user over given period
    * params
    * int id
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getUserSpendPeriod(Request $request, string $id)
    {
        $start_date = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $user = \App\Models\User::where('id', $id)->get();
        $user_id = $user[0]->id;
        $name = $user[0]->name;
        $spend = round($user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->sum('spend'), 5, PHP_ROUND_HALF_UP);
        $tokens = $user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');

        $models = $user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->get('model');
        $modelData = [];
        $all_models = [];
        foreach ($models as $model)
        {
            $model = $model['model'];
            $modelSpend = round($user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->where('model', $model)->sum('spend'), 5, PHP_ROUND_HALF_UP);
            $modelTokens = $user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->where('model', $model)->sum('tokens');
            $modelData[$model] = ['spend' => $modelSpend, 'tokens' => $modelTokens];

            if (!in_array($model, $all_models))
            {
                $all_models[] = $model;
            }
        }

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens, 'models' => $all_models, 'data' => $modelData]);
    }

    /*
    * Returns total spend and tokens used of specified user over given period
    * params
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getCurrentUserSpendPeriod(Request $request)
    {
        $id = $request->user()->id;

        return $this->getUserSpendPeriod($request, $id);    
    }

    /*
    * Returns total spend and tokens per model used of specified user over given period in daily intervals
    * params
    * int id
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getUserSpendPeriodDaily(Request $request, string $id)
    {
        $start_date = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->format('Y-m-d'));


        $period = new \Carbon\CarbonPeriod($start_date, $end_date);

        $all_models = [];
        foreach ($period as $date) 
        {
            $user = \App\Models\User::where('id', $id)->get();
            $models = $user[0]->Usage()->whereBetween('date', [$start_date, $end_date])->get('model');
            $data = [];
            foreach ($models as $model)
            {   
                $model = $model['model'];
                $spend = round($user[0]->Usage()->where('date', $date->format('Y-m-d'))->where('model', $model)->sum('spend'), 5, PHP_ROUND_HALF_UP);
                $tokens = $user[0]->Usage()->where('date', $date->format('Y-m-d'))->where('model', $model)->sum('tokens');
                $data[$model] = ['spend' => $spend, 'tokens' => $tokens];
                if (!in_array($model, $all_models))
                {
                    $all_models[] = $model;
                }
            }
            $results[] = ['date' => $date->format('Y-m-d'), 'data' => $data];
        }
        $output['models'] = $all_models;
        $output['results'] = $results;

       return json_encode($output);
    }

    /*
    * Returns total spend and tokens per model used of current user over given period in daily intervals
    * params
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getCurrentUserSpendPeriodDaily(Request $request)
    {
        $id = $request->user()->id;

        return $this->getUserSpendPeriodDaily($request, $id);
    }

    /*
    * Returns total spend and tokens used of every user over this month
    */
    public function getTotalSpendMonth()
    {
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');

        $request = new Request();
        $request->merge(['start_date' => $start_date]);
        return $this->getTotalSpendPeriod($request);
    }

    /*
    * Returns total spend and tokens used of specfied user over this month
    * params
    * int id
    */
    public function getUserSpendMonth(string $id)
    {
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');

        $request = new Request();
        $request->merge(['start_date' => $start_date]);
        return $this->getUserSpendPeriod($request, $id);
    }

    /*
    * Returns total spend and tokens used of current user over this month
    */
    public function getCurrentUserSpendMonth(Request $request)
    {
        $id = $request->user()->id;

        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');

        $request->merge(['start_date' => $start_date]);
        return $this->getUserSpendPeriod($request, $id);
    }

    /*
    * Returns total spend and tokens used of every user over this week
    */
    public function getTotalSpendWeek()
    {
        $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');

        $request = new Request();
        $request->merge(['start_date' => $start_date]);
        return $this->getTotalSpendPeriod($request);
    }

    /*
    * Returns total spend and tokens used of specfied user over this week
    * params
    * int id
    */
    public function getUserSpendWeek(string $id)
    {
        $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
        
        $request = new Request();
        $request->merge(['start_date' => $start_date]);
        return $this->getUserSpendPeriod($request, $id);
    }

    /*
    * Returns total spend and tokens used of current user over this week
    */
    public function getCurrentUserSpendWeek(Request $request)
    {
        $id = $request->user()->id;

        $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');

        $request->merge(['start_date' => $start_date]);
        return $this->getUserSpendPeriod($request, $id);    
    }


}
