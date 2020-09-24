<?php

namespace App\Http\Controllers\Investors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DB;

class LoginController extends Controller
{
    public function login(Request $request){
        $username = $request->username;
        $checkUsername = DB::table('investors')->whereExists('username', $username)->get();
        try {
            if(!empty($checkUsername)){
                return response()->json([
                    'Message' => 'User Succesfully login'
                ], 200);
            }else{
                return response()->json([
                    'Message' => 'You are not authorize to signin because you the username does not exist'
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
        
    }
}
