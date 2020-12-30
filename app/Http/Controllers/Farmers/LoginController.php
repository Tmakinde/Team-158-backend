<?php

namespace App\Http\Controllers\Farmers;
use App\Farmer;
use DB;
use Illuminate\support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct(){
        $this->middleware('guest:farmers');
    }

    public function login(Request $request){
        $username = $request->username;
        $credentials = compact('username');
        
        //$api_token = Str::random(60);

        $checkUsername = Farmer::where('username', $username)->first();
        try {
            //Auth::guard('farmers')->attempt($credentials)
            if ($checkUsername){
                $api_token = Auth::guard('farmers')->login($checkUsername);
                return response()->json([
                    'api_token' => $api_token,
                    'message' => 'User Succesfully login'
                ], 200);

            }

           /* if(!empty($checkUsername)){
                DB::table('farmers')->whereExists('username', $username)->update('api_token', $api_token);
                return response()->json([
                    'api_token' => $api_token,
                    'Message' => 'User Succesfully login'
                ], 200);
            }*/else{
                return response()->json([
                    'Message' => 'You are not authorize to signin because the username does not exist'
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'Message' => 'Internal server Error'
            ], 500);
        }
      
    }
}





