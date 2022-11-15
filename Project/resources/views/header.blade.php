<html>
<head>
<title>ThilaGrama</title>
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
<link rel="icon" href="{{ asset('img/header/favicon-32x32.png')}}" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div id="header" class="background display close">
        <div align="right" class="menu" style="width:33vw; left:150px">
            <button class="header-tab" >HORARIO</button>
            <button class="header-tab" style="margin-left:50px">PERSONAL</button>
        </div>
        <video class="logo" autoplay loop muted>
            <source type="video/webm" src="{{ asset('img/header/logo.webm')}}">
        </video>
        <div align="left" class="menu" style="width:33vw; right:150px">
            <button class="header-tab" style="margin-right:50px">GESTIÓN</button>
            <button class="header-tab">CONFIGURACIÓN</button>
        </div>
    </div>
    <div class="background display" style="height: 0vw">
        <button id="collapse-button" value="open" class="button-collapse collapse-close" onclick="collapseHeader(this)">≡</button>
    </div>
<script src="{{ asset('js/header.js') }}"></script>
</body>
</html>