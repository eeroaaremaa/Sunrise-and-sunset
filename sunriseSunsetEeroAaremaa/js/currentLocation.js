/*
Skript kasutaja hetkese asukoha teda saamiseks.
Autor: Eero Ääremaa
Kuupäev: 22. aprill 2020

Skript on loodud, et kasutaja saaks peale funktsiooni nupust käivitamist 
veebirakendusse automaatselt enda praeguse asukoha.


*/

// Loome funktsiooni, mis leiab asukoha, kui kasutaja selleks loa annab
function currentLocation(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setPosition);
    }
    // Määrame positsiooni ehk kirjutame koordinaatide sisestamise jaoks mõeldud
    // kastidesse ketkesed koordinaadid.
    // Kasutame abifunktsiooni deg_to_dms, defineeriud failis gMap.js
    function setPosition(position) {
        deg_to_dms(parseFloat(position.coords.latitude), "latDeg", "latMin", "latSec");
        deg_to_dms(parseFloat(position.coords.longitude), "lngDeg", "lngMin", "lngSec");
    }	
}