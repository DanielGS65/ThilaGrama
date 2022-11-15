function collapseHeader(btn){
    var button = document.getElementById(btn.id);
    var header = document.getElementById("header");

    if(btn.value == "open"){
        header.classList.remove("close");
        button.classList.remove("collapse-close");
        button.classList.add("collapse-open");
        button.value = "close"
    }
    else{
        header.classList.add("close");
        button.classList.add("collapse-close");
        button.classList.remove("collapse-open");
        button.value = "open"
    }
}