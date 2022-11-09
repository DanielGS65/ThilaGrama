<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class fullScheduleController extends Controller{
    public function viewFullSchedule(Request $request){
        return view('fullSchedule');
    }
}