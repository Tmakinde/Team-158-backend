<?php

namespace App\Http\Controllers\Investors;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Investor;
use App\Farmer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:investors')->except('store');
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
                'fname' => ['required'],
                'lname' => ['required'],
                'email' => ['required'],
                'state' => ['required'],
                'businessProof' => ['required'],
                'insured' => ['required'],
            ]);
            if($validator->passes()){
                $userStatus = $request->status;
                $state = State::where('state', $request->state)->first();
                $investor = Investor::where('username', $request->uid)->first();
                $investor->first_name = $request->fname;
                $investor->last_name = $request->lname;
                $investor->email = $request->email;
                $investor->state_id = $state->id;
              /*  DB::table('farmers_credential')->insertGetId([
                    'insurance_paper' => $request->insured,
                    'cbr' => 'businessProof'
                ]);*/
                if($userStatus == 'connected'){
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
        $username = $request->uid;
        try {
            if($username != null && ($userStatus != 'not_authorized' || $userStatus != 'unKnown')){
                $checkUsernameForFarmer = DB::table('farmers')->where('username', $username)->first();
                $checkUsernameForInvestor = Investor::where('username', $username)->first();

                if($checkUsernameForInvestor == null && $checkUsernameForFarmer == null){
                    DB::table('investors')->insertGetId([
                        'username' => $username,
                    ]);
                    $checkUsernameForInvestor = Investor::where('username', $username)->first();
                    $api_token = Auth::guard('investors')->login($checkUsernameForInvestor);
                    $investor = Investor::where('username',$username)->first();
                    $investor->api_token = $api_token;
                    $investor->save();
                    return response()->json([
                        'api_token' => $api_token,
                        'message' => 'user successfully signup'], 200); // status code means user should continue since their data exist and valid
                }else{

                    return response()->json(['message' => 'You Already Exist as a Farmer or Investor'], 403); // user exist and forbidden to see the nextpage
                }
                
                
            }else{
                return response()->json(['message' => 'You are not authorize by facebook'], 401); // status code means user should continue since their data exist and valid
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal server Error'], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    // retrive a single investor
    {
        $uid = $request->uid;
        $investor  = Investor::find($uid);
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
       // $investor  = Investor::find($id);
        
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
            $investor = Investor::find($uid);
            if($investor != null){
                $investor->delete();
                return response()->json(['Message' => 'Investor successfully deleted'], 200);
            }else{
                return response()->json(['Message' => 'Investor does not exist'], 200);
            }
        }catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
    }

    public function listFarmer(Request $request)
    {
        try{
            $uid = $request->uid;
            $investor = Investor::find($uid);
            if($investor != null){
            $farmer = $investor->farmers;
            return response()->json([
                'Message' => $farmer
            ], 200);
            }else{
                return response()->json(['Message' => 'You do not have a farmer yet'], 200);
            }

        }catch (Exception $e) {
            return response()->json(['Message' => 'Internal server Error'], 500);
        }
    }
    
}
