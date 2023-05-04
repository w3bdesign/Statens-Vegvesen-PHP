<?php

namespace Vehicle;

use Exception;

class VehicleDataFetcher
{
    // TODO Hent data fra .env
    private $apikey = 'endremeg';
    private $clientIdentifier = 'my-app';

    /**
     * Henter kjøretøysdata fra det norske Vegvesenet API basert på registreringsnummer.
     *
     * @param string $regNummer Registreringsnummer på kjøretøyet.
     * @return array|string Array med kjøretøysdata hvis data er funnet, eller en feilmelding hvis det oppstod en feil.
     * @throws Exception hvis curl-biblioteket ikke er installert eller aktivert på serveren.
     */
    public function getVehicleData($regNummer)
    {
        if (!function_exists('curl_version')) {
            throw new Exception('cURL library is not installed or enabled on this server.');
        }

        $baseURL = 'https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata';
        $queryParams = [
            'kjennemerke' => $regNummer
        ];
        $urlToFetch = $baseURL . '?' . http_build_query($queryParams);

        // Utfør en GET forespørsel mot det eksterne API-et ved hjelp av curl biblioteket
        $headers = [
            'SVV-Authorization: Apikey ' . $this->apikey,
            'X-Client-Identifier: ' . $this->clientIdentifier
        ];

        // Initialiser curl
        $curl = curl_init();

        // Sett curl-forespørselens parametre
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlToFetch,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
        ));

        // Kjør forespørselen
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            // Returner en feilmelding hvis curl-forespørselen feilet
            return "cURL error #: " . $err;
        } else {
            // JSON dekoder dataene før de brukes
            $data = json_decode($response, true);

            // Sett standard verdi for $apiregistrertPaaEier til null
            $apiregistrertPaaEier = null;

            // Kan vi erstatte koden nedenfor med dette?

            /*
            foreach ($data['kjoretoydataListe'] as $entry) {
            $apiRegnr = $entry['kjennemerke'][0]['kjennemerke'];
            $apiMerke = $entry["godkjenning"]["tekniskGodkjenning"]["tekniskeData"]["generelt"]["merke"][0]["merke"];
            $apiEUGodkjenning = $entry["periodiskKjoretoyKontroll"]["sistGodkjent"];
            $apiRegistrertAar = $entry["godkjenning"]["forstegangsGodkjenning"]["forstegangRegistrertDato"];
            $apiregistrertPaaEier = isset($entry["registrering"]["registrertForstegangPaEierskap"]) ? $entry["registrering"]["registrertForstegangPaEierskap"] : null;
            }

            */

            // Sjekk om $data arrayet inneholder kjøretøysdata basert på registreringsnummeret
            if (isset($data['kjoretoydataListe']) && count($data['kjoretoydataListe']) > 0) {
                $apiRegnr = $data['kjoretoydataListe'][0]['kjennemerke'][0]['kjennemerke'];
                $apiMerke = $data['kjoretoydataListe'][0]["godkjenning"]["tekniskGodkjenning"]["tekniskeData"]["generelt"]["merke"][0]["merke"];
                $apiEUGodkjenning = $data['kjoretoydataListe'][0]["periodiskKjoretoyKontroll"]["sistGodkjent"];
                $apiRegistrertAar = $data['kjoretoydataListe'][0]["godkjenning"]["forstegangsGodkjenning"]["forstegangRegistrertDato"];

                // Denne verdien kan være null, så vi må sjekke om den er satt før vi setter $apiregistrertPaaEier
                if (isset($data['kjoretoydataListe'][0]["registrering"]["registrertForstegangPaEierskap"])) {
                    $apiregistrertPaaEier = $data['kjoretoydataListe'][0]["registrering"]["registrertForstegangPaEierskap"];
                }

                // Opprett en array med kjøretøysdata som returneres fra funksjonen
                $result = array(
                    'regnr' => $apiRegnr,
                    'merke' => $apiMerke,
                    'eu_godkjenning' => $apiEUGodkjenning,
                    'registrert_aar' => $apiRegistrertAar,
                    'registrert_paa_eier' => $apiregistrertPaaEier,

                );

                // Returner array med kjøretøysdata
                return $result;
            } else {
                // Returner en feilmelding hvis ingen kjøretøysdata ble funnet i API-responsen
                return "Ingen kjøretøysdata ble funnet for dette registreringsnummeret.";
            }
        }
    }
}
