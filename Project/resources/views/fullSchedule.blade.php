<html>
<head>
 <link rel="stylesheet" href="{{ asset('css/fullSchedule.css') }}" />
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1"></meta>
<body>  
    @include('header') 
    <?php 
        setlocale(LC_ALL, 'Spain');
    ?>
    <div class="flex-container schedule-header">
        <button class="change-date"><</button>
        <div class="date">
            <p class="month">{{strtoupper(date('F'))}}</p>
            <p class="year">{{date('Y')}}</p>
        </div>
        <button class="change-date">></button>
        <div class="flex-container display-right">
            <button class="selection">Dia</button>
            <button class="selection">Semana</button>
            <button class="selection">Mes</button>
        </div>
    </div>
    <div class="flex-container">
        <div class="schedule-cell"><div>
    </div>
</body>
</html>