<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 

class PageViewController extends Controller
{
    function index()
    {
    }

    
    function show(Request $request)
    {}
        
    function clickCounts(Request $request)
    {
        $user = auth()->user();

       $profile = PageView::where('type','profile')->where('user_id',$user->id)->orderby('id', 'DESC')->get();
       $tel = PageView::where('type','tel')->where('user_id',$user->id)->orderby('id', 'DESC')->get();
       $whatsapp = PageView::where('type','whatsapp')->where('user_id',$user->id)->orderby('id', 'DESC')->get();
      
       $response = [
            'profile' => $profile,
            'tel' => $tel,
            'whatsapp' => $whatsapp,
            'message' => "Total view and clicks"
       ];
       return response($response, 201);
       
    }

    function todayClick(Request $request)
    {
        $user = auth()->user();

        $profile = PageView::whereDate('created_at', Carbon::today())->where('type','profile')->where('user_id',$user->id)->get();
        $tel = PageView::whereDate('created_at', Carbon::today())->where('type','tel')->where('user_id',$user->id)->get();
        $whatsapp = PageView::whereDate('created_at', Carbon::today())->where('type','whatsapp')->where('user_id',$user->id)->get();

        $response = [
                'profile' => $profile,
                'tel' => $tel,
                'whatsapp' =>$whatsapp,
                'message' => "Today's view and clicks"
            ];
        return response($response, 201);
    }


    function store(Request $request)
    {
        $user = auth()->user();

        $fields = $request->validate([
            'type' => 'string|required',
            'viewed' => 'string|required',
            'location' => 'string',
        ]);

        $viewer = User::find(@$fields['viewed']);

        $view = PageView::create([
            'user_id' => $fields['viewed'],
            'type' => $fields['type'],
            'viewer' => @$user->username ?? $_SERVER['HTTP_USER_AGENT']."|".@$fields['location'],
        ]);

        $response = [
                'message' => true,
        ];
        return response($response, 201);

    }

    

}
