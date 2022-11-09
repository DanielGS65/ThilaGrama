<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $primaryKey = ['hospital_Id','floorNum','patient_Id'];
    public $timestamps = false;
    public $incrementing = false;

    public function floor(){
        return $this->belongsTo(Floor::class,'floorNum','floorNum');
    }

    public function hospital(){
        return $this->belongsTo(Floor::class,'hospital_Id','hospital_Id');
    }

    public function patient(){
        return $this->belongsTo(Patient::class,'patient_Id');
    }
}
