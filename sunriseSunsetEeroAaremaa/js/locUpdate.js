/*
Skript küpsiste salvestamiseks.
Autor: Eero Ääremaa
Kuupäev: 22. aprill 2020

Skript on loodud, et uuendada küpsist hetkel sisestuskastides olevate andmetega.


*/

function updateCookie(){
    // Võtame sisestuskastidest andmed ja viime need kümnendsüsteemi
    var latCurrent = parseFloat(document.getElementById('latDeg').value) + (parseFloat(document.getElementById('latMin').value) / 60) + (parseFloat(document.getElementById('latDeg').value) / 3600);
    var lngCurrent = parseFloat(document.getElementById('lngDeg').value) + (parseFloat(document.getElementById('lngMin').value) / 60) + (parseFloat(document.getElementById('lngDeg').value) / 3600); 
    // Paneme saadud andmed üheks sõneks kokku
    var latlngtime = latCurrent + " " + lngCurrent;
            
    // Uuendame küpsist hetkeste koordinaatidega
    document.cookie = 'name =' + latlngtime;
}