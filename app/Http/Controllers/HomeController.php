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
        $this->middleware('auth')->except('csv');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(){
        return view('home');
    }

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
    // posted test for csv result;
    public function csv(Request $request){
        $csv = $request->csv;
        //dd(explode(',', $request->header('Accept')));
        
        $allowKeys = ['first_name','last_name'];

        $FNandLN = array_filter(
            $csv,
            function ($key) use ($allowKeys) {
                return in_array($key, $allowKeys);
            },
            ARRAY_FILTER_USE_KEY
        );

        $listOfNames = [[]];

        $count = count($FNandLN);
        for ($i=0; $i < $count; $i++) { 
            
            foreach ($FNandLN as $key => $value) {
                $listOfName[$i][$key] = $value[$i];
                
            }

        }
        
        return response()->json([
            'tokens' => 'rreRpt1fGkKIlHmigZyfDJ9m7Us/Ga7kat0rGo4hFBIIlHmigZyfDJ9m7Us',
            'csv' => $listOfName,
        ]);
    }


}
