<?php

namespace Vehicle;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class VehicleDataRender
 * 
 *  @package Vehicle
 * 
 * Klassen VehicleDataRender tar imot kjøretøydata og genererer HTML-tabell for å vise informasjonen.
 *
 * @return string Den genererte HTML-tabellen, eller en tom streng hvis det ikke finnes data å vise.
 */

class VehicleDataRender
{
    private $vehicleData;
    private $twig;

    public function __construct($vehicleData)
    {
        $this->vehicleData = $vehicleData;        
        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/views');
        $this->twig = new Environment($loader);
    }

    public function render()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return "";
        }

        // Sjekk om $this->vehicleData["registrert_paa_eier"] ikke er "Ingen data"
        // Ellers returnerer vi "Ingen data"
        if ($this->vehicleData["registrert_paa_eier"] !== "Ingen data") {
            $formattedDate = VehicleDataFormatter::formatRegistrationDate($this->vehicleData["registrert_paa_eier"]);
        } else {
            $formattedDate = $this->vehicleData["registrert_paa_eier"];
        }

        $html = $this->twig->render('vehicle-data.html.twig', [
            'vehicleData' => $this->vehicleData,
            'formattedDate' => $formattedDate,
        ]);

        return $html;
    }
}
