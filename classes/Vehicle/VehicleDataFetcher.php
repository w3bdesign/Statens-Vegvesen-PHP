<?php

namespace Vehicle;

use Exception;


/**
 * Class VehicleDataFormatter
 *
 * @package Vehicle
 * 
 * Henter kjøretøysdata fra det norske Vegvesenet API basert på registreringsnummer.
 *
 * @param string $regNummer Registreringsnummer på kjøretøyet.
 * @return array Array med kjøretøysdata hvis data er funnet, eller en feilmelding hvis det oppstod en feil.
 * @throws Exception 
 */

class VehicleDataFetcher
{    
    private string $apikey;
    private string $clientIdentifier = 'my-app';

    public function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }

    public function getVehicleData(string $regNummer): array
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
            throw new Exception("cURL feil");
        }

        // JSON dekode dataene før de brukes
        $data = json_decode($response, true);

        if (empty($data['kjoretoydataListe']) || count($data['kjoretoydataListe']) === 0) {
            throw new Exception("Feil registreringsnummer, eller ingen data funnet");
        }

        foreach ($data['kjoretoydataListe'] as $entry) {
            $apiRegnr = $entry['kjennemerke'][0]['kjennemerke'];
            $apiMerke = $entry["godkjenning"]["tekniskGodkjenning"]["tekniskeData"]["generelt"]["merke"][0]["merke"];
            $apiEUGodkjenning = isset($entry["periodiskKjoretoyKontroll"]["sistGodkjent"]) ? $entry["periodiskKjoretoyKontroll"]["sistGodkjent"] : "Ingen data";
            $apiRegistrertAar = $entry["godkjenning"]["forstegangsGodkjenning"]["forstegangRegistrertDato"];
            $apiregistrertPaaEier = isset($entry["registrering"]["registrertForstegangPaEierskap"]) ? $entry["registrering"]["registrertForstegangPaEierskap"] : "Ingen data";
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
    }
}
