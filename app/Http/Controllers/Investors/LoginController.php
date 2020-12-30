<?php

namespace App\Http\Controllers\Investors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Investor;
use Illuminate\support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(){
        $this->middleware('guest:investors');
    }

    public function login(Request $request){
        $username = $request->username;
        //$api_token = str_random(60);
        $checkUsername = Investor::where('username', $username)->first();
        try {
            if ($checkUsername){
                $api_token = Auth::guard('investors')->login($checkUsername);
                return response()->json([
                    'api_token' => $api_token,
                    'message' => 'User Succesfully login'
                ], 200);

            }else{
                return response()->json([
                    'message' => 'You are not authorize to signin because the username does not exist'
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal server Error'], 500);
        }
        
    }
}
