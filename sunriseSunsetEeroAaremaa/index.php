<!--
	Veebirakenduse päiksetõusu ja loojangu arvutamiseks avakuva
	Autor: Eero Ääremaa
	Kuupäev: 22. aprill 2020

	Programm on loodud töötama veebiserveris ning seda saab arvutis käivitada näiteks 
	rakenduse XAMPP abil.
	Juhend lokaalses serveris rakenduse käivitamiseks
		1. Käivita rakendus XAMPP ning käivita seal Apache
		2. Paiguta terve veebirakenduse kaust asukohta: "C:\xampp\htdocs"
		3. Ava brauseris leht localhost/sunriseSunsetEeroAaremaa

	Veebirakenduses on kasutusel HTML ja Bootstrap peamiste veebielementide kuvamiseks,
	JavaScript interaktiivse kaardi kuvamiseks ja küpsiste salvestamiseks,
	php serveriga suhtlemiseks ning Python peamiste arvutuste tegemiseks.
	Rakenduse korrektseks toimimiseks peab olema serverisse paigaldatud Python 3.8 ning 
	failis sunriseSunsetCalc.py täpsustatud teegid.

	Täpsema loomisprotsessi kohta saab lugeda readme failist.

-->


<?php 
	// PHP osa peamiselt serveris taustal Pythoni käivitamiseks
	// Kui kasutaja on vajutanud nuppu "submit" siis võtame kasutaja poolt sisestatud andmed
	if(isset($_POST['submit'])){
		$latDeg = $_POST['latDeg'];
		$latMin = $_POST['latMin'];
		$latSec = $_POST['latSec'];
		$lngDeg = $_POST['lngDeg'];
		$lngMin = $_POST['lngMin'];
		$lngSec = $_POST['lngSec'];
		if(isset($_POST['date'])){
			$date = $_POST['date'];
			// Käivitame konsoolis Pythoni programmi, mis prindib ekraanile ühel real soovitud vastused
			$str = exec("python sunriseSunsetCalc.py $latDeg $latMin $latSec $lngDeg $lngMin $lngSec $date");
			
			// Võtame selle rea kolme vastusega ja teeme selle eraldi vastustega massiiviks
			$sunRiseSet = explode(" ", $str);

			// Kui massivis on täpselt kolm elementi siis kuvame kasutaja päringule vastused
			if(count($sunRiseSet) == 3){
				$sunRise = $sunRiseSet[0];
				$sunSet = $sunRiseSet[1];	
				$dayLength = $sunRiseSet[2];
				$invalidInput = "";
			// Kui Pythoni programm ei tagasta õigeid vastuseid siis palume kasutajal andmed uuesti sisestada
			// Selline olukord tekib näiteks siis kui kasutaja ei vali kuupäeva
			}else{
				$sunRise = "-";
				$sunSet = "-";	
				$dayLength = "-";
				$invalidInput = "Please enter data above";
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Päises toome sisse soovitud raamistikud nagu jQuery ja Bootstrap-->
		<title>Sunrise and sunset calculator</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		
		<!-- Määrame CSSiga mõned nõuded. Kuna neid on vähe siis teeme seda päises,
		üldiselt võiks seda teha eraldi failis. Projekti edasi stiliseerides võiks seda kindalsi mujal teha -->
		<style>
		#map {
			height: 600px;
		}
		#main {
			margin-top: 20px;
		}
		</style>
	</head>

	<body>
		<!-- Loome lihtsa bootstrap navigatsioonirea ekraanin algusesse-->
		<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
  			<span class="navbar-brand">Sunset and sunrise calculator</span>
		</nav>
		<!-- Loome peamise jaotuse -->
		<div class  = container id = "main">
			<div class = "row justify-content-md-center">
				<div class="col" id= "left" >
					<!-- Loome kaardi, mille hiljem JavaScripti abil kuvame -->
					<div id="map"></div>
				</div>

				<!-- Parempoolne jaotus sisaldab kasutajale andmete sisestamiseks vajaliku -->
				<div class="col" id = "right">
					<!-- Kasutama HTML-i elementi form, mis nuppu "Submit" vajutamisel saadab php-le sisestatud info -->
					<form method = "post" >
						
						<!-- Palume kasutajal sisestada  laius- ja pikkuskraadid.
						Need väljad uuendavad ennast ise, kui kasutaja klõpsab interaktiivsel kaardil
						või on peale kaardil oleva nupu lohistamist sellest lahti lasknud.-->

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

						<!-- Palume kasutajal sisestada soovitud kuupäeva.
						Kuupäeva valimine ei ole erilise stiiliga, see võiks olla projekti edasi arendmisel
						üks eesmärk.-->
						<label for="date">Enter a date </label>
						<input type="date" id="datemax" name="date" value = <?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>><br>

						<!-- Kasutaja ei pea sisestama ajavööndit, selle määrab Pythoni programm automaatselt. -->
						<br> 

						<!-- Loome peamise nupu arvutuste tegemiseks --> 
						<input class = "btn btn-primary" onclick = "updateCookie()" type = "submit" name = "submit">
					</form>

					<br>
					<!-- Loome nupu, mille abil saab brauser leida ligikaudse asukoha. -->
					<button class="btn btn-secondary" onclick = "currentLocation()" name= "Get current location">Get current location</button>
					<br>
					<br>

					<!-- Vastuste kuvamiseks loome kolm span elementi, mida uuendatakse, kui 
					päiksetõusu ja loojangu arvutused on tehtud. Kui tegemist ei ole sobiva sisestusega siis
					palume kasutajal andmed uuesti sisestda "invalidInput" abil -->
					<span>Sunrise: </span> <span id = "sunriseElement">-</span>
					<br>
					<span>Sunset: </span> <span id = "sunsetElement">-</span>
					<br>
					<span>Length of the day: </span> <span id = "lengthElement">-</span>
					<br>
					<span id = "invalidInput"></span>

					<!-- Käivitame Javascript skriptid, mille tegevused on täpsemalt vastavates
					failides lahti kirjutatud --> 
					<script src="js/locUpdate.js"></script>
					<script src="js/currentLocation.js"></script>
					
					<!-- Jägnev skript kontrollib, kas php-s on olemas soovitud vastused,
					kui jah, siis kuvame ned vastavate HTML elementide sees. -->
					<script>
						document.getElementById("sunriseElement").innerHTML = "<?php echo isset($sunRise) ? $sunRise: '-'?>";
						document.getElementById("sunsetElement").innerHTML = "<?php echo isset($sunSet) ? $sunSet: '-'?>";
						document.getElementById("lengthElement").innerHTML = "<?php echo isset($dayLength) ? $dayLength: '-'?>";
						document.getElementById("invalidInput").innerHTML = "<?php echo isset($invalidInput) ? $invalidInput: ''?>";
					</script>
					
					<!-- Käivitame interaktiivset kaarti juhtiva skripti --> 
					<script src = "js/gMap.js"></script>

				</div>
			</div>
		</div>
		
		<!-- Lisame veebilehe lõppu navigatsioonirea, kus kuvame infot autori kohta-->
		<nav class="navbar fixed-bottom navbar-light" style="background-color: #e3f2fd; width: 100%;">
			<div class="container-fluid">
				<div class="row justify-content-md-center" >
					<div class="col-8">
						One of three columns
					</div>
					<div class="col-4">
						One of three columns
					</div>
				</div>
				<div class="row justify-content-md-center" >
					<div class="col">
						Eero Ääremaa 2020
					</div>
				</div>
			</div>
		</nav>

		<!-- Käivitame Google Maps API jaoks vajaliku skripti --> 
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy8U0o5mS9mRHZKHpp4u6kHizUAYB-tYk&callback=initMap"
		async defer></script>
	</body>
</html>