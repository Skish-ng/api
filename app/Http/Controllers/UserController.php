<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index()
    {
        //return User::all();
    }

    
    function show(Request $request)
    {

    }

    function search(Request $request)
    {
        if(!$request->input('q'))
            die(json_encode([0,"no search request"]));

        $columns = ["email","fullname","username","whatsapp","tagline","tel","address","pricing"];
        $temp = [];

        foreach($columns as $colname){
            
            $user = DB::select("SELECT * FROM users WHERE ".$colname." LIKE '%".$request->input("q")."%'");

        //    if($user != null)
        //         print_r($user);
        
            foreach($user as $userr){
                $users[] = (Array)$userr;
            }
            
        }

        foreach($users as $user)
            print_r($user);

    }

    function store(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $user = User::where('email',$fields['email'])->orwhere('username',$fields['email'])->get()->first();

        if(!user || Hash::check($fields['password'], $user->password))
            return(response([0,"incorrect user or password"]));
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);
        
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'state' => 1
        ]);

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [1,'Logged out'];
    }

    function update(Request $request, User $user)
    {

    }

    function delete(User $user)
    {

    }
}

