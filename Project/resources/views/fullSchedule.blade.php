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
            <button id="prev" value="{{$date}}" class="change-date" onclick="changeMonth(this)"><</button>
            <div class="date">
                <p class="month">{{strtoupper($date->format('F'))}}</p>
                <p class="year">{{$date->format('Y')}}</p>
            </div>
            <button id="next" value="{{$date}}" class="change-date" onclick="changeMonth(this)">></button>
            <div class="flex-container display-right">
                <button class="selection">Dia</button>
                <button class="selection">Semana</button>
                <button class="selection">Mes</button>
            </div>
        </div>
        <div class = "flex-container">
        <div class="flex-container-calendar">
            @for ($j = 1; $j < $firstDay; $j++)
                <div class="schedule-cell">
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
                <div class="schedule-cell">
                    <button class="personal-info">
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
            @endfor
            <?php $count = 7-$count; ?>
            @for($i = 1; $i <= $count; $i++)
                <div class="schedule-cell">
                    <div class="flex-container-calendar">
                        <p class="day-invalid">{{$i}}</p>
                    </div>
                </div>
            @endfor
        </div>
        </div> 
<script>
    function changeMonth(btn){
        event.preventDefault();
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var action = btn.id;
        var date = btn.value;

        $.ajax({
            url:"/changeMonth",
            type:"get",
            data:{
                CSRF_TOKEN,
                action: action,
                date: date
            },
            success:function(data){
                $("#calendar").html(data);
            }
        })
    }
</script>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
</script>
</div> 
</body>
</html>