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
    addressApi(document.getElementById('Search').value)

}
function activer() {
    $searchValue = document.getElementById('campus').disabled = false
}
window.onload = function ()
{
    document.getElementById('campus').disabled = true
}



function addressApi(city) {

    $.ajax(
        {

            url: 'https://api-adresse.data.gouv.fr/search/',
            method: 'GET',
            data: {q: city}

        }
    ).done(

        (donnees) => {
            console.log(donnees);
            SelectedAddress=donnees.features;
            let selectBox= $('#Town');
            document.getElementById('Town').options.length=0;
            cpt=0;
            for (const adresse of donnees.features) {
                 let nouvelElement = $(`<option value=${cpt}>${adresse.properties.name}--${adresse.properties.city}--${adresse.properties.postcode}</option>`);
                selectBox.append(nouvelElement)
                cpt++;
              }

        }
    )
        .fail()
        .always();

}