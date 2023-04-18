<?php
require "autoloader.php";

use Vehicle\VehicleDataFetcher;

$vehicleDataFetcher = new VehicleDataFetcher();
$regNummer = 'AX58168';
$vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);

if (is_array($vehicleData)) {
	// Process the vehicle data
	echo "Vehicle data: \n";
	print_r($vehicleData);
} else {
	// Handle the error message
	echo "Error: " . $vehicleData;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<title>Statens Vegvesen - PHP</title>
</head>

<body>

	<!-- Start Div for entering registration number -->
	<div class="container d-flex justify-content-center ps-2 pe-2 mt-5 sm-m-5">
		<div class="">
			<div class="bg-dark bg-gradient text-white p-4 mb-4 rounded shadow-sm">
				<div class="d-none d-lg-block">
					<h3 class="text-center">
						Skriv inn registreringsnummeret (AA12345)
					</h3>
				</div>
				<div class="d-sm-block d-md-block d-lg-none">
					<h6 class="text-center">
						Skriv inn registreringsnummeret (AA12345)
					</h6>
				</div>
			</div>
			<!-- End Div for entering registration number -->
			<!-- Start Div for input field -->
			<div>
				<form id="regnrform">
					<label for="bilinformasjon" class="form-label">Registreringsnummer</label>
					<input id="bilinformasjon" class="form-control shadow-sm" type="text" />
					<div class="d-flex justify-content-center">
						<span id="feilMelding" class="helper-text" data-error="Feil lengde på registreringsnummer"></span>
						<button id="submitButton" class="btn btn-primary btn-lg mt-4" type="submit" disabled>
							Hent informasjon
						</button>
					</div>
				</form>
			</div>
			<!-- End Div for input field -->
			<!-- Start Div Loading spinner  -->
			<div id="loadingSpinner" class="container mt-4 text-center d-none">
				<h4>Henter informasjon, vennligst vent</h4>
				<br />
				<div class="spinner-border" role="status">
					<span class="visually-hidden">Henter informasjon, vennligst vent</span>
				</div>
				<div class="row">
					<div class="preloader-wrapper small active">
						<div class="spinner-layer spinner-teal-only">
							<div class="circle-clipper left">
								<div class="circle"></div>
							</div>
							<div class="gap-patch">
								<div class="circle"></div>
							</div>
							<div class="circle-clipper right">
								<div class="circle"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Div Loading spinner  -->
			<!-- Start Div information table  -->
			<div id="tableElement" class="container mt-5 d-none animate__animated animate__fadeIn">
				<table class="table table-responsive table-hover">
					<caption class="mt-2">
						Kjøretøyinformasjon
					</caption>
					<thead>
						<tr>
							<th scope="col">Skilt</th>
							<th scope="col">Førstereg</th>
							<th scope="col">Eierreg</th>
							<th scope="col">EU kontroll</th>
						</tr>
					</thead>
					<tbody>
						<tr id="trInfo">
							<td id="kjennemerke">&nbsp;</td>
							<td id="forstegangsregistrering">&nbsp;</td>
							<td id="forstegangsregistreringEier">&nbsp;</td>
							<td id="sistKontrollert">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>

</html>