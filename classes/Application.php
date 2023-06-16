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

    /**
     * Constructor for initializing the class and loading environment variables.
     *
     * @throws Exception if API key is not set in .env file
     */
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

    /**
     * Runs the PHP function, starting a session, handling a post request,
     * rendering main.html.twig, and optionally rendering vehicle data.
     *
     * @return void
     */
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


    /**
     * Renders an error message based on the given Exception object.
     *
     * @param Exception $e The Exception object to render an error message for.
     * @throws Exception if the Exception object is not of type Exception.
     * @return string The HTML error message to display.
     */
    private function renderError(Exception $e)
    {
        return "<div class='container mt-5 text-center'>
                <div class='alert alert-danger' role='alert'>
                 " . $e->getMessage() . "
                </div>
              </div>";
    }

    /**
     * Handles a POST request by fetching vehicle data and rendering it.
     *
     * @throws Exception if there was an error while fetching the vehicle data or rendering it.
     * @return string rendered vehicle data, or an empty string if the request was not a POST request or if the required parameters were not set.
     */
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


            $vehicleDataRendered = $this->renderError($e);
        }

        return $vehicleDataRendered;
    }
}
