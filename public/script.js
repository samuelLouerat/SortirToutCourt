let $SelectedAddress;
function callShowAddress(){

    let pBox= $('#address');
    pBox.contents().remove();

    pBox.append(` <label for="street">rue</label>`)
    pBox.append(`<input id="street" name="street" value=${SelectedAddress[document.getElementById("Town").options[document.getElementById('Town').selectedIndex].value].properties.name}>`);

    pBox.append(` <label for="city">ville</label>`)
    pBox.append(`<input id="city" value=${SelectedAddress[document.getElementById("Town").options[document.getElementById('Town').selectedIndex].value].properties.city}>`);

    pBox.append(` <label for="postCode">code postal</label>`)
    pBox.append(`<input id="postCode" value=${SelectedAddress[document.getElementById("Town").options[document.getElementById('Town').selectedIndex].value].properties.postcode}>`);

    pBox.append(` <label for="longitude">longitude</label>`)
    pBox.append(`<input id="longitude" value=${SelectedAddress[document.getElementById("Town").options[document.getElementById('Town').selectedIndex].value].properties.x}>`);

    pBox.append(` <label for="latitude">latitude</label>`)
    pBox.append(`<input id="latitude" value=${SelectedAddress[document.getElementById("Town").options[document.getElementById('Town').selectedIndex].value].properties.y}>`);

}

function callApi(){
    addressApi(document.getElementById('town_name').value)

}
function activer() {
    // $searchValue = document.getElementById('campusActive').hidden = true
    // $searchValue = document.getElementById('event_campusSite').hidden = true
    document.getElementById('event_campusSite').disabled = false
}
window.onload = function ()
{
    // document.getElementById('campusActive').disabled = true
    document.getElementById('event_campusSite').disabled = true

}



function addressApi(city) {

    $.ajax(
        {

            url: 'https://api-adresse.data.gouv.fr/search/?q&type=municipality&autocomplete=1',
            method: 'GET',
            data: {q: city}

        }
    ).done(

        (donnees) => {
            SelectedAddress=donnees.features;
            let selectBox= $('#datalistOptions');
            //document.getElementById('town_name').options.length=0;

            for (const adresse of donnees.features) {
                 let nouvelElement = $(`<option value=${adresse.properties.city}></option>`);
                selectBox.append(nouvelElement)
                cpt++;
              }
            //travailler avec event listener pour rester dans la fonction
        }
    )
        .fail()
        .always();

}