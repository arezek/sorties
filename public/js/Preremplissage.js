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
    let selection = document.getElementById("optionSelectLieu").value;
    let tabSelection = selection.split('/');

    console.log(selection.split('/'));

    document.getElementById("rue").textContent = "Rue : " + tabSelection[1];
    document.getElementById("codePostal").textContent = "Code postal : " + tabSelection[2];
    document.getElementById("latitude").textContent = "Latitude : " + tabSelection[3];
    document.getElementById("longitude").textContent = "Longitude : " + tabSelection[4];
}

function Search(){
    let selection = document.getElementById("campusSelect").value;
    //var tabSelection = selection.split('/');

    console.log(selection);

    document.getElementById("rue").textContent = "Rue : " + tabSelection[1];
    document.getElementById("codePostal").textContent = "Code postal : " + tabSelection[2];
    document.getElementById("latitude").textContent = "Latitude : " + tabSelection[3];
    document.getElementById("longitude").textContent = "Longitude : " + tabSelection[4];


}
