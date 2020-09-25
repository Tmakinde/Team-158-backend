<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DB;
use Validator;
use App\Investor;


class InvestorController extends Controller
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
    public function create( Request $request )
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required'],
                'state' => ['required'],
            ]);
            if($validator->passes()){
                $userStatus = $request->status;
                $state = State::where('state', $request->state)->first();
                $investor = Investor::where('username', $request->username)->first();
                $investor->first_name = $request->first_name;
                $investor->last_name = $request->last_name;
                $investor->email = $request->email;
                $investor->state_id = $state->id;
                if($userStatus == 'conected'){
                    $investor->status = 'online';
                }else{
                    $investor->status = 'offline';
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
        $userStatus = $request->status;
        $username = $request->username;
        try {
            if(!empty($username) && ($userStatus == 'not_authorized' || $userStatus == 'unKnown')){
                $checkUsername = DB::table('investors')->where('username', $username)->get();
                if(!empty($checkUsername)){
                    DB::table('investors')->insertGetId([
                        'username' => $username,
                    ]);
                    return response()->json(['Message' => 'Internal server Error'], 100); // status code means user should continue since their data exist and valid
                }else{

                    return response()->json(['Message' => 'User Already Exist'], 403); // user exist and forbidden to see the nextpage
                }
                
            }else{
                return response()->json(['Message' => 'You are not register with Facebook'], 401); // status code means user should continue since their data exist and valid
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

    public function show($id)
    // retrive a single investor
    {
        $investor  = Investor::find($id);
        try {
            
            if($investor != null){
                return response()->json([
                    'investorData' => $investor,
                ], 200);
            }else{
                return response()->json([
                    'Message' => 'Not found',
                ], 401);
            }
           
        } catch (\Throwable $th) {
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
    public function update(Request $request, $id)
    {
        $investor  = Investor::find($id);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
