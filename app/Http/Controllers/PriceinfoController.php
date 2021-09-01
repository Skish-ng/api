<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Priceinfo;

class PriceinfoController extends Controller
{
    function index(Request $request)
    {
        $user = auth()->user();

        $response = [
            'price_info' => Priceinfo::where('user_id',$user->id)->get(),
            'message' =>"My Priceinfo",
        ];
        return response($response, 201);
    }

    

    #Add PRICE INFO
    function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'min' => 'required|Integer',
            'max' => 'required|Integer',
            'desc' => 'required|string',
        ]);


        $Priceinfo = Priceinfo::create([

            'user_id' => $user->id,
            'dec' => $request->desc,
            'min' => $request->min,
            'max' => $request->max,

        ]);

        $response = [
            'message' =>"Price info Added Sucessfully",
        ];
        return response($response, 201);

    }

    function update(Request $request,$id)
    {
        $user = auth()->user();
        $request->validate(
            [
                'min' => 'Integer',
                'max' => 'Integer',
                'desc' => 'string',
            ]
        );

        $Priceinfo = Priceinfo::find($id);

        if(!$Priceinfo || $Priceinfo->user_id != auth()->user()->id)
        {
            return response([ 'message' => "Object not Accessible"], 422);
        }
        $Priceinfo->update(
            [
                'min' => $request->min ?? $Priceinfo->min,
                'max' => $request->max ?? $request->max,
                'desc' => $request->desc ?? $Priceinfo->desc,
            ]
        );

        $response = [
            'message' => "Price info Updated"
        ];
        return response($response, 201);

    }

    function delete(Request $request,$id)
    {
        $Priceinfo = Priceinfo::find($id);
        if(!$Priceinfo || $Priceinfo->user_id != auth()->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $Priceinfo->delete();

        $response = [
            'message' => "Price Info deleted"
        ];
        return response($response, 201);
    }
}



