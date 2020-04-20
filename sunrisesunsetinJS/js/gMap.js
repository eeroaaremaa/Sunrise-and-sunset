var map;
    function initMap() {
    var myLatLng = {lat: 58, lng: 26};
    


    map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        zoom: 8
    });
    //google.maps.event.addListener(map, 'click', function( event ){
    //alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
    //});
    var marker;

    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
    });
    
    

    function placeMarker(location) {
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
                console.log(lat);
                console.log(lng)
            });    
            
            console.log(lat);
            console.log(lng);
        } else { 
            marker.setPosition(location);
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();
            console.log(lat);
            console.log(lng);
        }
    }
    
    function markerCoords(markerobject){
        google.maps.event.addListener(markerobject, 'dragend', function(evt){
            infoWindow.setOptions({
                content: '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>'
            });
        infoWindow.open(map, markerobject);
        });

            
    }
}