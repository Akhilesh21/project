<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
       

    }
    public function register(Request $request){
//data validation
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $tweeter_handle = $request->input('tweeter_handle');
        
        $register = User::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
            'tweeter_handle'=>$tweeter_handle
        ]);
        
        if($register){
            return response()->json([
                'success' =>true,
                'message' =>'Registration success',
                'data' => $register
            ], 201);
        //    $token = $register->createToken('tweet')->accessToken;
        //    return response()->json(['token' => $token], 200);
         } else {
            return response()->json([
                'success' =>false,
                'message' =>'Registration Failed',
                'data' => ''
            ], 400);

        } 

    }


    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        
        $user = User::where('email', $email)->first();

        if(Hash::check($password, $user->password)){
            $apiToken = base64_encode(str_random(40));
            $user->update([
                'api_token' => $apiToken
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                 'data' => [
                 'user' => $user ,
                 'api_token' => $apiToken
                ]
                ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'data' => ''
            ]);
        }
    }

    public function details(){
            return response()->json(['user' => auth()->user()], 200);
    }  

    public function updateDetails(Request $request){
        // echo "akilesh";exit;
        $update = User::find($request->id);
        if($update){
            if($request['email'] == null){
                return response()->json(['Message' => 'error'], 400);
            }else{
                $update->name = $request['name'];
                $update->email = $request['email'];
                $update->tweeter_handle = $request['tweeter_handle'];
                $update->profile_pic = $request['profile_pic'];
                $update->save();
                return response()->json(['message' => 'details updated successfully'], 200);
            }
        }else{
            return response()->json(['message' => 'undefined'], 404);
        }
    }
    
    public function updateProfile(Request $request){
      $find = User::find($request['token']);
      if($find){
        $find->profile_pic=$request['profile_pic'];
        $find->save();
       return response()->json(['message'=>$find]);
      }else
      return response()->json(['message'=>"profile not updated"]);
    }
       
}