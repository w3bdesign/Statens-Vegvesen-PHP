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
	<title>Document</title>
</head>

<body>

	<h1>Skjema for data</h1>

	<form>
	<h1>Registreringsnummer: <?php echo $vehicleData; ?></h1>
	</form>

</body>

</html>