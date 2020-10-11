<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


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