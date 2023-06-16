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

    /**
     * Renders the vehicle data as HTML if the request method is POST.
     * If the vehicle data's 'registrert_paa_eier' field is not 'Ingen data',
     * the registration date is formatted and included in the HTML.
     * Otherwise, the unformatted registration date is included.
     *
     * @return string The vehicle data rendered as HTML, or an empty string if
     * the request method is not POST.
     */
    public function render()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return "";
        }
   
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
