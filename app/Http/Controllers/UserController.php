<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index()
    {
        return User::all();
    }

    
    function show(Request $request, $id)
    {
        return User::find($id) ?? "User Not Found";
    }

    function search(Request $request)
    {
        //Check if query is not empty
        if(!$request->input('q'))
            die(json_encode([0,"no search request"]));

        $columns = ["email","fullname","username","whatsapp","tagline","tel","address","pricing"];
        $temp = [];

        #Search stated Columns
        foreach($columns as $colname){
            
            $user = DB::select("SELECT * FROM users WHERE ".$colname." LIKE '%".$request->input("q")."%'");

        //    if($user != null)
        //         print_r($user);
        
            foreach($user as $userr){
                $users[] = (Array)$userr;
            }
            
        }

        foreach($users as $user)
            print_r(json_encode($user));

        die();
    }


    #User Login
    function store(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $user = User::where('email',$fields['email'])->orwhere('username',$fields['email'])->get()->first();

        //if user does not existst
        if(!$user || !Hash::check($fields['password'], $user->password))
        {  

            return(response([0,"incorrect user or password"]));
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response, 404);
        }
        else
        {
            
            $token = $user->createToken('userToken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);
        }
    }

    function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|unique:users,email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);
        
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'state' => 1,
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

    function update(Request $request, $id)
    {
        print_r($request->file('image'));

        if($id != $request->user()->id){
            die(json_encode([0,"User not Authenticated"]));
        }

        $fields = $request->validate([
            'email' => 'string|unique:users,email',
            'username' => 'string|unique:users,username',
            'tagline' => 'string',
            'whatsapp' => 'string',
            'pricing' => 'string',
            'address' => 'string',
            'bio' => 'string',
            'old_password' => 'string',
            'password' => 'string|confirmed',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'passport' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' ,
        ]);

        extract($fields);
        $user = User::find($id);

        $user->username = @$username ?? $user->username;
        $user->fullname = @$fullname ?? $user->fullname;

        $user->tagline = @$tagline ?? $user->tagline;
        $user->whatsapp = @$whatsapp ?? $user->whatsapp;

        $user->address = @$address ?? $user->address;
        $user->bio = @$bio ?? $user->address;
        $user->pricing = @$pricing ?? $user->address;

        #PASSWORD

        if(@$old_password){
            if(Hash::check(@$old_password, $user->password)){
                $user->password = bcrypt($password);
                $user->save();
                die(json_encode([1,"password changed sucessfully"]));
            }
            else{
                die(json_encode([0,"incorrect old password"]));
            }
        }
        
        //FILE
        if($request->hasfile('image')){
            $imageName = $user->username.'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $user->dp = 'images/'.$imageName;
        }
       //$user = User::findbyToken($request);
        
       $user->save();
       $user->refresh();

       die($user);
    }

    function delete(User $user)
    {

    }

    
    function views(Request $request)
    {
    }
}

