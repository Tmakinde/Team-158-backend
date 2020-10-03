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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function state(Request $request)
    {try {
       
        if($request->state != null){
            $state = new State;
            $state = $request->state;
            $state->save();
            return response()->json([
                'success' => "State successfully added",
            ], 200);
        }else{
            return response()->json([
                'Error' => "provide a valid state name",
            ], 401);
        }     
    } catch (Exception $e) {
        return response()->json(['Message' => 'Internal server Error'], 500);

    }
       
    }

}
