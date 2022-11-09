<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssigmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigments', function (Blueprint $table) {
            $table->integer('floorNum');
            $table->unsignedBigInteger('hospital_Id');
            $table->unsignedBigInteger('employee_Id');
            $table->unsignedBigInteger('turn_Id');

            $table->foreign(['hospital_Id','floorNum'])->references(['hospital_Id','floorNum'])->on('floors')->onDelete('cascade');
            $table->foreign('employee_Id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('turn_Id')->references('id')->on('turns')->onDelete('cascade');

            $table->primary(['hospital_Id','floorNum',"employee_Id","turn_Id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assigments');
    }
}
