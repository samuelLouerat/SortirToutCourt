let $SelectedAddress;

document.getElementById("place_street").addEventListener("keyup", function(){lat(document.getElementById("place_street").value +" "+document.getElementById("place_town").options[document.getElementById("place_town").selectedIndex].text)});
document.getElementById("place_street").addEventListener("change", coordonnees);


function coordonnees() {
    let val = document.getElementById("place_street").value;
    console.log(val);
    for(const element of $SelectedAddress){
        if(element.properties.name=== val){
            document.getElementById("place_latitude").value=element.properties.x;
            document.getElementById("place_longitude").value=element.properties.y;

        }

    }
    $SelectedAddress=null;
}


function lat(val) {
    //let val = document.getElementById("town_name").value;
    console.log(val);
    $.ajax(
        {

            url: 'https://api-adresse.data.gouv.fr/search/?q&type=street&autocomplete=1',
            method: 'GET',
            data: {q: val}

        }
    ).done(
        (donnees) => {
            console.log(donnees.features);
            let availableTags = [];
            for (const adresse of donnees.features) {
                availableTags.push(adresse.properties.name);

            }
            $SelectedAddress=donnees.features;
            console.log(availableTags);
            $( "#place_street" ).autocomplete({
                    source: availableTags
                }
            )

        })



}


