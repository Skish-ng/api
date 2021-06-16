<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageViewController extends Controller
{
    <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PageViewController extends Controller
{
    function index()
    {
    }

    
    function show(Request $request)
    {
        

        $user = auth()->user();
        return PageView::where('user_id',$user->id)->get();
    }

    function search(Request $request)
    {
       
    }


    function store(Request $request)
    {
        $fields = $request->validate([
            'type' => 'string|required',
            'viewer' => 'string|required',
        ]);

        $view = PageView::create([
            'id' => $user->id,
            'type' => $fields['type'],
            'viewer' => $fields['viewer'],
        ]);


    }

    

}
