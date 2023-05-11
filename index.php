<?php
// Autoinnlasting av klasser
require_once "autoloader.php";
require_once "vendor/autoload.php";

use Vehicle\VehicleDataFetcher;
use Vehicle\VehicleDataRender;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/views');
$twig = new Environment($loader);
$mainTemplate = $twig->load('main.html.twig');
$footerTemplate = $twig->load('footer.html.twig');

session_start();

$hasError = false;
$apikey = isset($_ENV["STATENS_VEGVESEN_API_KEY"]) ? $_ENV["STATENS_VEGVESEN_API_KEY"] : null;

if (!$apikey) {
	// Vis feilmelding hvis STATENS_VEGVESEN_API_KEY mangler
	throw new Exception('API nøkkel er ikke satt i .env');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bilinformasjon"])) {

	try {
		$vehicleDataFetcher = new VehicleDataFetcher($apikey);
		$regNummer = $_POST["bilinformasjon"];
		$vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
	} catch (Exception $e) {
		$hasError = true;
	}
}

echo $mainTemplate->render();

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

echo $footerTemplate->render();
?>
