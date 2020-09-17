<?php

namespace App\Http\Controllers;
use App\Model\Tweet;
use App\Model\Comment;

use Illuminate\Http\Request;

class TweetController extends Controller{
    
    public function createTweet(Request $request){
        $input = $request->all();
        if($input['tweet'] == null){
            return response()->json(['Message' =>'error'], 400);
        }else{
            $tweet = Tweet::create($input);
            return response()->json(['Message' => 'Tweet created successfully'], 200);
        }
    }
//edit or update tweet
    public function editTweet(Request $request){
        $tweet = Tweet::find($request->id);
        if($tweet){
            if($request['tweet'] == null){
                return response()->json(['Message' => 'error'], 400);
            }else{
                $tweet->tweet = $request['tweet'];
                $tweet->save();
                return response()->json(['message' => 'tweet updated successfully'], 200);
            }
        }else{
            return response()->json(['message' => 'undefined'], 404);
        }
    }
 
    public function getTweet(){
        $find = Tweet::where('userid', 1)->first();
        if($find){
            $tweet = Tweet::where('userid',1)->get(['id','tweet',]);
            return response()->json(['data' => $tweet], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }

    public function deleteTweet(Request $request){
          $find = Tweet::find($request['id']);
          if($find){
              $find = Tweet::find($request['id'])->delete();
              return response()->json(['message' => 'tweet deleted '], 200);
          }else{
              return response()->json(['message' => 'error'] , 404);
          }
    }

    public function Comment(Request $request){
        $input = $request->all();
        echo "test";
        if($input['comment'] == null){
            return response()->json(['Message' =>'error'], 400);
        }else{
            $comment = Comment::create($input);
            return response()->json(['Message' => 'commented successfully'], 200);
        }
    }



    
  
}
?>