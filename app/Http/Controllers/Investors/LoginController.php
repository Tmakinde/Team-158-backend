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
        $userStatus = $request->status;
        $uid = $request->uid;
        $checkUid = Investor::where('uid', $uid)->first();
        try {
            if($userStatus == "connected"){

                if ($checkUid){
                    $api_token = Auth::guard('investors')->login($checkUid);
                    return response()->json([
                        'api_token' => $api_token,
                        'message' => 'User Succesfully login'
                    ], 200);
                    
                }else{
                    return response()->json([
                        'message' => 'You need to fill the Investor Data before authorization'
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
