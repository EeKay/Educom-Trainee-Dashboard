<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class UsageController extends Controller
{
    public function fetchUsagePeriod(Request $request) 
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));

        //get list of teams
        $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/list');
        $teams = (array)$response->json();

        //loop through teams to get each trainee's ai usage data
        foreach ($teams as $team) {

            //retrieve AI usage data from LiteLLM API
            $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/daily/activity', [
                'team_ids' => $team['team_id'], 
                'start_date' => $start_date,
                'end_date' => $end_date,
                'page_size' => 10000
                ]);
            $response = (array)$response->json();

            //If team has no usage data skip to next team
            if(empty($response['results'])) {
                continue;
            }

            foreach ($response['results'] as $data) {

                //get date associated with usage data
                $date = date($data['date']);
                    
                //get all models that were used
                $models = (array)$data['breakdown']['models'];
                foreach ($models as $key => $model) {
                    foreach ($model['api_key_breakdown'] as $userData) {
                            
                        //retrieve user from database
                        $user = \App\Models\User::where('key_alias', $userData['metadata']['key_alias'])->first();

                        //create entry if not yet present, otherwise update entry
                        if ($user !== null)
                        {
                            if (!\App\Models\Usage::where('user_id', $user->id)->where('model', $key)->where('date', $date)->exists())
                            {
                                $user->Usage()->create([
                                    'date' => $date,
                                    'model' => $key,
                                    'spend' => $userData['metrics']['spend'], 
                                    'tokens' => $userData['metrics']['total_tokens']
                                    ]);
                            } else 
                            {
                                \App\Models\Usage::where('user_id', $user->id)
                                                    ->where('model', $key)
                                                    ->where('date', $date)
                                                    ->update([
                                                        'spend' => $userData['metrics']['spend'], 
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
        }
        return('succes');
    }
    
    public function fetchUsage()
    {
        //get list of teams
        $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/list');
        $teams = (array)$response->json();

        //loop through teams to get each trainee's ai usage data
        foreach ($teams as $team) {

            //retrieve AI usage data from LiteLLM API
            $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/daily/activity', [
                'team_ids' => $team['team_id'], 
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d')
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
                    $user = \App\Models\User::where('key_alias', $userData['metadata']['key_alias'])->first();

                    //create entry if not yet present, otherwise update entry
                    if ($user !== null)
                    {
                        if (!\App\Models\AiUsage::where('user_id', $user->id)->where('model', $key)->where('date', $date)->exists())
                        {
                            $user->AiUsage()->create([
                                'date' => $date,
                                'model' => $key,
                                'spend' => $userData['metrics']['spend'], 
                                'tokens' => $userData['metrics']['total_tokens']
                                ]);
                        } else 
                        {
                            \App\Models\AiUsage::where('user_id', $user->id)
                                                ->where('model', $key)
                                                ->where('date', $date)
                                                ->update([
                                                    'spend' => $userData['metrics']['spend'], 
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
    * Returns all AI usage data
    */
    public function getUsage() 
    {
        return \App\Models\AiUsage::latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data of the specified user
    * params
    * int id
    */
    public function getUserUsage(string $id) 
    {
        return \App\Models\AiUsage::where('user_id', $id)->latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data over a given time period
    * params
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getUsagePeriod(Request $request)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));
        return \App\Models\AiUsage::whereBetween('date', [$start_date, $end_date])->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data of the specified user over a given time period
    * params
    * int id
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getUserUsagePeriod(Request $request, string $id)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));
        return \App\Models\AiUsage::where('user_id', $id)->whereBetween('date', [$start_date, $end_date])->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns total spend and tokens used of every user
    */
    public function getTotalSpend()
    {
        $users = \App\Models\User::get();
        $result = array();
        foreach ($users as $user) {
            $id = $user->id;
            $name = $user->name;
            $spend = $user->AiUsage()->sum('spend');
            $tokens = $user->AiUsage()->sum('tokens');
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
        $spend = $user[0]->AiUsage()->sum('spend');
        $tokens = $user[0]->AiUsage()->sum('tokens');

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens]);
    }

    /*
    * Returns total spend and tokens used of every user over given period
    * params
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getTotalSpendPeriod(Request $request)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));

        $users = \App\Models\User::get();
        $result = array();
        foreach ($users as $user) {
            $id = $user->id;
            $name = $user->name;
            $spend = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
            $tokens = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');
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
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));

        $user = \App\Models\User::where('id', $id)->get();
        $user_id = $user[0]->id;
        $name = $user[0]->name;
        $spend = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
        $tokens = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens]);
    }

    /*
    * Returns total spend and tokens used of specified user over given period in daily intervals
    * params
    * int id
    * string start_date : YYYY-MM-DD
    * string end_date : YYYY-MM-DD
    */
    public function getUserSpendPeriodDaily(Request $request, string $id)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));


        $period = new \Carbon\CarbonPeriod($start_date, $end_date);

        $results = [];
        
        foreach ($period as $date)
        {
            $user = \App\Models\User::where('id', $id)->get();
            $user_id = $user[0]->id;
            $name = $user[0]->name;
            $spend = $user[0]->Usage()->where('date', $date->format('Y-m-d'))->sum('spend');
            $tokens = $user[0]->Usage()->where('date', $date->format('Y-m-d'))->sum('tokens');
            $results[] = ['user_id' => $user_id, 'name' => $name, 'date' => $date->format('Y-m-d'), 'spend' => $spend, 'tokens' => $tokens];
        }
       


       return json_encode($results);
    }


    /*
    * Returns total spend and tokens used of every user over this month
    */
    public function getTotalSpendMonth()
    {
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = date('Y-m-d');

        $users = \App\Models\User::get();
        $result = array();
        foreach ($users as $user) {
            $id = $user->id;
            $name = $user->name;
            $spend = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
            $tokens = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');
            $result[] = ['user_id' => $id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens];
            
        }
        return json_encode($result);
    }

    /*
    * Returns total spend and tokens used of specfied user over this month
    * params
    * int id
    */
    public function getUserSpendMonth(string $id)
    {
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = date('Y-m-d');

        $user = \App\Models\User::where('id', $id)->get();
        $user_id = $user[0]->id;
        $name = $user[0]->name;
        $spend = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
        $tokens = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens]);
    }

    /*
    * Returns total spend and tokens used of every user over this week
    */
    public function getTotalSpendWeek()
    {
        $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_date = date('Y-m-d');

        $users = \App\Models\User::get();
        $result = array();
        foreach ($users as $user) {
            $id = $user->id;
            $name = $user->name;
            $spend = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
            $tokens = $user->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');
            $result[] = ['user_id' => $id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens];
            
        }
        return json_encode($result);
    }

    /*
    * Returns total spend and tokens used of specfied user over this week
    * params
    * int id
    */
    public function getUserSpendWeek(string $id)
    {

        $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_date = date('Y-m-d');

        $user = \App\Models\User::where('id', $id)->get();
        $user_id = $user[0]->id;
        $name = $user[0]->name;
        $spend = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('spend');
        $tokens = $user[0]->AiUsage()->whereBetween('date', [$start_date, $end_date])->sum('tokens');

       return json_encode(['user_id' => $user_id, 'name' => $name, 'spend' => $spend, 'tokens' => $tokens]);
    }


}
