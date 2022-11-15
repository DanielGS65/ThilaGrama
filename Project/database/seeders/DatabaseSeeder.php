<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Hospital;
use App\Models\Floor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Turn;
use App\Models\Assigment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Europe/Madrid');
        DB::table('hospitals')->delete();
        DB::table('floors')->delete();
        DB::table('patients')->delete();
        DB::table('employees')->delete();
        DB::table('appointments')->delete();
        DB::table('turns')->delete();
        DB::table('assigments')->delete();
        
        $hospital = new Hospital();
        $hospital->name = 'testHospital';
        $hospital->save();

        $floor = new Floor();
        $floor->floorNum = 1;
        $floor->name = "Traumatogia";
        $floor->hospital()->associate($hospital);
        $floor->save();

        for($i=0; $i<10; $i++){
            $patient = new Patient();
            $patient->name = "patient" .(string)$i;
            $patient->email = "patient" . (string)$i . "@gmail.com";
            $patient->phone = "64452660" . (string)$i;
            $patient->nif = "4856930" . (string)$i . "A";
            $patient->save();

            $appointment = new Appointment();
            $appointment->floor()->associate($floor);
            $appointment->hospital()->associate($floor);
            $appointment->patient()->associate($patient);
            $appointment->start_date = \Carbon\Carbon::now();
            $appointment->end_date = \Carbon\Carbon::now()->addMonth();
            $appointment->save();
        }

        $turn_day = new Turn();
        $turn_aft = new Turn();
        $turn_night = new Turn();

        $turn_day->turn = 'd';
        $turn_aft->turn = 'a';
        $turn_night->turn = 'n';

        $turn_day->time = 7;
        $turn_aft->time = 7;
        $turn_night->time = 10;

        $turn_day->save();
        $turn_aft->save();
        $turn_night->save();

        for($i=0; $i<10; $i++){
            $employee = new Employee();
            if($i%2 == 0){
                $employee->name = ("Enfermera".(string)$i);
                $employee->email = "enfermera" . (string)$i . "@gmail.com";
                $employee->phone = "6553560" . (string)$i;
                $employee->auxiliar = false;
            }
            else{
                $employee->name = ("Auxiliar".(string)$i);
                $employee->email = "auxiliar" . (string)$i . "@gmail.com";
                $employee->phone = "6553560" . (string)$i;
                $employee->auxiliar = true;
            }
            
            $employee->save();
        }

        $employee = Employee::where('auxiliar',0)->first();

        /*$assigment = new Assigment();
        $assigment->employee()->associate($employee);
        $assigment->turn()->associate($turn_day);
        $assigment->floor()->associate($floor);
        $assigment->hospital()->associate($floor);
        $assigment->date = \Carbon\Carbon::now();
        $assigment->turnProgress = 1;
        $assigment->turnType = "d-t-n";
        $assigment->save();*/
    }
}
