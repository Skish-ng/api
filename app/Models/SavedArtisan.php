<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedArtisan extends Model
{
    use HasFactory;

    public static function Search($id,$query){
        $user = User::where('token', $request->bearerToken());
        print_r($user);
        return $user;
    }
    protected $fillable = [
        'user_id',
        'artisan_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
}
