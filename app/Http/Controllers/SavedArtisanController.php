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

        return $users;
    }


    function search(Request $request)
    {
        // $fields = $request->validate([
        //     'q' => 'required',
        // ]);
        // extract($fields);
        
        // $user = auth()->user();
        // $users = $this->show($request);
        
        
        // //Check if query is not empty
        // foreach($users as $auser){
        //     $search_user = User::find($auser->id);
        //     die($saerch_user);
        // }

        // die();
    }


    #Add Saved Artisan
    function store(Request $request)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'artisan_id' => 'integer|required|not_in:'.$user->id,
        ]);

        $alreadySavedArtisans = $this->show($request);

        foreach($alreadySavedArtisans as $aSavedArtisan){
            if($aSavedArtisan->artisan_id = $fields['artisan_id'])
                die("User Have been Saved Already");
        }

        SavedArtisan::create([
            'user_id' => $user->id,
            'artisan_id' => $fields['artisan_id'],
        ]);

        die(json_encode([1,"Successfully Added"]));

    }

    function delete(Request $request,$id)
    {
        if(!SavedArtisan::find($id)){
            die("Object does not exist");
        }
        SavedArtisan::find($id)->delete();
        die(json_encode([1,"Successfully Deleted"]));
    }
}


