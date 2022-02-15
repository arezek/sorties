let ready = $(document).ready(
    function (){
        $("#optionSelect").click(function (event){

            var contenu = "hey";
            console.log(contenu);
            $("#rue").textContent = "hey";
        })
    }
);

function choix(){
    var idSelectionne = document.getElementById("optionSelectLieu").value;

    document.getElementById("rue").textContent = document.getElementById("optionSelectLieu").value;
    document.getElementById("codePostal").textContent = document.getElementById("optionSelectLieu").value;

}