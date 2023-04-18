<?php
require "autoloader.php";

use Vehicle\VehicleDataFetcher;

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bilinformasjon"])) {

	$vehicleDataFetcher = new VehicleDataFetcher();
	$regNummer = $_POST["bilinformasjon"];
	$vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
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
				<form id="regnrform" method="POST" action="index.php">
					<label for="bilinformasjon" class="form-label">Registreringsnummer</label>
					<input id="bilinformasjon" name="bilinformasjon" class="form-control shadow-sm" type="text" maxlength="7" pattern="[A-Za-z]{2}[0-9]{5}" required />
					<div class="d-flex justify-content-center">
						<span id="feilMelding" class="helper-text" data-error="Feil lengde på registreringsnummer"></span>
						<button id="submitButton" class="btn btn-primary btn-lg mt-4" type="submit" formmethod="post">
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

			<?php

			if ($_SERVER["REQUEST_METHOD"] == "POST" && !is_array($vehicleData)) {
				echo "<div class='container mt-5 text-center'>		

				<div class='alert alert-danger' role='alert'>
				Feil registreringsnummer, eller ingen data funnet
				</div>

				</div>";
				return;
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

				$dateString = $vehicleData["registrert_paa_eier"];
				$dateTime = new DateTime($dateString);
				$formattedDate = $dateTime->format('Y-m-d');

			?>
				<div id="tableElement" class="container mt-5">
					<table class="table table-responsive table-hover">
						<caption class="mt-2">
							Kjøretøyinformasjon
						</caption>
						<thead>
							<tr>
								<th scope="col">Regnummer</th>
								<th scope="col">Førstegangsregistrert</th>
								<th scope="col">Registrert på eier</th>
								<th scope="col">Sist EU godkjent</th>
							</tr>
						</thead>
						<tbody>
							<tr id="trInfo">
								<td id="kjennemerke"><?php echo $vehicleData["regnr"]; ?></td>
								<td id="forstegangsregistrering"><?php echo $vehicleData["registrert_aar"]; ?></td>
								<td id="forstegangsregistreringEier"><?php echo $formattedDate; ?> </td>
								<td id="sistKontrollert"><?php echo $vehicleData["eu_godkjenning"]; ?></td>
							</tr>
						</tbody>
					</table>
				</div>

			<?php
			}
			?>

			<!-- End Div information table  -->

		</div>
	</div>

</body>

</html>