<?php

namespace Vehicle;

use DateTime;

/**
 * Class VehicleDataFormatter
 *
 * @package Vehicle
 *
 * VehicleDataFormatter formatterer datoer for f.eks EU registrering osv.
 */

class VehicleDataFormatter
{
    public static function formatRegistrationDate($dateString)
    {
        if ($dateString !== "Ingen data") {
            $dateTime = new DateTime($dateString);
            return $dateTime->format('Y-m-d');
        }
        return "";
    }
}
