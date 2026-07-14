<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models;

class ApiController extends Controller
{
    public function AiUsage()
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
    public function getAiUsage() 
    {
        return \App\Models\AiUsage::latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data of the specified user
    */
    public function getUserAiUsage(string $id) 
    {
        return \App\Models\AiUsage::where('user_id', $id)->latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all Users
    */
    public function getUsers() 
    {
        return \App\Models\User::latest()->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data over a given time period
    */
    public function getAiUsagePeriod(Request $request)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));
        return \App\Models\AiUsage::whereBetween('date', [$start_date, $end_date])->get()->toJson(JSON_PRETTY_PRINT);
    }

    /*
    * Returns all AI usage data of the specified user over a given time period
    */
    public function getUserAiUsagePeriod(Request $request, string $id)
    {
        $start_date = $request->input('start_date', date('Y-m-d'));
        $end_date = $request->input('end_date', date('Y-m-d'));
        return \App\Models\AiUsage::where('user_id', $id)->whereBetween('date', [$start_date, $end_date])->get()->toJson(JSON_PRETTY_PRINT);
    }


    //TODO make migration to replace this, for testing purposes only
    public function AddMe() {
        //puts me in the database for testing, do not call if already present in database
        $col = \App\Models\User::create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'email' => 'paulhoi541@gmail.com',
            'key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678']);
        dd($col);
    }
}
