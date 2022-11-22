<html>
<head>
 <link rel="stylesheet" href="{{ asset('css/fullSchedule.css') }}" />
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1"></meta>
<body>  
    <div id="calendar" class="app">
    @include('header') 
        <?php
            setlocale(LC_ALL, 'Spain');
            $dateAux = $date->copy();
            $daysCount = $dateAux->daysInMonth;
            $firstDay = $dateAux->firstOfMonth()->dayOfWeek;
            $lastMonthDays = $dateAux->subMonth()->daysInMonth;  
        ?>
        <div class="flex-container schedule-header">
            <button id="optionButton" value="open" class="options" onclick="optionsColapse(this)"> <img class="options" src="{{ asset('img/calendar/config.png')}}"></img></button>
            <button id="prev" value="{{$date}}" class="change-date" onclick="changeMonth(this)"><</button>
            <div class="date">
                <p class="month">{{strtoupper($date->format('F'))}}</p>
                <p class="year">{{$date->format('Y')}}</p>
            </div>
            <button id="next" value="{{$date}}" class="change-date" onclick="changeMonth(this)">></button>
            <div class="flex-container display-right">
                <button class="selection">Dia</button>
                <button class="selection">Semana</button>
                <button class="selection-selected">Mes</button>
            </div>
        <form method="POST" action="{{ route('autoAssign') }}">
        @csrf
        </div>
        <div id="optionBox" class="options-collapse options-close options-hidden">
            <p class="options-text">Generación Automática del Horario</p>
            <div class="break"></div>
            <input name="patients" class="options-high" style="width:10vw;position:relative;left:2.5vw" type="range" value="0" min="0" max="99" step="1" oninput="updateRangePatient(this)"></input>
            <div id="range-counter" style="position:relative;left:3vw" class="options-counter options-high">0</div>
            <p class="options-text options-low" style="right:4.5vw">Estimación de Pacientes</p>
            <input name="time-per-patient" class="options-high" style="width:5vw;position:relative;right:11vw" type="range" value="0.5" min="0.5" max="24" step="0.5" oninput="updateRangeTime(this)"></input>
            <div id="hours-counter" class="options-counter options-high" style="left:-10vw; width:100px" >0.5 hr</div>
            <input name="init-date" class="options-date options-high" style="right:5vw" type="date"></input>
            <p class="options-text options-low" style="right:14.1vw">Fecha de Inicio</p>
            <input name="end-date" class="options-date options-high" style="right:4.7vw" type="date"></input>
            <p class="options-text options-low" style="right:15.9vw">Fecha de Finalización</p>
            <input name="date" style="visibility: hidden; width:0px" value={{$date}}></input>
            <button id="action" value="add" class="options-button apply options-high" style="position:relative;right:5vw">Aplicar</button>
            <button id="action" value="erase" class="options-button erase options-high" style="position:relative;right:2.4vw">Eliminar</button>            
        </div>
        </form>
        <?php 
            $today = \Carbon\Carbon::now();
            $currentMonth = $today->month;
            $selectedMonth = $date->month;
            $currentDay = $today->day;
        ?>
        <div class = "flex-container">
        <div class="flex-container-calendar">
            @for ($j = 1; $j < $firstDay; $j++)
                @if(($currentMonth == ($selectedMonth - 1)) and ($currentDay == ($lastMonthDays - ($firstDay - $j - 1))))  
                <div class="schedule-cell currentDay">
                @else
                <div class="schedule-cell">
                @endif
                    <div class="flex-container-calendar">
                        <p class="day-invalid">{{$lastMonthDays - ($firstDay - $j - 1)}}</p>
                    </div>
                </div>
            @endfor
            <?php 
                $count = $firstDay -1;
                if($count < 0){$count = 0;}
            ?>
            @for ($i = 1; $i <= $daysCount; $i++)
                <?php $count = $count + 1?>
                @if(($currentMonth == $selectedMonth) and ($currentDay == $i))  
                <div class="schedule-cell currentDay">
                @else
                <div class="schedule-cell">
                @endif
                    <button id="{{$i}}" value="open" onclick="personalPopUp(this)" class="personal-info">
                        <p style="position:relative; top:0.4vw">Personal</p>
                        <img class="image" src="{{ asset('img/calendar/personal.png')}}"></img>
                    </button>
                    @if($count <= 5)
                        <p class="day">{{$i}}</p>
                    @else
                        <p class="day" style="color: rgba(0,100,180,0.6)">{{$i}}</p>
                    @endif
                    <button id="{{$i}}" value="open" onclick="patientPopUp(this)" class="patient-info">
                        <p style="position:relative; top:0.4vw">Pacientes</p>
                        <img class="image" src="{{ asset('img/calendar/patient.png')}}"></img>
                    </button>  
                    <button id="infoButton{{$i}}" name="{{$i}}" value="open" onclick="infoPopUp(this)" class="status-info">
                        <p style="position:relative; bottom:0.1vw">i</p>
                    </button>     
                </div>
                <?php if($count == 7){$count = 0;} ?>
                <div id="personal{{$i}}" class="popUp-shade hidden shade-hidden">
                    <div id="personal-popUp{{$i}}" class="popUp hidden">
                        <div style="float: right">
                            <button id="{{$i}}" name="close{{$i}}" value="close" class="close" onclick="personalPopUp(this)">x</button>
                        </div>
                        <div class="popUp-Content">
                            <div>
                                <?php
                                $contEnfDay = 0;
                                $contEnfAft = 0;
                                $contEnfNight = 0;

                                $contAuxDay = 0;
                                $contAuxAft = 0;
                                $contAuxNight = 0;
                                ?>
                                <div class="turno turno-dia">Turno de Dia</div>
                                <div class="break"></div>
                                <div class="rango">Enfermeros</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'd' and $asigment->auxiliar == 0)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contEnfDay = $contEnfDay + 1 ?>
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'd' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contAuxDay = $contAuxDay + 1 ?>
                                    @endif
                                @endforeach
                            </div>
                            <div>
                                <div class="turno turno-tarde">Turno de Tarde</div>
                                <div class="break"></div>
                                <div class="rango">Enfermeros</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'a' and $asigment->auxiliar == 0)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contEnfAft = $contEnfAft + 1 ?>
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'a' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contAuxAft = $contAuxAft + 1 ?>
                                    @endif
                                @endforeach
                            </div>
                            <div>
                                <div class="turno turno-noche">Turno de Noche</div>
                                <div class="break"></div>
                                <div class="rango">Enfermeros</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'n' and $asigment->auxiliar == 0)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contEnfNight = $contEnfNight + 1 ?>
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'n' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
                                        <?php $contAuxNight = $contAuxNight + 1 ?>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div id="patient{{$i}}" class="popUp-shade hidden shade-hidden">
                    <div id="patient-popUp{{$i}}" class="popUp hidden">
                        <div style="float: right">
                            <button id="{{$i}}" name="close{{$i}}" value="close" class="close" onclick="patientPopUp(this)">x</button>
                        </div>
                        <div class="popUp-Content">
                            <div>
                                <div class="patients-header">Pacientes</div>
                                <div style="height: 20px"></div>
                                <?php $contPatients = 0; ?>
                                @foreach($appointments as $appointment)
                                    <?php 
                                        $appointment_start_date = \Carbon\Carbon::parse($appointment->start_date); 
                                        $appointment_end_date = \Carbon\Carbon::parse($appointment->end_date);
                                    ?>
                                    @if((($appointment_start_date->day <= $i and $appointment_start_date->month == $date->month) or $appointment_start_date->month < $date->month) and (($appointment_end_date->day >= $i and $appointment_end_date->month == $date->month) or $appointment_end_date->month > $date->month))
                                        <p class="nombre">{{$appointment->name}}</p>
                                        <?php $contPatients = $contPatients + 1; ?>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    $empty = false;
                    $critical = false;
                    $warning = false;
                    $good = false;

                    $enf_morn = 0;
                    $enf_aft = 0;
                    $enf_night = 0;
                    $aux_morn = 0;
                    $aux_aft = 0;
                    $aux_night = 0;

                    if($contPatients > 0){
                        $enf_morn = ($contPatients * 2.50 * 0.6 * 0.5)/7;
                        $enf_aft = ($contPatients * 2.50 * 0.6 * 0.3)/7;
                        $enf_night = ($contPatients * 2.50 * 0.6 * 0.2)/7;

                        $aux_morn = ($contPatients * 2.50 * 0.4 * 0.5)/7;
                        $aux_aft = ($contPatients * 2.50 * 0.4 * 0.3)/7;
                        $aux_night = ($contPatients * 2.50 * 0.4 * 0.2)/7;

                        if($contEnfDay == 0 or $contEnfAft == 0 or $contEnfNight == 0 or $contAuxDay == 0 or $contAuxAft == 0 or $contAuxNight == 0){
                            $critical = true;
                        }
                        elseif($contEnfDay < $enf_morn or $contEnfAft < $enf_aft or $contEnfNight < $enf_night or $contAuxDay < $aux_morn or $contAuxAft < $aux_aft or $contAuxNight < $aux_night){
                            $warning = true;
                        }
                        else{
                            $good = true;
                        }
                    }
                    else{
                        $empty = true;
                    }
                ?>
                @if($warning)
                <script i="{{$i}}">
                    var i = document.currentScript.getAttribute('i'); 
                    var btn = document.getElementById("infoButton" + i);
                    btn.classList.add("warning");
                </script>
                @elseif($critical)
                <script i="{{$i}}">
                    var i = document.currentScript.getAttribute('i'); 
                    var btn = document.getElementById("infoButton" + i);
                    btn.classList.add("critical");
                </script>
                @elseif($good)
                <script i="{{$i}}">
                    var i = document.currentScript.getAttribute('i'); 
                    var btn = document.getElementById("infoButton" + i);
                    btn.classList.add("good");
                </script>
                @else
                <script i="{{$i}}">
                    var i = document.currentScript.getAttribute('i'); 
                    var btn = document.getElementById("infoButton" + i);
                    btn.classList.add("empty");
                </script>
                @endif
                <div id="info{{$i}}" class="popUp-shade hidden shade-hidden">
                    <div id="info-popUp{{$i}}" class="popUp hidden">
                        <div style="float: right">
                            <button name="{{$i}}" id="close{{$i}}" value="close" class="close" onclick="infoPopUp(this)">x</button>
                        </div>
                        <div class="popUp-Content">
                            <div>
                                <div class="info-header">Estadisticas</div>
                                <div style="height: 20px"></div>
                                <p class="rango">Mañana</p>
                                <p class="nombre">  Enfermeros/as : {{$contEnfDay}}->{{(int)$enf_morn + 1}}</p>
                                <p class="nombre">  Auxiliares : {{$contAuxDay}}->{{(int)$aux_morn + 1}}</p>
                                <div style="height: 20px"></div>
                                <p class="rango">Tarde</p>
                                <p class="nombre">  Enfermeros/as : {{$contEnfAft}}->{{(int)$enf_aft + 1}}</p>
                                <p class="nombre">  Auxiliares : {{$contAuxAft}}->{{(int)$aux_aft + 1}}</p>
                                <div style="height: 20px"></div>
                                <p class="rango">Noche</p>
                                <p class="nombre">  Enfermeros/as : {{$contEnfNight}}->{{(int)$enf_night + 1}}</p>
                                <p class="nombre">  Auxiliares : {{$contAuxNight}}->{{(int)$aux_night + 1}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            <?php $count = 7-$count; ?>
            @for($i = 1; $i <= $count; $i++)
                @if(($currentMonth == $selectedMonth + 1) and ($currentDay == $i))  
                <div class="schedule-cell currentDay">
                @else
                <div class="schedule-cell">
                @endif
                    <div class="flex-container-calendar">
                        <p class="day-invalid">{{$i}}</p>
                    </div>
                </div>
            @endfor
        </div>
        </div> 
<script src="{{ asset('js/fullSchedule.js') }}"></script>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
</script>
</div> 
</body>
</html>