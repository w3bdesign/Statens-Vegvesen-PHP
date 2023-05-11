
To use this class, you'll need to do the following:

1. Import the autoloader.php with `require "autoloader.php";`

2. Include the class in your project by adding the `use` statement at the top of your file:

   ```php
use Vehicle\VehicleDataFetcher;
use Vehicle\VehicleDataRender;
   ```

3. Create a new instance of the `VehicleDataFetcher` class:

   ````php
   $hasError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

   ````php
if (isset($vehicleData)) {
				$vehicleDataRender = new VehicleDataRender($vehicleData);
				echo $vehicleDataRender->render();
			}
   ```

5. Handle the returned data or error message:

   ````php
   <?php
			// Render error
			if ($hasError) {
				echo "<div class='container mt-5 text-center'>
			<div class='alert alert-danger' role='alert'>
			 " . $e->getMessage() . "
			</div>
			</div>";
				return;
			}
    ?>
   ```
