<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class fullScheduleController extends Controller{
    public function viewFullSchedule(Request $request){
        $date = null;
        if(!$request->has('date')){
            $date = \Carbon\Carbon::now();
        }
        return view('fullSchedule',['date' => $date]);
    }

    public function changeMonth(Request $request){
        $date = $request->get('date');
        $date = \Carbon\Carbon::parse($date);
        //$date = \Carbon\Carbon::now()->addMonth();
        if($request->get('action') == "next"){
            $date = $date->addMonth();
        }
        else{
            $date = $date->subMonth();
        }
        $request->request->add(['date' => $date]);

        return view('fullSchedule',['date' => $date]);
    }
}