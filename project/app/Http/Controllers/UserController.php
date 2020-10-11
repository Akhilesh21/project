<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

// class UserController extends Controller
// {
//     public function __construct()
//     {
       

//     }
//     // 1 .working
//      public function register(Request $request){
//      //data validation
//         $name = $request->input('name');
//         $email = $request->input('email');
//         $password = Hash::make($request->input('password'));
//         $tweeter_handle = $request->input('tweeter_handle');
        
//         $register = User::create([
//             'name'=>$name,
//             'email'=>$email,
//             'password'=>$password,
//             'tweeter_handle'=>$tweeter_handle
//         ]);
        
//         if($register){
//             return response()->json(['success' =>true, 'message' =>'Registration success',
//                 'data' => $register], 201);
//          } else {
//             return response()->json(['success' =>false,'message' =>'Registration Failed',
//                 'data' => ''], 400);
//         } 
// }

//     // 2 .working
//     public function login(Request $request){
//         $email = $request->input('email');
//         $password = $request->input('password');
        
//         $user = User::where('email', $email)->first();

//         if(Hash::check($password, $user->password)){
//             $apiToken = base64_encode(str_random(40));
//             $user->update([
//                 'api_token' => $apiToken
//             ]);
//             return response()->json(['success' => true,'message' => 'Login successful',
//                  'data' => ['user' => $user ,'api_token' => $apiToken]
//                 ], 201);
//         } else {
//             return response()->json(['success' => false,'message' => 'Login failed',
//                 'data' => '']);
//         }
//     }

//     public function details(User $user){
//             return $user;
//     }  
//    // 3 .working
//     public function updateDetails(Request $request){
//         // echo "akilesh";exit;
//         $update = User::find($request->id);
//         if($update){
//             if($request['email'] == null){
//                 return response()->json(['Message' => 'error'], 400);
//             }else{
//                 $update->name = $request['name'];
//                 $update->email = $request['email'];
//                 $update->tweeter_handle = $request['tweeter_handle'];
//                 $update->profile_pic = $request['profile_pic'];
//                 $update->save();
//                 return response()->json(['message' => 'details updated successfully'], 200);
//             }
//         }else{
//             return response()->json(['message' => 'undefined'], 404);
//         }
//     }
//     // 4. pending
//     public function updateProfile(Request $request){
//       $find = User::find($request['api_token']);
//       if($find){
//         $find->profile_pic=$request['profile_pic'];
//         $find->save();
//        return response()->json(['message'=>$find]);
//       }else
//       return response()->json(['message'=>"profile not updated"]);
//     }

//    public function profile(){
//        echo "hi";
//        return response()->download(public_path('twitter_PNG31.png'), 'profile_pic');
//    }
    
//    public function Update_profile(Request $request){
//    $fileName = "user_image.jpg";
//    $path = $request->file('profile_pic')->move(public_path("/") ,$fileName);
//    $photoUrl = url('/'.$fileName);
//    return response()->json(['url' => $photoUrl], 200);
//  }
// }


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\User;

class UserController extends Controller{
            
     
     public function __construct(){
           
      $this->middleware('auth');
    
    }
     public function register(Request $request){
    //     dd($request);
         //validation
         $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'tweeter_handle' => 'required|string',       
         ]);
        $input = $request->only('name','email','password','tweeter_handle'); 
       //  dd($input);
         try{
             $user = new User;
             $user->name = $input['name'];
             $user->email = $input['email'];
             $password = $input['password'];;
             $user->password = app('hash')->make($password);
             $user->tweeter_handle = $request->input('tweeter_handle');
             if( $user->save() ){
                 $code = 200;
                   $output = [
                     'user' => $user,
                     'code' => $code,
                     'message' =>'User created successfully',
            ];
             } else{
                 $code = 500;
                $output = [
                    'code' => $code,
                    'message' =>'error while creating user',
            ];  
             }
         } catch(Exception $e){
          //   dd($e->getMessage());
          $code = 500;
             $output = [
                'code' => $code,
                'message' =>'error while creating user',
        ];
         }
         return response()->json($output, $code);
     }

   public function login(Request $request){
      
      $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',       
     ]);
     $input = $request->only('email','password');
     //dd($input);
     if( ! $authorized = Auth::attempt($input) ){
     $code = 401;
     $output = [
         'code' => $code,
         'message' =>'User is not authorized',
         ];
    }else{
     $token =  $this->respondWithToken($authorized);
     $code = 201;
     $output = [
         'code' => $code,
         'message' =>'User logged in successfully',
         'token' => $token
         ];
    }
    return response()->json($output, $code);
  }


  public function allUsers(){
       return response()->json(['users' =>  User::all()], 200);
  }


  public function profile(){
        return response()->json(['user' => Auth::user()], 200);
    }
        
}