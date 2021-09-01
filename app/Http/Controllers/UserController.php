<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\CanResetPassword;
use Carbon\Carbon; 
use Illuminate\Support\Str;
use App\Models\Featured;
use App\Models\Review;
use App\Models\Priceinfo;


class UserController extends Controller
{
    function index()
    {
        $response = [
            'allusers' => User::all(),
            'message' => "All registered user"
        ];
        return response($response, 201);
    }

    
    function show(Request $request, $id)
    {
        $request->validate([
            'location' => 'string'
        ]);
        // User::where('token','LIKE',"%".$request->bearerToken()."%")->get()
        echo Auth::check();
        
        $user = User::where('username',$id)->first() ?? User::where('id',$id)->first();
        $id = $user->id;
        
        if(!@$user)
        {
            $response = [
                'message' => "User does not exist",
            ];
            return response($response, 422);
        }
        
        if(@$viewer->id != $user->id)
        {
            PageView::create([
                'user_id' => $user->id,
                'type' => 'profile',
                'viewer' => $viewer->username ?? $_SERVER['HTTP_USER_AGENT']."|".@$request->location,
            ]);
        }

        $featured = Featured::where('user_id',$id)->get();
        $reviews = Review::where('reviewed',$id)->get();
        $Priceinfo = Priceinfo::where('user_id',$id)->get();

        $response = [
            'profile' => $user,
            'featured' => $featured,
            'review' => $reviews,
            'message' => "Here is the User Profile"
        ];
        return response($response, 201);
    }

    function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string'
        ]);

        $columns = ["email","fullname","username","whatsapp","tagline","tel","address","pricing"];
        $temp = [];

        #Search stated Columns
        foreach($columns as $colname){
            
            $user = DB::select("SELECT * FROM users WHERE ".$colname." LIKE '%".$request->input("q")."%'");

            if(@$user)
            {
                foreach(@$user as $userr)
                {
                    unset($userr->password, $userr->token, $userr->email_verified_at,$userr->doc1, $userr->doc2, $userr->doc3, $userr->passport);
                    $users[] = (Array)@$userr;
                }
            }
            
        }

        if(!@$users)
        {

            $response = [
                'message' => 'No search Result'
            ];
            return response($response, 422);
    
        }

        $response = [
            'result' => $users,
            'message' => 'here are the search results'
        ];
        return response($response, 201);

    }


    #User Login
    function store(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $user = User::where('email',$fields['email'])->orwhere('username',$fields['email'])->get()->first();

        if($user->state == 0){
            $response = [
                'message' => "User Suspended"
            ];

            return response($response, 402);
        }

        //if user does not existst
        if(@$user || Hash::check(@$fields['password'], @$user->password))
        {  
            
            $token = $user->createToken('userToken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
                'message' => "User Logged in Succesfully"
            ];

            return response($response, 201);
           
        }
        else
        {
             $response = [
                'user' => $user,
                'message' => "incorrect user or password"
            ];

            return response($response, 404);
            
        }
    }

    function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|unique:users,email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'state' => 1,
        ]);

        $token = $user->createToken('userToken')->plainTextToken;
        $this->confirm_email($request);

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => "User Registered Succesfully"
        ];

        return response($response, 201);
    }

    function logout(Request $request){
        auth()->user()->tokens()->delete();

        $response = [
            'messsage' => 'Logged out Succesfully'
        ];
        return response($response,201);
    }

    function update(Request $request)
    {
        $user = auth()->user();
        // print_r($request->file());

        $fields = $request->validate([
            'email' => 'string|unique:users,email',
            'username' => 'string|unique:users,username',
            'tagline' => 'string',
            'whatsapp' => 'string',
            'category' => 'string',
            'tel' => 'string',
            'pricing' => 'string',
            'address' => 'string',
            'bio' => 'string|min:50',
            'old_password' => 'string',
            'password' => 'string|min:6|confirmed',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'passport' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' ,
            'doc1' => 'file|max:5124' ,
            'doc2' => 'file|max:5124' ,
            'doc3' => 'file|max:5124' ,
        ]);

        extract($fields);

        $user->update(
            [
                'username' => @$username ?? $user->username,
                'fullname' => @$fullname ?? $user->fullname,
                'tagline' => @$tagline ?? $user->tagline,
                'category' => @$category ?? $user->category,
                'whatsapp' => @$whatsapp ?? $user->whatsapp,
                'tel' => @$tel ?? $user->tel,
                'address' => @$address ?? $user->address,
                'bio' => @$bio ?? $user->pricing,
                'pricing' => @$pricing ?? $user->prciing
            ]
        );

        #PASSWORD

        if(@$old_password){
            if(Hash::check(@$old_password, @$user->password))
            {
                $user->update([ 'password' => bcrypt(@$password)]);

                $response = [
                    'user' => $user,
                    'message' => "Password Changed Succesfully"
                ];
                return response($response, 201);
            }
            else
            {
                $response = [
                    'user' => $user,
                    'message' => "Old Password Incorrect"
                ];
                return response($response, 422);
            }
        }
        
        //FILE
        if($request->hasfile('image'))
        {

            $imageName = $user->username.'.'.$request->image->extension();
            $user->dp = $request->image->move(public_path('images'), $imageName) ;
            $user->save();
            

        }

        if($request->hasfile('passport'))
        {  
            $imageName = $user->username.'_passport.'.$request->passport->extension();
            $ect = $request->passport->move(public_path('images'), $imageName) ;
            $user->update(
                [ 
                    'passport' => $ect
                ]
            );
        }

        if($request->hasfile('doc1'))
        {  
            $imageName = $user->username.'doc1.'.$request->doc1->extension();
            $user->update(
                [ 
                    'doc1' => $request->doc1->move(public_path('images'), $imageName) 
                ]
            );
        }

        if($request->hasfile('doc2'))
        {  
            $imageName = $user->username.'doc2.'.$request->doc2->extension();
            $user->update(
                [ 
                    'doc2' => $request->doc2->move(public_path('images'), $imageName) 
                ]
            );
        }

        if($request->hasfile('doc3'))
        {  
            $imageName = $user->username.'doc3.'.$request->doc3->extension();
            $user->update(
                [ 
                    'doc3' => $request->doc3->move(public_path('images'), $imageName) 
                ]
            );
         }
        
       $user->save();
       $user->refresh();

       $response = [
            'user' => $user,
            'message' => 'Update Successfully'
        ];
        return response($response,201);
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email =  $request->email;
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $email, 
            'token' => $token, 
          ]);
        
        try
        {
           $mail = Mail::send('mail', 
            [
                'token' => $token,
                'email' => $email,
            
            ], 
            function($message) use($email) 
            {
                $message->to($email, 'Your forgotten Password');
                $message->subject('Password Forgotten');
                $message->from('bataino.ronaldo@gmail.com','Bataino Ronaldo');
            }); 
        }
        catch(\Exception $e)
        {
            $response = [
                'email' => $email,
                'message' => 'Network Error, Couldn\'t send mail'
            ];
            return response($response, 404);
        }
        

        if(Mail::failures())
        {
             $response = [
                'email' => $email,
                'message' => 'Error Sending Mail'
            ];
            return response($response,422);
        }
        else
        {
            $response = [
                    'email' => $email,
                    'message' => 'Email Sent Successfully'
                ];
            return response($response,201);
        }
    }

    function confirmForgotPassword(Request $request)
    {
        $request->validate(
            [
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email, 
                'token' => $request->token
         ]
        )->first();

        if(!$updatePassword)
        {
            $response = [
                'email' => $request->email,
                'message' => 'Link has Expired'
            ];
            return response($response, 422);
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        $response = [
            'email' => $request->email,
            'user' => $user,
            'message' => 'Your password has been changed'
        ];
        return response($response,201);

        
    }

    
    public function confirm_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email =  $request->email;
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $email, 
            'token' => $token, 
          ]);
        
        try
        {
           $mail = Mail::send('confirmMail', 
            [
                'token' => $token,
                'email' => $email,
                'username' => $request->username
            
            ], 
            function($message) use($email) 
            {
                $message->to($email, 'Skrill: Email Verificatio');
                $message->subject('Welcome to the Skrill Community');
                $message->from('bataino.ronaldo@gmail.com','Bataino Ronaldo');
            },function($error){ die("lagaga".$error);}); 
        }
        catch(Swift_TransportException $e)
        {
            $response = [
                'email' => $email,
                'message' => 'Network Error, Couldn\'t send mail'
            ];
            return response($response, 404);
        }
        

        if(Mail::failures())
        {
             $response = [
                'email' => $email,
                'message' => 'Error Sending Mail'
            ];
            return response($response,422);
        }
        else
        {
            $response = [
                    'email' => $email,
                    'message' => 'Email Sent Successfully'
                ];
            return response($response,201);
        }
    }

    function confirmEmail(Request $request)
    {
        $request->validate(
            [
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email, 
                'token' => $request->token
         ]
        )->first();

        if(!$updatePassword)
        {
            $response = [
                'email' => $request->email,
                'message' => 'Link has Expired'
            ];
            return response($response, 422);
        }

        $user = User::where('email', $request->email)->update(['email_verified_at' => Carbon::now()]);
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        $response = [
            'email' => $request->email,
            'user' => $user,
            'message' => 'Email Verified Successfully'
        ];
        return response($response,201);

        
    }
}
        



