function personalPopUp(btn){
    var shader = document.getElementById("personal" + btn.id);
    var popUp = document.getElementById("personal-popUp" + btn.id);
    var close = document.getElementsByName("close" + btn.id);
    var button = document.getElementById("collapse-button");
    if(btn.value == "open"){
        shader.classList.remove('shade-hidden');
        shader.classList.remove('hidden');
        popUp.classList.remove('hidden');
        button.classList.add('hidden');
    }
    else{
        popUp.classList.add('hidden');
        shader.classList.add('shade-hidden');
        button.classList.remove('hidden');
        setTimeout(() =>shader.classList.add('hidden'), 300);
       
    }
}

function optionsColapse(btn){
    var box = document.getElementById("optionBox");
    var button = document.getElementById(btn.id);

    if(btn.value == "open"){
        box.classList.remove("options-hidden");
        box.classList.remove("options-close");
        button.value = "close";
    }
    else{
        box.classList.add('options-close');
        setTimeout(() =>box.classList.add('options-hidden'), 40);
        button.value = "open";    
    }
}

function updateRangePatient(btn){
    counter = document.getElementById("range-counter");
    counter.innerHTML = btn.value;
}

function updateRangeTime(btn){
    counter = document.getElementById("hours-counter");
    counter.innerHTML = btn.value + ' hr';
}

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


