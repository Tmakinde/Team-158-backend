<?php

namespace App\Http\Controllers\Farmers;

use Illuminate\Http\Request;
use App\DB;
use App\Farmer;
use App\Investor;
use Validator;
use App\Http\Controllers\Controller;


class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'f_name' => ['required'],
                'l_name' => ['required'],
                'email' => ['required'],
                'state' => ['required'],
                
            ]);
            if($validator->passes()){
                $uid = $request->uid;
                $userStatus = $request->status;
                $state = State::where('state', $request->state)->first();
                $farmer = Farmer::where('username', $request->uid)->first();
                $farmer->first_name = $request->f_name;
                $farmer->last_name = $request->l_name;
                $farmer->email = $request->email;
                $farmer->state_id = $state->id;
                if($userStatus == 'connected'){
                    $farmer->status = 'online';
                }else{
                    $farmer->status = 'offline';
                };
                return response()->json([
                    'Message' => 'Information saved'
                ], 200);
            }else{
                return response()->json([
                    'Error' => withErrors($validator),
                ], 500);
            }     
        } catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save username to database
        //uid
        $userStatus = $request->status;
        $username = $request->uid;
        $api_token = str_random(60);
        try {
            if(!empty($username) && ($userStatus == 'not_authorized' || $userStatus == 'unKnown')){
                $checkUsername = DB::table('farmers')->where('username', $username)->get();
                if(!empty($checkUsername)){
                    DB::table('farmers')->insertGetId([
                        'username' => $username,
                        'api_token' => $api_token,
                    ]);
                    return response()->json(['Message' => 'Successfully Signup'], 100); // status code means user should continue since their data exist and valid
                }else{

                    return response()->json(['Message' => 'User Already Exist'], 403); // user exist and forbidden to see the nextpage
                }
                
            }else{
                return response()->json(['Message' => 'You are not signin or signup with Facebook '], 401); // status code means user should continue since their data exist and valid
            }
        } catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    // retrive a single farmer
    {
        $uid = $request->uid;
        $farmer  = Farmer::find($uid);
        try {
            
            if($farmer != null){
                return response()->json([
                    'farmerData' => $farmer,
                ], 200);
            }else{
                return response()->json([
                    'Message' => 'Not found',
                ], 401);
            }
           
        } catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $uid = $request->uid;
            $farmer = Farmer::find($uid);
            if($farmer != null){
                $farmer->delete();
                return response()->json(['Message' => 'Farmer successfully deleted'], 200);
            }else{
                return response()->json(['Message' => 'Farmer does not exist'], 200);
            }
        }catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
    }
    public function listInvestor(Request $request)
    {
        try{
            $uid = $request->uid;
            $farmer = Farmer::find($uid);
            if($farmer != null){
                $investor = $farmer->investors;
                return response()->json([
                    'Message' => $investor
                ], 200);
            }else{
                return response()->json(['Message' => 'You do not have an investor yet'], 200);
        }

        }catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
    }
}
