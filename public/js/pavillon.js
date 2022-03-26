function yesnoCheck(that) {
    if(that.value == "affect"){
        document.getElementById("affecter").style.display = "block";
    }else{
        document.getElementById("affecter").style.display = "none";
    }

    if(that.value == "add"){
        document.getElementById("ajouter").style.display = "flex";
    }else{
        document.getElementById("ajouter").style.display = "none";
    }

}