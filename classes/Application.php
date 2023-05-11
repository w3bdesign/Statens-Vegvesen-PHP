<?php

namespace Main;

use Vehicle\VehicleDataFetcher;
use Vehicle\VehicleDataRender;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use Exception;
use Dotenv;

class Application
{
    private $apikey;
    private $twig;

    public function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();


        // Set up Twig
        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/views');
        $this->twig = new Environment($loader);

        // Set API key
        $this->apikey = isset($_ENV["STATENS_VEGVESEN_API_KEY"]) ? $_ENV["STATENS_VEGVESEN_API_KEY"] : null;

        if (!$this->apikey) {
            // Throw an exception if API key is missing
            throw new Exception('API nøkkel er ikke satt i .env');
        }
    }

    public function run()
    {
        session_start();

        $hasError = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bilinformasjon"])) {
            try {
                $vehicleDataFetcher = new VehicleDataFetcher($this->apikey);
                $regNummer = $_POST["bilinformasjon"];
                $vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
            } catch (Exception $e) {
                $hasError = true;
            }
        }

        echo $this->twig->render('main.html.twig');

        if ($hasError) {
            echo "<div class='container mt-5 text-center'>
                <div class='alert alert-danger' role='alert'>
                 " . $e->getMessage() . "
                </div>
                </div>";
            return;
        }

        if (isset($vehicleData)) {
            $vehicleDataRender = new VehicleDataRender($vehicleData);
            echo $vehicleDataRender->render();
        }

        echo $this->twig->render('footer.html.twig');
    }
}
