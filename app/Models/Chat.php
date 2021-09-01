<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'lastmessage',
        'users',
    ];
// where('users','like',$user1)->where('users','like',$user2)->get()

    public static function findbyId($user1,$user2){
       $chats = Chat::all();
       $boolean = false;
       $chat = [];

       foreach ($chats as $chat) {
            $delimer = explode(',', $chat->users);
            $boolean = in_array($user1,$delimer) && in_array($user2,$delimer);

           if($boolean){
               return $chat;
           }
       }
       if(!$boolean) {
                $chat = Chat::create([
                    'users' => $user1.','.$user2
                ]);
        }
       return $chat;
       
    }



    public static function finduserChats($user){
        $chats = Chat::all();

        $realchats = [];
 
        foreach ($chats as $chat) {
            $delimer = explode(',',$chat->users);
 
            if(in_array($user,$delimer)){
                $realchats[] = $chat;
            }
        }

        return $realchats;
     }
}
