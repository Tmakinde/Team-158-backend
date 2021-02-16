<?php

namespace App\Http\Controllers\Farmers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\Farmer;
use App\Investor;
use App\State;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmersResource;
use App\Http\Requests\CreateFarmerRequest;
use Illuminate\Support\Str;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:farmers')->except('store');
    }

    public function index()
    {
        $farmer = Farmer::all();
        return FarmersResource::collection($farmer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public $customAttributes = [
        'data' => 'data',
        '*.type' => 'type',
        '*.attributes' => 'atttributes',
        '*.attributes.f_name' => 'First Name',
        '*.attributes.l_name' => 'Last Name',
        '*.attributes.email' => 'Email',
        '*.attributes.state' => 'State',
        '*.attributes.status' => 'Status',
        '*.attributes.uid'=> 'Unique Id',
    ];

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            '*.type' => 'required|in:Farmer',
            '*.attributes' => 'required|array',
            '*.attributes.f_name' => 'required|string',
            '*.attributes.l_name' => 'required|string',
            '*.attributes.email' => 'required|email|unique:farmers',
            '*.attributes.state' => 'required|string',
            '*.attributes.status' => 'required|string',
            '*.attributes.uid'=> 'required|string|unique:farmers',
        ]);
        
        try {  
            if ($validator->passes()) {
                if($request->json('data.attributes.status') == "connected"){
                    $userStatus = $request->status;
                    $state = State::where('state', $request->json('data.attributes.state'))->first();
                    $farmer = new Farmer;
                    $farmer->first_name = $request->json('data.attributes.f_name');
                    $farmer->uid = $request->json('data.attributes.uid'); // userId
                    $farmer->lastname = $request->json('data.attributes.l_name');
                    $farmer->email = $request->json('data.attributes.email');
                    $farmer->state_id = $state->id;
                    if($userStatus == 'connected'){
                        $farmer->status = 'online';
                    }else{
                        $farmer->status = 'offline';
                    };
                    $farmer->save();
                    $farmer = Farmer::where('uid', $request->json('data.attributes.uid'))->first();
                    $api_token = Auth::guard('farmers')->login($farmer);
                    $farmer->api_token = $api_token;
                    $farmer->save();
                    
                    return new FarmersResource($farmer);
                    
                    
                }else{
                    return \response()->json([
                        "message" => "No authorize",
                    ]);
                }
            } else {
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
    // retrive a single farmer
    {
        
        try {
            $farmer  = Farmer::find($id);
            return new FarmersResource($farmer);
           
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
    public function destroy(Request $request, $uid)
    {
        try{
            $farmer = Farmer::find($uid);
            if($farmer != null){
                $farmer->delete();
                return response()->json(['message' => 'Farmer successfully deleted'], 200);
            }else{
                return response()->json(['message' => 'Farmer does not exist'], 200);
            }
        }catch (Exception $e) {
            return response()->json(['message' => 'Internal server Error'], 500);
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
