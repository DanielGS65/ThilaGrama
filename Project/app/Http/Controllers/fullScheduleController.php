<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Floor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Turn;
use App\Models\Assigment;

class fullScheduleController extends Controller{
    public function viewFullSchedule(Request $request){
        setlocale(LC_ALL, 'Spain');
        $date = null;
        if(!$request->has('date')){
            $date = \Carbon\Carbon::now();
        }
        else{
            $date = $request->get('date');
            if(gettype($date) == "string"){$date = \Carbon\Carbon::parse($date);}
        }

        $startDate = (string)$date->year . "-" . (string)$date->month . "-1";
        $endDate = (string)$date->year . "-" . (string)$date->month . "-" . (string)$date->daysInMonth;

        $query = Assigment::query();
        $query = $query->join('employees','assigments.employee_Id','=','employees.id');
        $query = $query->join('turns','assigments.turn_Id','=','turns.id');
        $query = $query->whereDate('assigments.date','>=',$startDate);
        $query = $query->whereDate('assigments.date','<=',$endDate);
        $query = $query->orderBy('assigments.date', 'desc');
        $asigments = $query->get();

        return view('fullSchedule',['date' => $date,'asigments' => $asigments]);
    }

    public function changeMonth(Request $request){
        setlocale(LC_ALL, 'Spain');
        $date = $request->get('date');
        $date = \Carbon\Carbon::parse($date);
        if($request->get('action') == "next"){
            $date = $date->addMonth();
        }
        elseif($request->get('action') == "prev"){
            $date = $date->subMonth();
        }
        $request->request->add(['date' => $date]);

        return $this->viewFullSchedule($request);
    }

    public function autoassingEmployees(Request $request){
        $patients = $request->get("patients");
        $patients = $patients + 0;
        $time = $request->get("time-per-patient");
        $time = $time + 0;
        $init_date = $request->get("init-date");
        $end_date = $request->get("end-date");

        $init_date = \Carbon\Carbon::parse($init_date);
        $end_date = \Carbon\Carbon::parse($end_date);

        $enf_morn = ($patients * $time * 0.6 * 0.5)/7;
        $enf_aft = ($patients * $time * 0.6 * 0.3)/7;
        $enf_night = ($patients * $time * 0.6 * 0.2)/7;

        $aux_morn = ($patients * $time * 0.4 * 0.5)/7;
        $aux_aft = ($patients * $time * 0.4 * 0.3)/7;
        $aux_night = ($patients * $time * 0.4 * 0.2)/7;
        
        $date_aux = $init_date->copy();
        if($date_aux->dayOfWeekIso != 1){
            $date_aux->subDays($date_aux->dayOfWeekIso - 1);
        }

        $query = Employee::query();
        $query = $query->where('auxiliar','=',false);
        $employees = $query->get();

        $this->assing($employees,$date_aux,$init_date,$end_date,$enf_morn,$enf_aft,$enf_night);

        $init_date = $request->get("init-date");
        $end_date = $request->get("end-date");

        $init_date = \Carbon\Carbon::parse($init_date);
        $end_date = \Carbon\Carbon::parse($end_date);

        $date_aux = $init_date->copy();
        if($date_aux->dayOfWeekIso != 1){
            $date_aux->subDays($date_aux->dayOfWeekIso - 1);
        }

        $query = Employee::query();
        $query = $query->where('auxiliar','=',true);
        $aux = $query->get();

        $this->assing($aux,$date_aux,$init_date,$end_date,$aux_morn,$aux_aft,$aux_night);

        return $this->viewFullSchedule($request);
    }

    public function assing($employees, $date,$init_date,$end_date,$morn,$aft,$night){
        //$i = $date->dayOfWeekIso;
        $turn_day = Turn::where('id',1)->first();
        $turn_aft = Turn::where('id',2)->first();
        $turn_night = Turn::where('id',3)->first();
        while($date <= $end_date){

            for($i = 1; $i <= 7; $i= $i + 1){
                $contDay = 0;
                $contAft = 0;
                $contNight = 0;
                $stop = false;

                foreach($employees as $employee){
                    $last_assigment = null;
                    $query = Assigment::query();
                    $query = $query->join('employees','assigments.employee_Id','=','employees.id');
                    $query = $query->where('assigments.employee_Id',$employee->id);
                    $query = $query->orderBy('assigments.date', 'desc');
                    if($query->exists()){
                        $last_assigment = $query->first();
                    }
                    
                    $assigment = new Assigment();
                    $floor =Floor::where('name','Traumatogia')->first();
                    $assigment->floor()->associate($floor);
                    $assigment->hospital()->associate($floor); 
                    if($last_assigment != null){
                        if($contDay > $morn and $contAft > $aft and $contNight > $night){
                            $stop = true;
                        }
                        else{
                            $stress = 0;
                            if($date->dayOfWeekIso != 1){
                                $stress = $last_assigment->stress;
                            }

                            if($last_assigment->turnProgress == 1 and $stress < 36 and $contAft <= $aft){
                                $assigment->employee()->associate($employee);
                                $assigment->turn()->associate($turn_aft);
                                $assigment->date = $date;
                                $assigment->turnProgress = 2;
                                $assigment->turnType = "d-t-n";
                                $assigment->stress = $stress + $turn_aft->time;
                                $assigment->save();
                                $contAft = $contAft + 1;
                            }
                            elseif($last_assigment->turnProgress == 2 and $stress < 36 and $contNight <= $night){
                                $assigment->employee()->associate($employee);
                                $assigment->turn()->associate($turn_night);
                                $assigment->date = $date;
                                $assigment->turnProgress = 3;
                                $assigment->turnType = "d-t-n";
                                $assigment->stress = $stress + $turn_night->time;
                                $assigment->save();
                                $contNight = $contNight + 1;
                            }
                            elseif($last_assigment->turnProgress == 3 and $stress < 36 and $contDay <= $morn){
                                $last_date = \Carbon\Carbon::parse($last_assigment->date);
                                $last_date = $last_date->addDays(2);
                                if($date > $last_date){
                                    $assigment->employee()->associate($employee);
                                    $assigment->turn()->associate($turn_day);
                                    $assigment->date = $date;
                                    $assigment->turnProgress = 1;
                                    $assigment->turnType = "d-t-n";
                                    $assigment->stress = $stress + $turn_day->time;
                                    $assigment->save();
                                    $contDay = $contDay + 1;
                                }
                            }
                        }
                    }
                    else{
                        if($contDay <= $morn){
                            $assigment->employee()->associate($employee);
                            $assigment->turn()->associate($turn_day);
                            $assigment->date = $date;
                            $assigment->turnProgress = 1;
                            $assigment->turnType = "d-t-n";
                            $assigment->stress = $turn_day->time;
                            $assigment->save();
                            $contDay = $contDay + 1;
                        }
                        elseif($contAft <= $aft){
                            $assigment->employee()->associate($employee);
                            $assigment->turn()->associate($turn_aft);
                            $assigment->date = $date;
                            $assigment->turnProgress = 2;
                            $assigment->turnType = "d-t-n";
                            $assigment->stress = $turn_aft->time;
                            $assigment->save();
                            $contAft = $contAft + 1;
                        }
                        elseif($contNight <= $night){
                            $assigment->employee()->associate($employee);
                            $assigment->turn()->associate($turn_night);
                            $assigment->date = $date;
                            $assigment->turnProgress = 3;
                            $assigment->turnType = "d-t-n";
                            $assigment->stress = $turn_night->time;
                            $assigment->save();
                            $contNight = $contNight + 1;
                        }
                        else{
                            $stop = true;
                        }
                        
                    }
                    if($stop){
                        break;
                    }
                }
                $date->addDays(1);
            }           
        }
    }
}
