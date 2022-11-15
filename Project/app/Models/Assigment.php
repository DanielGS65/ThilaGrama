<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigment extends Model
{
    use HasFactory;

    protected $table = 'assigments';
    protected $primaryKey = ['hospital_Id','floorNum','turn_Id','employee_Id','date'];
    public $timestamps = false;
    public $incrementing = false;

    public function floor(){
        return $this->belongsTo(Floor::class,'floorNum','floorNum');
    }

    public function hospital(){
        return $this->belongsTo(Floor::class,'hospital_Id','hospital_Id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_Id');
    }
    public function turn(){
        return $this->belongsTo(Turn::class,'turn_Id','id');
    }
}
