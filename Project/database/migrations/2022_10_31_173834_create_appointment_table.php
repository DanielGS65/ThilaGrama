<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->integer('floorNum');
            $table->unsignedBigInteger('hospital_Id');
            $table->unsignedBigInteger('patient_Id');

            //$table->foreign('hospital_Id')->references('hospital_Id')->on('floors')->onDelete('cascade');
            $table->foreign(['hospital_Id','floorNum'])->references(['hospital_Id','floorNum'])->on('floors')->onDelete('cascade');
            $table->foreign('patient_Id')->references('id')->on('patients')->onDelete('cascade');

            $table->primary(['hospital_Id','floorNum',"patient_Id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
