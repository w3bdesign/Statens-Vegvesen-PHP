<?php

namespace Vehicle;

use Exception;

class VehicleDataFetcher
{
    private $apikey = 'changethis';
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

        $curl = curl_init();
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

            // Sjekk om $data-arrayet inneholder kjøretøysdata basert på registreringsnummeret
            if (isset($data['kjoretoydataListe']) && count($data['kjoretoydataListe']) > 0) {
                $apiRegnr = $data['kjoretoydataListe'][0]['kjennemerke'][0]['kjennemerke'];
                $apiMerke = $data['kjoretoydataListe'][0]["godkjenning"]["tekniskGodkjenning"]["tekniskeData"]["generelt"]["merke"][0]["merke"];
                $apiEUGodkjenning = $data['kjoretoydataListe'][0]["periodiskKjoretoyKontroll"]["sistGodkjent"];
                $apiRegistrertAar = $data['kjoretoydataListe'][0]["godkjenning"]["forstegangsGodkjenning"]["forstegangRegistrertDato"];
                $apiregistrertPaaEier = $data['kjoretoydataListe'][0]["registrering"]["registrertForstegangPaEierskap"];

                // Opprett en array med kjøretøysdata som returneres fra funksjonen
                $result = array(
                    'regnr' => $apiRegnr,
                    'merke' => $apiMerke,
                    'eu_godkjenning' => $apiEUGodkjenning,
                    'registrert_aar' => $apiRegistrertAar,
                    'registrert_paa_eier' => $apiregistrertPaaEier,                    

                );

                return $result;
            } else {
                // Returner en feilmelding hvis ingen kjøretøysdata ble funnet i API-responsen
                return "Ingen kjøretøysdata ble funnet for dette registreringsnummeret.";
            }
        }
    }
}
