function yesnoCheck(that) {
    if (that.value == "nonBoursier") {
        document.getElementById("Section1").style.display = "block";

    } else {
        document.getElementById("Section1").style.display = "none";

    }
    if(that.value == "boursierNL"){
        document.getElementById("Section2").style.display = "block";
    }else{
        document.getElementById("Section2").style.display = "none";

    }

    

}
