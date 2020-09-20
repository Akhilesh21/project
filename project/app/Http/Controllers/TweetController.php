<?php

namespace App\Http\Controllers;
use App\Model\Tweet;
use App\Model\Comment;

use Illuminate\Http\Request;

class TweetController extends Controller{
//working    
    public function createTweet(Request $request){
        $input = $request->all();
        if($input['tweet'] == null){
            return response()->json(['Message' =>'error'], 400);
        }else{
            $tweet = Tweet::create($input);
            return response()->json(['Message' => 'Tweet created successfully'], 200);
        }
    }
//edit or update tweet //working
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
 //working
    // public function getTweet(){
    //     $find = Tweet::where('userid', 1)->first();
    //     if($find){
    //         $tweet = Tweet::where('userid',1)->get(['id','tweet',]);
    //         return response()->json(['data' => $tweet], 200);
    //     }else{
    //         return response()->json(['message' => 'error']);
    //     }
    // }
//working
    public function getTweet(Request $request){
        $inputValues=$request->all();
        $find = Tweet::where('userid', $inputValues['userid'])->first();
        if($find){
            $tweet = Tweet::where('userid',$inputValues['userid'])->get();
            return response()->json(['data' => $tweet], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
//working
    public function deleteTweet(Request $request){
          $find = Tweet::find($request['id']);
          if($find){
              $find = Tweet::find($request['id'])->delete();
              return response()->json(['message' => 'tweet deleted '], 200);
          }else{
              return response()->json(['message' => 'error'] , 404);
          }
    }

    // public function like(Request $request){
    //     $like = Tweet::where($request->id)->first;
    //     $like->userid()->attach(auth()->userid()->id);
    //     $like->sum += $count;
    //     $like->total_likes += 1;
    //     $like->save();
    //     return number_format((float)$like->sum / $total_likes, 1, '.', '');
    // }

    // public function unlike(Request $request, $count){
    //     $like = Tweet::where($request->id)->first;
    //     $like->userid()->attach(auth()->userid()->id);
    //     $like->sum -= $count;
    //     $like->total_likes -= 1;
    //     $like->save();
    //     return number_format((float)$like->sum / $total_likes, 1, '.', '');
    // }

//working
    public function addComment(Request $request)
    {

        $this->validate($request, [
            'comment' => 'required|min:1',
            'twitter_handler' => 'required|min:3',
            'tweet_id' => 'required|integer', //  |unique:users',
        ]);

        // $find=Tweet::where(['tweet_id'=> $inputValues['tweet_id'],'twitter_handler'=> $inputValues['twitter_handler']])->get(['id','total_comments']);
        // $find->total_comments=$find['total_comments']+1;
        // $find->save();

        $user = comment::create([
            'comment' => $request->comment,
            'twitter_handler' => $request->twitter_handler,
            'tweet_id' => $request->tweet_id,
        ]);
        return response()->json(['Message' => 'comment created successfully'], 200);
    }
    
    //working
    // public function getComment(){
    //     $find = comment::where('tweet_id', 1)->first();
    //     if($find){
    //         $comment = comment::where('tweet_id',1)->get(['id','comment',]);
    //         return response()->json(['data' => $comment], 200);
    //     }else{
    //         return response()->json(['message' => 'error']);
    //     }
    // }
    ///working
    public function getComment(Request $request){
        $inputValues=$request->all();
        $find = comment::where('tweet_id', $inputValues['tweet_id'])->first();
        if($find){
            $comment = comment::where('tweet_id',$inputValues['tweet_id'])->get();
            return response()->json(['data' => $comment], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
    
//
    public function editComment(Request $request){
       $comment = comment::find($request->tweet_id);
    //     echo "hi";
    //    exit;
    print_r($comment);
        if($comment){
            if($request['comment'] == null){
                return response()->json(['Message' => 'error'], 400);
            }else{
                $comment->comment = $request['comment'];
                $comment->save();
                return response()->json(['message' => 'comment updated successfully'], 200);
            }
        }else{
            return response()->json(['message' => 'undefined'], 404);
        }
    }
 //working
    public function deleteComment(Request $request){
        $find = comment::find($request['id']);
        if($find){
            $find = comment::find($request['id'])->delete();
            return response()->json(['message' => 'tweet deleted '], 200);
        }else{
            return response()->json(['message' => 'error'] , 404);
        }
  }
//not working
  public function likeAndDislike(Request $request){
     $inputValues = $request->all();
     $find = Tweet::find($inputValues['id']);
     if($find)
     {
        $find->total_likes=$find['total_likes'] + 1;
        $find->save();
         return response()->json(['message' => 'liked successfully']);
     }
     return response()->json(['message' => 'something went wrong']);
    }
//search tweet by text
//not working 
    public function searchTweet(Request $request){
        $inputValues = $request->all();
        $search = $request['search'];
        // print_r($inputValues);
        // echo $search;
        // exit;
        if(isset($search)){
        $find=Tweet::where(['userid'=>$inputValues['userid']])->where('tweet','LIKE',"%{$search}%")->get();
        return response()->json(['data' => $find ], 200);
        }
        return response()->json(['data' =>'not found' ], 200);
    }
//search tweet by comment
//not working
    public function searchComment(Request $request)
    {
        $inputValues=$request->all();
        $search = $request['search'];
        if(isset($inputValues['search']))
        {
            $find=Comment::where(['twitter_handler'=>$inputValues['userid']])->where(['tweet_id'=>$inputValues['tweet_id']])->where('comment','LIKE',"%{$search}%")->get();
            return response()->json(['data' => $find], 200);
        }
    }
//not working
    public function updateComment(Request $request)
    {
        $inputValues = $request->all();
        if(isset($inputValues['comment']))
        {
            $find=Comment::where(['tweet_id'=> $inputValues['tweet_id']]);
            if($find){
                $find->comment=$inputValues['comment'];
                $find->save();
                return response()->json(['message' => 'comment updated']);
            }
        }
        return response()->json(['message' => 'something went wrong']);
    }
    
//working
    public function gettenTweet(Request $request){
        $inputValues=$request->all();
        $find = Tweet::where('userid', $inputValues['userid'])->first();
        if($find){
            $tweet = Tweet::where('userid',$inputValues['userid'])->limit(10)->get();
            return response()->json(['data' => $tweet], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
//working
    public function gettencomment(Request $request){
        $inputValues=$request->all();
        $find = comment::where('tweet_id', $inputValues['tweet_id'])->first();
        if($find){
            $comment = comment::where('tweet_id',$inputValues['tweet_id'])->limit(10)->get();
            return response()->json(['data' => $comment], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
    
}
  
?>