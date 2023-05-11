<?php

namespace Vehicle;

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

    public function __construct($vehicleData)
    {
        $this->vehicleData = $vehicleData;
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

        return <<<HTML
            <div id="tableElement" class="container mt-5">
                <table class="table table-responsive table-hover">
                    <caption class="mt-2">Kjøretøyinformasjon</caption>
                    <thead>
                        <tr>
                            <th scope="col">Skilt</th>
                            <th scope="col">Førstereg</th>
                            <th scope="col">Registrert</th>
                            <th scope="col">EU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="trInfo">
                            <td id="kjennemerke">{$this->vehicleData["regnr"]}</td>
                            <td id="forstegangsregistrering">{$this->vehicleData["registrert_aar"]}</td>
                            <td id="forstegangsregistreringEier">{$formattedDate}</td>
                            <td id="sistKontrollert">{$this->vehicleData["eu_godkjenning"]}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
HTML;
    }
}
