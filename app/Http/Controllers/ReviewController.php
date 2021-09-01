<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;

class ReviewController extends Controller
{
    //
    function store(Request $request)
    {
        $fields = $request->validate([
            'reviewed' => 'required|integer',
            'review' => 'required|integer',
            'message' => 'required|string'
        ]);
        extract($fields);
        
        $user = auth()->user();

        $todayReview = Review::where('reviewed',$reviewed)->whereDate('created_at', Carbon::today())->count();
        $todayforReviewer = Review::where('reviewer',$reviewe ?? $user->id)->whereDate('created_at', Carbon::today() )->count();
        $reviewExist = Review::where('reviewed',$reviewed)->where('reviewer', $user->id)->count();

        if($reviewExist)
        {
            $response = [
                'message' => "Review already exists"
            ];
            return response($response, 422);
        }

        else if($user->id == $reviewed)
        {
            $response = [
                'message' => "You can not review Yourself baba"
            ];
            return response($response, 422);
        }

        else if($todayReview >= 2)
        {
            $response = [
                'message' => "Maximum Today-Reviews for the User"
            ];
            return response($response, 422);
        }

        else if($todayforReviewer >= 5)
        {
            $response = [
                'message' => "You can only review upto 5 users per day"
            ];
            return response($response, 422);
        }

        $review = Review::create([
            'reviewer' => $user->id,
            'reviewed' => $reviewed,
            'review' => $review,
            'message' => $message
        ]);

        $response = [
            'message' => "Reviewed Successfully"
        ];
        return response($response, 201);
    }


    // function myReview(Request $request)
    // {
    //     $user = auth()->user();

    //     $response = [
    //         'user' => $user,
    //         'myreview' => Review::where('reviewed',$user->id)->get(),
    //         'message' => "Your reviews"
    //     ];
    //     return response($response, 201);
    // }

    function show(Request $request, $id)
    {
        $response = [
            'reviews' => Review::where('reviewed', $id)->get(),
            'message' => User::find($id)->username."'s reviews"
        ];
        return response($response, 201);
    }


    function update(Request $request){
        $fileds = $request->validate([
            'id' => 'integer',
            'message' => 'string',
            'review' => 'integer',
        ]);
        extract($fields);

        $Review = Review::find($id);

        $Review->update(
            [
                'message' => @$message,
                'review' => $review
            ]
        );

        $response = [
            'user' => $user,
            'review' => $Review,
            'message' => "Review Updated"
        ];
        return response($response, 201);

    }


    function delete(Request $request,$id){
        $review = review::find($id);
        if(!$review)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }

        Review::find($id)->delete();

        $response = [
            'message' => "Successfully deleted"
        ];
        return response($response, 201);
    }
}
