<?php 
    if(isset($_POST['submit'])){ 
		$date = $_POST['date'];
		$TZ = $_POST['timeZone'];
		if(!isset($TZ) ){
			echo "Please set timezone";
		}
		$latDeg = $_POST['latDeg'];
		$latMin = $_POST['latMin'];
		$latSec = $_POST['latSec'];
		$lngDeg = $_POST['lngDeg'];
		$lngMin = $_POST['lngMin'];
		$lngSec = $_POST['lngSec'];
		if(isset($_COOKIE['name'])){
			$test =  $_COOKIE['name'];
			//echo $test;
			$str = exec("python test.py $test $TZ $date $latDeg $latMin $latSec $lngDeg $lngMin $lngSec");
			$sunRiseSet = explode(" ", $str);
			$sunRise = $sunRiseSet[0];
			$sunSet = $sunRiseSet[1];			
		}
			
		
    }else{
         //code to be executed  
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sunrise and sunset calculator</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		
		<style>
		/* Always set the map height explicitly to define the size of the div
		* element that contains the map. */
		#map {
			/*height: 100%;*/
			height: 600px;
		}
		/* Optional: Makes the sample page fill the window. */
		html, body {
			height: 100%;
			margin: 10;
			padding: 10;
		}
		/*#left{
			float: left;
			width: 50%;
			height: 70%;
		}
		#right{
			margin-top: 20px;
		}*/
		</style>

		<link href="css/calendar.css" type="text/css" rel="stylesheet" />

	</head>

	<body>
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
  <a class="navbar-brand" href="#">Sunset and sunrise calculator</a>
  
</nav>

		<div class  = container>
			<div class = "row justify-content-md-center">
				<div class="col" id= "left" >
					<div id="map"></div>
				</div>

				<div class="col" id = "right">
					<form method = "post" action = "">
						
						<label for = "lat">Enter latitude </label>
						<input size = "3" id = "latDeg" name = "latDeg" value = <?php echo isset($_POST['latDeg']) ? $_POST['latDeg'] : '' ?>> ° 
						<input size = "3" id = "latMin" name = "latMin" value = <?php echo isset($_POST['latMin']) ? $_POST['latMin'] : '' ?>> ' 
						<input size = "5" id = "latSec" name = "latSec" value = <?php echo isset($_POST['latSec']) ? $_POST['latSec'] : '' ?>> ''
						<br>

						<label for = "lng">Enter longitude</label>
						<input size = "3" id = "lngDeg" name = "lngDeg" value = <?php echo isset($_POST['lngDeg']) ? $_POST['lngDeg'] : '' ?>> ° 
						<input size = "3" id = "lngMin" name = "lngMin" value = <?php echo isset($_POST['lngMin']) ? $_POST['lngMin'] : '' ?>> ' 
						<input size = "5" id = "lngSec" name = "lngSec" value = <?php echo isset($_POST['lngSec']) ? $_POST['lngSec'] : '' ?>> ''
						<br>

						<!--<label for = "lat">Enter latitude </label>
						<input size = "3" id="lat" type = "text" name = "lat" value = <//?php echo isset($_POST['lat']) ? $_POST['lat'] : '' ?>> 
						<br>
						<label for = "long">Enter longitude</label>
						<input id="lng" type = "text" name = "lng" value = <//?php echo isset($_POST['lng']) ? $_POST['lng'] : '' ?>> 
						<br>-->

						<label for="datemax">Enter a date </label>
						<input type="date" id="datemax" name="date" value = <?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>><br>
						<label for= "timeZone">Timezone</label>
						<input type = "text" id = "timeZone" name = "timeZone" value = <?php echo isset($_POST['timeZone']) ? $_POST['timeZone'] : '' ?>>
						<br>
						<br> 

						<input class = "btn btn-primary" onclick = "updateCookie()" type = "submit" name = "submit">
					</form>

					<br>
					<button onclick = "currentLocation()" name= "Get current location">Get current location</button>
					<br>
					<br>

					<div>
						<span>Sunrise: </span> <span id = "sunriseElement">-</span>
						<br>
						<span>Sunset: </span> <span id = "sunsetElement">-</span>
					</div>
				
					<script> 
						function updateCookie(){
							var todayJulian = new Date().getTime()/86400000 + 2440587.5;
							var latCurrent = parseFloat(document.getElementById('latDeg').value) + (parseFloat(document.getElementById('latMin').value) / 60) + (parseFloat(document.getElementById('latDeg').value) / 3600);
							var lngCurrent = parseFloat(document.getElementById('lngDeg').value) + (parseFloat(document.getElementById('lngMin').value) / 60) + (parseFloat(document.getElementById('lngDeg').value) / 3600); 
							var latlngtime = latCurrent + " " + lngCurrent + " " + todayJulian;
									
							document.cookie = 'name =' + latlngtime;
							console.log(latCurrent);
							alert(document.cookie);
						}
						document.getElementById("sunriseElement").innerHTML = "<?php echo isset($sunRise) ? $sunRise: '-'?>";
						document.getElementById("sunsetElement").innerHTML = "<?php echo isset($sunSet) ? $sunSet: '-'?>";
					</script>

					<script>
						function currentLocation(){
							getLocation();
							function getLocation() {
								if (navigator.geolocation) {
									navigator.geolocation.getCurrentPosition(setPosition);
								}
							}
							function setPosition(position) {
								deg_to_dms(parseFloat(position.coords.latitude), "latDeg", "latMin", "latSec");
								deg_to_dms(parseFloat(position.coords.longitude), "lngDeg", "lngMin", "lngSec");
							}	
						}
					</script>

					<script>
						function deg_to_dms (deg, idDeg,idMin, idSec) {
							var d = Math.floor (deg);
							var minfloat = (deg-d)*60;
							var m = Math.floor(minfloat);
							var secfloat = (minfloat-m)*60;
							//var s = Math.round(secfloat);
							var s = secfloat;
							// After rounding, the seconds might become 60. These two
							// if-tests are not necessary if no rounding is done.
							if (s==60) {
								m++;
								s=0;
							}
							if (m==60) {
								d++;
								m=0;
							}
							document.getElementById(idDeg).value = d;
							document.getElementById(idMin).value = m;
							document.getElementById(idSec).value = s;

							console.log(dms_to_deg(d,m,s));
						}

						function dms_to_deg (deg, min, sec) {
							return deg + (min/60) + (sec/3600);
						}

						document.cookie = "; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

						var map;

						function initMap() {
							var marker;

							var myLatLng = {lat: 0, lng: 0};
							var decodedCookie = decodeURIComponent(document.cookie);
							var ca = decodedCookie.split(' ');
							ca[0] = ca[0].substring(5, ca[0].length);
							console.log(ca[0]);  
							console.log(ca[1]);
							if(Boolean(ca[0])){

								//VAJAB TÖÖD
								//console.log("latSet" + latSet);
								//var myLatLng = {lat: latSet, lng: parseFloat(ca[1])};
								var myLatLng = {lat: parseFloat(ca[0]), lng: parseFloat(ca[1])};
								console.log(myLatLng);
								
							}else{
								
							}

							//console.log("test" + myLatLng);
							//navigator.geolocation.getCurrentPosition(function(position) {
							//		var currentLatitude = position.coords.latitude;
							//		var currentLongitude = position.coords.longitude;
							//		console.log(currentLongitude);
							//		
							//	});


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
										
									lat = marker.getPosition().lat();
									lng = marker.getPosition().lng();
										
									var todayJulian = new Date().getTime()/86400000 + 2440587.5;
									var latlngtime = lat + " " + lng + " " + todayJulian;
										
									document.cookie = 'name =' + latlngtime;
										
										
										//alert(document.cookie);
										//document.getElementById("lat").value = marker.getPosition().lat();
										//document.getElementById("lng").value = marker.getPosition().lng();
									deg_to_dms(marker.getPosition().lat(), "latDeg", "latMin", "latSec");
									deg_to_dms(marker.getPosition().lng(), "lngDeg", "lngMin", "lngSec");
									console.log(lat);
									console.log(lng);
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
								//document.getElementById("lat").value = marker.getPosition().lat();
								//document.getElementById("lng").value = marker.getPosition().lng();
								deg_to_dms(marker.getPosition().lat(), "latDeg", "latMin", "latSec");
								deg_to_dms(marker.getPosition().lng(), "lngDeg", "lngMin", "lngSec");
								
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
										console.log(lng);
										
									});    
									//console.log(lat);
									//console.log(lng);
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
									//alert(document.cookie);
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
						
				</div>
			</div>
		</div>

		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy8U0o5mS9mRHZKHpp4u6kHizUAYB-tYk&callback=initMap"
		async defer></script>
	</body>
</html>