<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models;

class ApiController extends Controller
{
    public function AIusage()
    {
        $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/list');
        $teams = (array)$response->json();
        foreach ($teams as $team) {

            $response = Http::withToken(env('API_KEY'))->get('https://ai.educom.nu/team/daily/activity', [
                'team_ids' => $team['team_id'], 
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d')
                ]);
            $response = (array)$response->json();
            if(empty($response['results'])) {
                continue;
            }
            $data = $response['results'][0];
            
            $models = (array)$data['breakdown']['models'];
            foreach ($models as $key => $model) {
                foreach ($model['api_key_breakdown'] as $userData) {
                    
                    $user = \App\Models\User::where('key_alias', $userData['metadata']['key_alias'])->first();

                    if ($user !== null)
                    {
                        if (!\App\Models\AiUsage::where('user_id', $user->id)->where('model', $key)->where('date', $data['date'])->exists())
                        {
                            $user->AiUsage()->create([
                                'date' => $data['date'],
                                'model' => $key,
                                'spend' => $userData['metrics']['spend'], 
                                'tokens' => $userData['metrics']['total_tokens']
                                ]);
                        } else 
                        {
                            \App\Models\AiUsage::where('user_id', $user->id)
                                                ->where('model', $key)
                                                ->where('date', $data['date'])
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
     

    public function showAIusage() 
    {
        //return \App\Models\User::latest()->take(5)->get()->toJson(JSON_PRETTY_PRINT);
        return \App\Models\AiUsage::where('user_id', 1)->latest()->take(5)->get()->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
