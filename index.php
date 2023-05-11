<?php
// Autoinnlasting av klasser
require_once "autoloader.php";
require_once "vendor/autoload.php";

use Vehicle\VehicleDataFetcher;
use Vehicle\VehicleDataRender;

session_start();

$hasError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bilinformasjon"])) {
	try {
		$vehicleDataFetcher = new VehicleDataFetcher();
		$regNummer = $_POST["bilinformasjon"];
		$vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
	} catch (Exception $e) {
		$hasError = true;
	}
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

			<?php
			// Vis feilmelding om vi har
			if ($hasError) {
				echo "<div class='container mt-5 text-center'>
			<div class='alert alert-danger' role='alert'>
			 " . $e->getMessage() . "
			</div>
			</div>";
				return;
			}

			// Viser data fra VehicleDataRender
			if (isset($vehicleData)) {
				$vehicleDataRender = new VehicleDataRender($vehicleData);
				echo $vehicleDataRender->render();
			}

			?>


		</div>
	</div>

</body>

</html>