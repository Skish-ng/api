<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'summary',
    ];

    public static function Add($id,$summary){
        Activity::create([
            'created_by' => $id,
            'summary' => $summary,
        ]);
    }
}
