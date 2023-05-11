<?php
// Autoinnlasting av klasser
require_once "vendor/autoload.php";

use Main\Application;

$app = new Application();
$app->run();
?>