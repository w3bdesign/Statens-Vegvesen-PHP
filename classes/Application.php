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
        $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();

        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/views');
        $this->twig = new Environment($loader);

        $this->apikey = isset($_ENV["STATENS_VEGVESEN_API_KEY"]) ? $_ENV["STATENS_VEGVESEN_API_KEY"] : null;

        if (!$this->apikey) {
            throw new Exception('API nøkkel er ikke satt i .env');
        }
    }

    public function run()
    {
        session_start();

        $vehicleDataRendered = $this->handlePostRequest();

        echo $this->twig->render('main.html.twig');

        if (!empty($vehicleDataRendered)) {
            echo $vehicleDataRendered;
        }

        echo $this->twig->render('footer.html.twig');
    }

    private function handlePostRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["bilinformasjon"])) {
            return '';
        }

        $vehicleDataRendered = '';

        try {
            $vehicleDataFetcher = new VehicleDataFetcher($this->apikey);
            $regNummer = $_POST["bilinformasjon"];
            $vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);

            $vehicleDataRender = new VehicleDataRender($vehicleData);
            $vehicleDataRendered = $vehicleDataRender->render();
        } catch (Exception $e) {
            $vehicleDataRendered = "<div class='container mt-5 text-center'>
                <div class='alert alert-danger' role='alert'>
                 " . $e->getMessage() . "
                </div>
              </div>";
        }

        return $vehicleDataRendered;
    }
}
