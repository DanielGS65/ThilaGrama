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
            <div class="options-space"></div>
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
                    <button class="patient-info">
                        <p style="position:relative; top:0.4vw">Pacientes</p>
                        <img class="image" src="{{ asset('img/calendar/patient.png')}}"></img>
                    </button>  
                    <button class="status-info">
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
                                <div class="turno turno-dia">Turno de Dia</div>
                                <div class="break"></div>
                                <div class="rango">Enfermeros</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'd' and $asigment->auxiliar == 0)
                                        <p class="nombre">{{$asigment->name}}</p>
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'd' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
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
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'a' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
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
                                    @endif
                                @endforeach 
                                <div class="break"></div>
                                <div class="rango">Auxiliares</div>
                                @foreach($asigments as $asigment)
                                    @if(\Carbon\Carbon::parse($asigment->date)->day == $i and $asigment->turn == 'n' and $asigment->auxiliar == 1)
                                        <p class="nombre">{{$asigment->name}}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div id="personal{{$i}}" class="popUp-shade hidden shade-hidden">
                    <div id="personal-popUp{{$i}}" class="popUp hidden">
                        <div style="float: right">
                            <button id="{{$i}}" value="close" class="close" onclick="personalPopUp(this)">x</button>
                        </div>
                        <div style="padding-top: 45px">
                        
                        </div>
                    </div>
                </div>
                <div id="personal{{$i}}" class="popUp-shade hidden shade-hidden">
                    <div id="personal-popUp{{$i}}" class="popUp hidden">
                        <div style="float: right">
                            <button id="{{$i}}" value="close" class="close" onclick="personalPopUp(this)">x</button>
                        </div>
                        <div style="padding-top: 45px">
                        
                        </div>
                    </div>
                </div>-->
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