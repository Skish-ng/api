<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Chat;
use Carbon\Carbon;


class MessageController extends Controller
{
    //
    
    function index(Request $request){

        $user = auth()->user();
        $chats = Chat::finduserChats($user->id);
        $temp = [];

        foreach ($chats as $chat) {
            
            $array = explode(',',$chat->users);
            $array = array_diff($array,[$user->id]);
            
            $id = $array[1];

            $temp[] = [
                'user' => User::find($id)->username,
                'user_id' => User::find($id)->id,
                'unread' =>  Message::where('state', 0)->where('reciever', $user->id)->where('sender', User::find($id)->id)->count(),
                'lastmessage' => Message::find($chat->lastmessage)->body,
                'date' => $chat->updated_at
      
            ];
        }
        
        $response = [
            'Allmsg' => $temp,
            'message' =>"Unread and read messages",
        ];
        return response($response, 201);
        
    }

    
    function store(Request $request){

        $filepath = '';
        $user = auth()->user();

        $fields = $request->validate([
            'reciever' => 'integer|required|exists:users,id|not_in:'.$user->id,
            'body' => 'required|string',
            'file' => 'file',
        ]);
        extract($fields);

        if($request->hasfile('file'))
        {  
            $imageName = $user->username.'file.'.$request->file->extension();
            $filepath = $request->file->move(public_path('images/messages/'.$user->username."_and_".User::find($reciever)->username), $imageName) ;
            
        }



        $chat = Chat::findbyId($user->id,$reciever);

        $message = Message::create([
            'sender' => $user->id,
            'reciever' => $reciever,
            'body' => $body,
            'file' => $filepath,
        ]);

        $chat->lastmessage = $message->id;
        $chat->save();

        $response = [
            'msg' => $message->refresh(),
            'message' =>"message sent",
        ];
        return response($response, 201);
    }

    function show(Request $request, $id){

        $user = auth()->user();
        $this->update();

        $m2 = Message::where('reciever',$id)->where('sender', $user->id)->orderby('id','DESC')->get();
        $m1 = Message::where('sender',$id)->where('reciever', $user->id)->orderby('id','DESC')->get();

        return  $m1 + $m2;
    }

    function unread(Request $request){

        $user = auth()->user();
       return  Message::where('state',0)->where('reciever', $user->id)->orderby('id','DESC')->get();
       
    }

    function delete(Request $request, $id){

        $user = auth()->user();
        $message = Message::find($id);

        if(!$message || @$message->sender != $user->id )
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }

        Message::find($id)->delete();

        $response = [
            'message' => "Successfully deleted"
        ];
        return response($response, 201);
        
    }

    function update(){

        $user = auth()->user();
        

        foreach(Message::where('reciever',$user->id)->where('state',0)->get() as $message){

            $message->state = 1;
            $message->save();
            $message->refresh();

        }
        $response = [
            'message' =>"messages read",
        ];
        return response($response, 201);
    }
}
