<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Featured;

class FeaturedController extends Controller
{
    function index(Request $request)
    {
        $user = auth()->user();

        $response = [
            'featureds' => Featured::where('user_id',$user->id)->get(),
            'message' =>"My Featured",
        ];
        return response($response, 201);
    }

    
    function show(Request $request,$id)
    {
         $response = [
            'user' => $user,
            'featureds' => Featured::find($id),
            'message' =>"Feauterd by id",
        ];
        return response($response, 201);
    }


    #Add Saved Artisan
    function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = 0;
        
        if($request->hasfile('image')){

            $imageName = $user->username."_".$request->title;
            $file = $request->image->move(public_path('images/Featured'), $imageName);

        }

        if($file)
        {
            $featured = Featured::create([

                'user_id' => $user->id,
                'body' => $request->desc,
                'title' => $request->title,
                'image' => $file
      
            ]);

            $response = [
                'featureds' => $featured->refresh(),
                'message' =>"Featured Added Sucessfully",
            ];
            return response($response, 201);
        }
        else
        {
            $response = [
                'message' =>"Unknown Error occured",
            ];
            return response($response, 201);
        }
    }

    function update(Request $request,$id)
    {
        $user = auth()->user();
        $request->validate(
            [
                'title' => 'string',
                'body' => 'string',
                'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );
        
        $file = '';
        
        if($request->hasfile('image')){

            $imageName = $user->username."_".$request->title;
            $file = $request->image->move(public_path('images/Featured'), $imageName);

        }

        $featured = Featured::find($id);

        if(!$featured || $featured->user_id != auth()->user()->id){
            return response([ 'message' => "Object not Accessible"], 422);
        }
        $featured->update(
            [
                'title' => $request->title ?? $featured->title,
                'body' => $request->body ?? $featured->body,
                'image' => $file ?? $request->file,
            ]
        );

        $response = [
            'message' => "Feature UpdATE sUCCESFULL"
        ];
        return response($response, 201);

    }

    function delete(Request $request,$id)
    {
        $featured = Featured::find($id);
        if(!$featured || $featured->user_id != auth()->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $featured->delete();

        $response = [
            'message' => "Feature deleted"
        ];
        return response($response, 201);
    }
}



