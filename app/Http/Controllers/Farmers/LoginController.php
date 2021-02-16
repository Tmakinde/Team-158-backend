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
        $userStatus = $request->status;
        $username = $request->uid;
        $checkUsername = Farmer::where('uid', $username)->first();
        try {
            if($userStatus == "connected"){

                if ($checkUsername){
                    $api_token = Auth::guard('farmers')->login($checkUsername);
                    return response()->json([
                        'api_token' => $api_token,
                        'message' => 'User Succesfully login'
                    ], 200);
                    
                }else{
                    return response()->json([
                        'message' => 'You need to fill the Farmer data before authorization'
                    ], 401);
                    
                }

            }else{
                return response()->json([
                    'message' => 'Please connect to facebook'
                ], 403);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'Message' => 'Internal server Error'
            ], 500);
        }
      
    }
}





