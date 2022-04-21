document.getElementById("event_place").addEventListener("change", address);

function activer() {
    document.getElementById('event_campusSite').disabled = false
}

window.onload = function () {
    document.getElementById('event_campusSite').disabled = true
}

//window.location.href = 'place/2';
function address() {
let id= document.getElementById("event_place").value;
    $.ajax(
        {
            url: 'place/'+id,
            method: 'GET',
            contentType: 'application/json',
            dataType:'json',
            crossDomain: true,
            headers: {
                'Access-Control-Allow-Origin': '*'
            }
        }
    )
        .done(
            (donnees) => {
                //todo reinitialiser ville
                $('#monadresse').empty();
                let nouvelElement = $('<li>rue: '+donnees.street+' </li>');
                $('#monadresse').append(nouvelElement);
                nouvelElement = $('<li>code postale: '+donnees.town.postCode+' </li>');
                $('#monadresse').append(nouvelElement);
                nouvelElement = $('<li>latitude :'+donnees.latitude+' </li>');
                $('#monadresse').append(nouvelElement);
                nouvelElement = $('<li>longitude :'+donnees.longitude+' </li>');
                $('#monadresse').append(nouvelElement);
                console.log(donnees)
                console.log(donnees.latitude)

                console.log(donnees.street)
            }
        )

}

