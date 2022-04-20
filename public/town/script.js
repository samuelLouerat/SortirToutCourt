let $SelectedAddress;


document.getElementById("town_name").addEventListener("change", cp);

document.getElementById("town_name").addEventListener("keyup", function(){town(document.getElementById("town_name").value)});


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

function cp() {
    let val = document.getElementById("town_name").value;
    for(const element of $SelectedAddress){
        if(element.properties.city=== val){
            document.getElementById("town_postCode").value=element.properties.postcode;

        }

    }
    $SelectedAddress=null;

}
function town(val) {
    //let val = document.getElementById("town_name").value;
    console.log(val);
    $.ajax(
        {

            url: 'https://api-adresse.data.gouv.fr/search/?q&type=municipality&autocomplete=1',
            method: 'GET',
            data: {q: val}

        }
    ).done(
        (donnees) => {
            console.log(donnees.features);
            let availableTags = [];
            for (const adresse of donnees.features) {
                availableTags.push(adresse.properties.city);

            }
            $SelectedAddress = donnees.features;
            console.log(availableTags);
            $("#town_name").autocomplete({
                    source: availableTags
                }
            )

        })
}


