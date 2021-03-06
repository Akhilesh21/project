<?php

namespace App\Http\Controllers;
use App\Model\Tweet;
use App\Model\Comment;

use Illuminate\Http\Request;

class TweetController extends Controller{
//1.working    
    // public function createTweet(Request $request){
    //     $input = $request->all();
    //     if($input['tweet'] == null){
    //         return response()->json(['Message' =>'error'], 400);
    //     }else{
    //         $tweet = Tweet::create($input);
    //         return response()->json(['Message' => 'Tweet created successfully'], 200);
    //     }
    // }
//working with validation
    public function createTweet(Request $request){
             $this->validate($request, [
                'tweet' => 'required',
                'userid' => 'required|integer',
                'total_comments' => 'required',
                'total_likes' => 'required',  
                'total_retweets' => 'required'    
             ]);
            $input = $request->only('tweet','userid','total_comments','total_likes','total_retweets'); 
             try{
                 $user = new Tweet;
                 $user->tweet = $input['tweet'];
                 $user->userid = $input['userid'];
                 $user->total_comments = $input['total_comments'];
                 $user->total_likes = $input['total_likes'];
                 $user->total_retweets = $input['total_retweets'];
                 
                 if( $user->save() ){
                     $code = 200;
                    $output = ['user' => $user,'code' => $code,'message' =>'Tweet created successfully',
                ];
                 } else{
                     $code = 500;
                    $output = ['code' => $code,'message' =>'error while creating Tweet',];  
                 }
             } catch(Exception $e){
              $code = 500;
                 $output = ['code' => $code,'message' =>'error while creating Tweet',];
             }
             return response()->json($output, $code);
         }  

    
 //2 .edit or update tweet //working
    // public function editTweet(Request $request){
    //     $tweet = Tweet::find($request->id);
    //     if($tweet){
    //         if($request['tweet'] == null){
    //             return response()->json(['Message' => 'error'], 400);
    //         }else{
    //             $tweet->tweet = $request['tweet'];
    //             $tweet->save();
    //             return response()->json(['message' => 'tweet updated successfully'], 200);
    //         }
    //     }else{
    //         return response()->json(['message' => 'undefined'], 404);
    //     }
    // }
//2 with validation
    public function editTweet(Request $request)
    {
        $this->validate($request, [
        'tweet' => 'filled',
        'userid' => 'required',
        'id' => 'required'
         ]);
        $tweet = Tweet::find($request->id);
        if($tweet->fill($request->all())->save()){
           return response()->json(['status' => 'tweet updated successfully']);
        }
        return response()->json(['status' => 'failed']);
    }

 
//3 .working
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
//4 .working
    public function deleteTweet(Request $request){
          $find = Tweet::find($request['id']);
          if($find){
              $find = Tweet::find($request['id'])->delete();
              return response()->json(['message' => 'tweet deleted '], 200);
          }else{
              return response()->json(['message' => 'error'] , 404);
          }
    }

//5 .working
// here table tweets(id) = table  comments(tweet_id)
    public function addComment(Request $request)
    {

        $this->validate($request, [
            'comment' => 'required|min:1',
            'twitter_handler' => 'required|min:3',
            'tweet_id' => 'required|integer', //  |unique:users',
        ]);
        $user = comment::create([
            'comment' => $request->comment,
            'twitter_handler' => $request->twitter_handler,
            'tweet_id' => $request->tweet_id,
        
            ]);
            /**tweet section */
        $comment = Tweet::find($request->id);
        if ($comment) {
           $comment->total_comments = $comment['total_comments'] + '1';
            if ($comment->save()) {
                return response()->json(['message' => 'successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr '], 400);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }
    }
  //6 .working  
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
    
// 7. working
    // public function editComment(Request $request){
    //    $comment = comment::find($request->tweet_id);
    // // print_r($comment);
    //     if($comment){
    //         if($request['comment'] == null){
    //             return response()->json(['Message' => 'error'], 400);
    //         }else{
    //             $comment->comment = $request['comment'];
    //             $comment->save();
    //             return response()->json(['message' => 'comment updated successfully'], 200);
    //         }
    //     }else{
    //         return response()->json(['message' => 'undefined'], 404);
    //     }
    // }
//7 working
    public function editComment(Request $request){
        $this->validate($request, [
        'comment' => 'filled',
        'tweet_id' => 'required',
        'twitter_handler' => 'required',
        'id' => 'required'
         ]);
        $tweet = comment::find($request->tweet_id);
        if($tweet->fill($request->all())->save()){
           return response()->json(['status' => 'comment updated successfully']);
        }
        return response()->json(['status' => 'failed']);
    }
 // 8. working
    public function deleteComment(Request $request){
        $find = comment::find($request['id']);
        if($find){
            $find = comment::find($request['id'])->delete();
         $comment = Tweet::find($request->id);
        if ($comment) {
           $comment->total_comments = $comment['total_comments'] - '1';
            if ($comment->save()) {
                return response()->json(['message' => 'comment deleted  successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr '], 400);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }
    }
   }

  // 9 .working  
    public function like(Request $request){
        $like = Tweet::find($request->id);
        if ($like) {
           $like->total_likes = $like['total_likes'] + '1';
            if ($like->save()) {
                return response()->json(['message' => 'like successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr while like'], 400);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }

    }
// 10 .working
    public function Dislike(Request $request){
        $like = Tweet::find($request->id);
        if ($like) {
           $like->total_likes = $like['total_likes'] - '1';
            if ($like->save()) {
                return response()->json(['message' => 'unlike successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr while unlike'], 400);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }
    }
     
// 11 .working
    public function retweet(Request $request){
        $retweet = Tweet::find($request->id);
        if ($retweet) {
           $retweet->total_retweets = $retweet['total_retweets'] + '1';
            if ($retweet->save()) {
            } else {
                return response()->json(['message' => 'Erorr while retweet'], 400);
                return response()->json(['message' => 'retweet successfully'], 200);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }
    }
// 12 .working
    public function undoRetweet(Request $request){
        $retweet = Tweet::find($request->id);
        if ($retweet) {
           $retweet->total_retweets = $retweet['total_retweets'] - '1';
            if ($retweet->save()) {
                return response()->json(['message' => 'undoRetweet successfully'], 200);
            } else {
                return response()->json(['message' => 'Erorr while undoRetweet'], 400);
            }
        } else {
            return response()->json(['message' => ' Id Invalid'], 404);
        }

    }

//search tweet by text
//13 .working 
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
//search comment
// 14 .working
    public function searchComment(Request $request)
    {
        $inputValues=$request->all();
        $search = $request['search'];
        if(isset($inputValues['search']))
        {
            $find=Comment::where(['twitter_handler'=>$inputValues['tweet_id']])->where(['tweet_id'=>$inputValues['tweet_id']])->where('comment','LIKE',"%{$search}%")->get();
            return response()->json(['data' => $find], 200);
        }
       return response()->json(['data' =>'not found' ], 200);
    }
    
// 15 .pagination  10 tweets per page working
    public function gettenTweet(Request $request){
        $inputValues=$request->all();
        $find = Tweet::where('userid', $inputValues['userid'])->first();
        if($find){
            $tweet = Tweet::where('userid',$inputValues['userid'])->paginate(10)->onEachSide(5);//->get();
            return response()->json(['data' => $tweet], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
// 16 .pagination 10 comments per page working
    public function gettencomment(Request $request){
        $inputValues=$request->all();
        $find = comment::where('tweet_id', $inputValues['tweet_id'])->first();
        if($find){
            $comment = comment::where('tweet_id',$inputValues['tweet_id'])->paginate(10)->onEachSide(5);//->get();
            return response()->json(['data' => $comment], 200);
        }else{
            return response()->json(['message' => 'error']);
        }
    }
}
  
?>