<?php

namespace App\Http\Controllers\Investors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DB;
use App\Investor;

class LoginController extends Controller
{
    public function login(Request $request){
        $username = $request->username;
        $api_token = str_random(60);
        $checkUsername = DB::table('investors')->whereExists('username', $username)->get();
        try {
            if(!empty($checkUsername)){
                DB::table('investors')->whereExists('username', $username)->update('api_token', $api_token);
                return response()->json([
                    'api_token' => $api_token,
                    'Message' => 'User Succesfully login'
                ], 200);
            }else{
                return response()->json([
                    'Message' => 'You are not authorize to signin because you have not signin'
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
        
    }
}
