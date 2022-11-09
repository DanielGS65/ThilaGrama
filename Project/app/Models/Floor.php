<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'floors';
    protected $primaryKey = ['hospital_Id','floorNum'];
    public $timestamps = false;
    public $incrementing = false;

    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_Id');
    }
}
