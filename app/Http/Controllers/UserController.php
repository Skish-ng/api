<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function index()
    {
        //return User::all();
    }

    function show(Request $request)
    {
        //dd($request->input('q'));
        $column = DB::select("SHOW COLUMNS FROM skish.users;");
        $column = array_diff($column,["password","role","token","email_verified_at","documents","passport","created_at","updated_at"]);
<<<<<<< HEAD

        //dd($column);
        $array = [];
        for($x = 0;$x < count($column);$x++){
            $col = (array)$column[$x];
            //dd($col);
=======
        dd($column);

        $array = [];
        for($x = 0;$x < count($column);$x++){

            $col = (array)$column[$x];
>>>>>>> refs/remotes/origin/main
            $colname = $col["Field"];
            print_r($colname);
            $user = DB::select("SELECT * FROM users WHERE ".$colname." LIKE '%similique'");

<<<<<<< HEAD
            print_r($user);
            //dd($colname);
        }
        // $user = User::query("SELECT * FROM users WHERE fullname LIKE '%a'");
        //dd($column);
=======

        }
>>>>>>> refs/remotes/origin/main
    }

    function store(Request $request)
    {
        //extract($request->input);
        $user = DB::table('users')->insert([
            'username' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('email'),
            'state' => 0,
            'token' => 0,
        ]);
        dd($user);

        dd(('name'));
    }

    function update(Request $request, User $user)
    {

    }

    function delete(User $user)
    {

    }
}

