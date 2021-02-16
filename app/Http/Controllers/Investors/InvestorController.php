<?php

namespace App\Http\Controllers\Investors;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Investor;
use App\Farmer;
use App\State;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvestorResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateInvestorRequest;
use App\Http\Requests\CreateFarmerRequest;


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

    public function index()
    {
        $investor = Investor::all();
        return InvestorResource::collection($investor);
    }

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
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            '*.type' => 'required|in:Investor',
            '*.attributes' => 'required|array',
            '*.attributes.f_name' => 'required|string',
            '*.attributes.l_name' => 'required|string',
            '*.attributes.email' => 'required|email|unique:investors',
            '*.attributes.state' => 'required|string',
            '*.attributes.status' => 'required|string',
            '*.attributes.uid'=> 'required|string|unique:investors',
        ]);
        try {  
            if ($validator->passes()) {
                if($request->json('data.attributes.status') == "connected"){
                    $userStatus = $request->json('data.attributes.status');
                    $state = State::where('state', $request->json('data.attributes.state'))->first();
                    $investor = new Investor;
                    $investor->first_name = $request->json('data.attributes.f_name');
                    $investor->uid = $request->json('data.attributes.uid'); // userId
                    $investor->lastname = $request->json('data.attributes.l_name');
                    $investor->email = $request->json('data.attributes.email');
                    $investor->state_id = $state->id;
                    if($userStatus == 'connected'){
                        $investor->status = 'online';
                    }else{
                        $investor->status = 'offline';
                    };
                    $investor->save();
                    $investor = Investor::where('uid', $request->json('data.attributes.uid'))->first();
                    $api_token = Auth::guard('investors')->login($investor);
                    $investor->api_token = $api_token;
                    $investor->save();

                    return new InvestorResource($investor);
                }else{
                    return \response()->json([
                        "message" => "No authorize",
                    ]);
                }
            }else{
                return \response()->json(['errors' => $validator->errors()], 401);
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

    public function show(Request $request, $id)
    // retrive a single investor
    {
        try {
            $investor  = Investor::find($id);
            return new InvestorResource($investor);
           
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
    public function destroy(Request $request, $uid)
    {
        try{
            $investor = Investor::find($uid);
            if($investor != null){
                $investor->delete();
                return response()->json(['message' => 'Investor successfully deleted'], 200);
            }else{
                return response()->json(['message' => 'Investor does not exist'], 200);
            }
        }catch (Exception $e) {
            return response()->json(['message' => 'Internal server Error'], 500);
        }
    }

    public function listFarmer(Request $request, $uid)
    {
        try{
            
            $investor = Investor::find($uid);
            if($investor != null){
            $farmersUnderInvestor = $investor->farmers;
            return response()->json([
                'data' => $farmersUnderInvestor,
            ], 200);
            }else{
                return response()->json(['message' => 'You do not have a farmer yet'], 200);
            }

        }catch (Exception $e) {
            return response()->json(['message' => 'Internal server Error'], 500);
        }
    }
    
}
