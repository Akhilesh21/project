<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
//aditional code 
class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function __construct()
    {
       

    }
    // 1 .working
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
            return response()->json(['success' =>true, 'message' =>'Registration success',
                'data' => $register], 201);
         } else {
            return response()->json(['success' =>false,'message' =>'Registration Failed',
                'data' => ''], 400);
        } 
}

    // 2 .working
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        
        $user = User::where('email', $email)->first();

        if(Hash::check($password, $user->password)){
            $apiToken = base64_encode(str_random(40));
            $user->update([
                'api_token' => $apiToken
            ]);
            return response()->json(['success' => true,'message' => 'Login successful',
                 'data' => ['user' => $user ,'api_token' => $apiToken]
                ], 201);
        } else {
            return response()->json(['success' => false,'message' => 'Login failed',
                'data' => '']);
        }
    }

    public function details(User $user){
            return $user;
    }  
   // 3 .working
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
    // 4. pending
    public function updateProfile(Request $request){
      $find = User::find($request['api_token']);
      if($find){
        $find->profile_pic=$request['profile_pic'];
        $find->save();
       return response()->json(['message'=>$find]);
      }else
      return response()->json(['message'=>"profile not updated"]);
    }

   public function profile(){
       echo "hi";
       return response()->download(public_path('twitter_PNG31.png'), 'profile_pic');
   }
    
   public function Update_profile(Request $request){
   $fileName = "user_image.jpg";
   $path = $request->file('profile_pic')->move(public_path("/") ,$fileName);
   $photoUrl = url('/'.$fileName);
   return response()->json(['url' => $photoUrl], 200);
 }
}


