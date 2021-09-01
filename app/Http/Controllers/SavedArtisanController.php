<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SavedArtisan;
;

class SavedArtisanController extends Controller
{
    function index()
    {
        // return User::all();
    }

    
    function show(Request $request)
    {
        $user = auth()->user();
        $savedArtisans = SavedArtisan::where('user_id',$user->id)->get();
        
        #Get Users from the Gotten Ids
        foreach($savedArtisans as $savedArtisan){
            $users[] = User::find($savedArtisan->artisan_id);
        }

        $response = [
            'savedArtisan' => $users,
            'message' => "Saved Artisans"
        ];
        return response($response, 201);
    }


    function search(Request $request)
    {
        
    }


    #Add Saved Artisan
    function store(Request $request)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'artisan_id' => 'integer|required|not_in:'.$user->id,
        ]);

        $alreadySavedArtisans = SavedArtisan::where('user_id',$user->id)->get();

        foreach($alreadySavedArtisans as $aSavedArtisan)
        {
            if($aSavedArtisan->artisan_id == $fields['artisan_id'])
            {
                
                $response = [
                    'message' => "User Have been Saved Already"
                ];
                return response($response, 422);
                die();
            }
        }

        SavedArtisan::create([
            'user_id' => $user->id,
            'artisan_id' => $fields['artisan_id'],
        ]);

        $response = [
            'message' => "User Have been Saved Sucessfully"
        ];
        return response($response, 201);

    }

    function delete(Request $request,$id)
    {
        if(!SavedArtisan::find($id))
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }

        SavedArtisan::find($id)->delete();
        
        $response = [
            'message' => "Deleted Successfully"
        ];
        return response($response, 201);
    }
}


