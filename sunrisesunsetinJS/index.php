<?php 
    if(isset($_POST['submit'])){ 
		$test =  $_COOKIE['name'];
		echo $test;
		$str = exec("python test.py $test");
    }else{
         //code to be executed  
    }
?>
<!DOCTYPE html>
<html>
  <head>
  	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
		height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 10;
        padding: 10;
	  }
	  #left{
		  float: left;
		  width: 50%;
		  height: 70%;
	  }
	  #right{
		  float: right;
		  width: 50%;
		  height: 70%;
	  }

	  
	  
	</style>
	<link href="css/calendar.css" type="text/css" rel="stylesheet" />
  </head>

  <body>

  	<div id= "left" >
		<div id="map"></div>
	</div>
	<div id = "right">
	  	<form method = "post" action = "">
			<input onclick = "anwser()" type = "submit" name = "submit">
		</form>
	</div>

	<h1 id = "test">test</h1>
	<script> 
		document.getElementById("test").innerHTML = "<?php echo $str ?>";
	</script>
	<script>
	document.cookie = "; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

	var map;
    function initMap() {
		var marker;

		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(' ');
		ca[0] = ca[0].substring(5, ca[0].length);
		console.log(ca[0]);  
		console.log(ca[1]);
		if(Boolean(ca[0])){
			var myLatLng = {lat: parseFloat(ca[0]), lng: parseFloat(ca[1])};
			console.log(myLatLng);
			
		}else{
			var myLatLng = {lat: 0, lng: 0};
		}
		


        map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 4
		});
		
		if(Boolean(ca[0])){
			marker = new google.maps.Marker({
					position: {lat: parseFloat(ca[0]), lng: parseFloat(ca[1])},
					map: map,
					draggable: true
				});
			
				var lat = marker.getPosition().lat();
				var lng = marker.getPosition().lng();
				
				google.maps.event.addListener(marker, 'dragend', function(evt){
					var lat = marker.getPosition().lat();
					var lng = marker.getPosition().lng();
					var todayJulian = new Date().getTime()/86400000 + 2440587.5;
					var latlngtime = lat + " " + lng + " " + todayJulian;
					
					document.cookie = 'name =' + latlngtime;
					
					alert(document.cookie);
					console.log(lat);
					console.log(lng)
				});    
				
				console.log(lat);
				console.log(lng);
		}
		
		//google.maps.event.addListener(map, 'click', function( event ){
		//alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
		//});
		

		google.maps.event.addListener(map, 'click', function(event) {
			console.log(event.latLng);
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
					var todayJulian = new Date().getTime()/86400000 + 2440587.5;
					var latlngtime = lat + " " + lng + " " + todayJulian;
					
					document.cookie = 'name =' + latlngtime;
					
					alert(document.cookie);
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

				var lat = marker.getPosition().lat();
				var lng = marker.getPosition().lng();
				var todayJulian = new Date().getTime()/86400000 + 2440587.5;
				var latlngtime = lat + " " + lng + " " + todayJulian;
					
				document.cookie = 'name =' + latlngtime;
				alert(document.cookie);
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
	  
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy8U0o5mS9mRHZKHpp4u6kHizUAYB-tYk&callback=initMap"
	async defer></script>
  </body>
</html>