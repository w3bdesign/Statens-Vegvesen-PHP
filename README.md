# Display vehicle information from Statens Vegvesen

<img src="https://user-images.githubusercontent.com/45217974/164569251-ffd9b726-ccc5-4d87-b210-ec11e11c8c9d.png" alt="Screenshot" />

## Description

PHP port of https://github.com/w3bdesign/Statens-Vegvesen

Fetching vehicle information from the REST API on <https://autosys-kjoretoy-api.atlas.vegvesen.no/api-ui/index-enkeltoppslag.html> and displaying it.

It requires an API key that has to be set inside `.env` (rename .env.example to .env) that you can get from <https://www.vegvesen.no/om+statens+vegvesen/om+organisasjonen/apne-data/api-for-tekniske-kjoretoyopplysninger>

## Features

-   PHP with OOP
-   Separation of HTML and PHP code
-   Composer with class autoloader
-   Error handling and type annotations
-   Twig for rendering HTML
-   Bootstrap 5
-   Responsive design
-   Input validation with HTML5
-   PHPDoc comments wherever possible

## Instructions

To implement this inside your code, you'll need to do the following:

1.  Run `composer install` 

1.  Import the autoloader with `require_once "vendor/autoload.php";` 

2.  Include the class in your project by adding the `use` statement at the top of your file:

```php    

use Vehicle\VehicleDataFetcher;
use Vehicle\VehicleDataRender;

```

3. Create a new instance of the `VehicleDataFetcher` class:

```php
$hasError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
 try {
 	$vehicleDataFetcher = new VehicleDataFetcher();
    // This should be fetched from an input text value with $_POST
 	$regNummer = "AX58167;
 	$vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
 } catch (Exception $e) {
 	$hasError = true;
 }
}
```

4.Render the data inside your code:

```php
if (isset($vehicleData)) {
$vehicleDataRender = new VehicleDataRender($vehicleData);
echo $vehicleDataRender->render();
}
```

5. Handle the returned data or error message:

```php 	
if ($hasError) {
echo "<div class='container mt-5 text-center'>
<div class='alert alert-danger' role='alert'>
" . $e->getMessage() . "
</div>
</div>";
return;
}
 
```
