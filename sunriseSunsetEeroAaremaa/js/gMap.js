/*
Skript Google Maps API interaktiivse kaardi kuvamiseks
Autor: Eero Ääremaa
Kuupäev: 22. aprill 2020

Skript on loodud, et kuvada Google Maps API interaktiivset kaarti ning et 
kaardil saaks valida asukoha.


*/

//Loome abifunktsiooni kümnendüsteemist kraadideks, minutiteks ja sekunditeks üle kandmiseks
// ning vastavatesse HTML elementidesse vastuste kirjutamiseks.
// Anname funktsioonile kümnendsüsteemis kraadid ja soovitud HTML elemendid, kuhu need kirjutada.
function deg_to_dms (deg, idDeg,idMin, idSec) {

    var d = Math.floor (deg);
    var minfloat = (deg-d)*60;
    var m = Math.floor(minfloat);
    var s = (minfloat-m)*3600;
    
    // Ümardamisel tekkinud vigade vältimiseks sooritame järgmised kontrollid.
    if (s == 60) {
        m++;
        s = 0;
    }
    if (m == 60) {
        d++;
        m = 0;
    }

    // Kanname vastused ette antud HTML elementidesse
    document.getElementById(idDeg).value = d;
    document.getElementById(idMin).value = m;
    document.getElementById(idSec).value = s;
}

// Abifunktsioon kraadidest, minutitest ja sekunditest kümnendsüsteemi üle minemiseks.
function dms_to_deg (deg, min, sec) {
    return deg + (min/60) + (sec/3600);
}

// Kustutame varasemalt loodud küpsise
document.cookie = "; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

// Loome kaardi
var map;
function initMap() {
    // Loome markeri ehk nupu, mida kaardil liigutada saab
    var marker;

    // Määrame esialgse kaardi lähtekoha, milleks on 0,0
    var myLatLng = {lat: 0, lng: 0};

    // Dekodeerime küpsise ning võtame sealt ainult vajaliku info
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(' ');
    ca[0] = ca[0].substring(5, ca[0].length);

    //Kui küpsises leidub vajalik info kaardi alguskoha määrmaiseks siis määrame selle vastavalt.
    if(Boolean(ca[0])){
        var myLatLng = {lat: parseFloat(ca[0]), lng: parseFloat(ca[1])};
    }

    // Loome uue Google Maps kaardi, mille keskkoht on etteantud asukoht
    map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        zoom: 4
    });
    
    // Järgnev toimub siis kui küpsistes on kasutaja poolt eelnevalt sisestatud asukoha info
    if(Boolean(ca[0])){
        // Loome uue markeri, küpsisest saadud asukohaga
        marker = new google.maps.Marker({
            position: {lat: parseFloat(ca[0]), lng: parseFloat(ca[1])},
            map: map,
            draggable: true
        });
        
        // Leiame markeri positsiooni
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();
            
        // Lisame markerile kuulaja, mis käivitub, kui markeri lohistamine on lõppenud.
        google.maps.event.addListener(marker, 'dragend', function(evt){

            // Võtame markeri askoha ja paneme need üheks sõneks kokku
            lat = marker.getPosition().lat();
            lng = marker.getPosition().lng();
            var latlngtime = lat + " " + lng;
            
            // Kirjutame saadud sõne küpsisesse
            document.cookie = 'name =' + latlngtime;
            
            // Kirjutame HTML tekstiväljad markeri uue asukohaga üle.
            deg_to_dms(marker.getPosition().lat(), "latDeg", "latMin", "latSec");
            deg_to_dms(marker.getPosition().lng(), "lngDeg", "lngMin", "lngSec");
        });    
    }
    
    
    // Lisame kuulaja, mis reageerib kaardil klõpsamisele.
    google.maps.event.addListener(map, 'click', function(event) {
        // Määrame markeri asukohaks klõpsu tehtud koha
        placeMarker(event.latLng);

        //Kirjutame HTML tekstiväljad markeri uue asukohaga üle
        deg_to_dms(marker.getPosition().lat(), "latDeg", "latMin", "latSec");
        deg_to_dms(marker.getPosition().lng(), "lngDeg", "lngMin", "lngSec");
        
    });
    
    // Funktsioon markeri kaardile asetamiseks
    function placeMarker(location) {

        // Kui kaardil veel marekerit pole siis loome uue ja lisame talle lohistamise kuulaja
        if (marker == null){
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            }); 
            
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();
            
            google.maps.event.addListener(marker, 'dragend', function(evt){
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                var latlngtime = lat + " " + lng;
                
                document.cookie = 'name =' + latlngtime;
            });
            
        // Kui marker on juba olemas siis määrame markeri asukoha ja uuendame küpsiseid
        } else { 

            marker.setPosition(location);
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();

            var latlngtime = lat + " " + lng;
                
            document.cookie = 'name =' + latlngtime;
        }
    }
}