<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\Contracts\FetchServiceInterface;
use Carbon\Carbon;

class FetchService implements FetchServiceInterface
{
    public function fetchUsagePeriod($start_date = null, $end_date = null) 
    {
        if ($start_date == null) {
            $start_date = Carbon::now()->format('Y-m-d');
        }
        if ($end_date == null) {
            $end_date = Carbon::now()->format('Y-m-d');
        }

        //get list of teams
        $response = Http::withToken(config('app.API_KEY'))->get('https://ai.educom.nu/team/list');
        $teams = (array)$response->json();

        //loop through teams to get each trainee's ai usage data
        foreach ($teams as $team) {

            //retrieve AI usage data from LiteLLM API
            $response = Http::withToken(config('app.API_KEY'))->get('https://ai.educom.nu/team/daily/activity', [
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
        }
        return('succes');
    }

    public function fetchUsage()
    {
        return $this->fetchUsagePeriod();
    }
}
