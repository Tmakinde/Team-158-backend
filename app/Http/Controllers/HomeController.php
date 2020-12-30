<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:farmers');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function state(Request $request)
    {
        try {
            $checker = State::where('state',$request->state)->first();
            if($request->state != null && $checker == null){
                $state = new State;
                $state->state = $request->state;
                $state->save();
                return response()->json([
                    'success' => "State successfully added",
                ], 200);
            }else{
                return response()->json([
                    'error' => "provide a valid state name",
                ], 401);
            }     
        } catch (\Tymon\JWTAuth\Exceptions\userNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 500);

        }
       
    }

}
