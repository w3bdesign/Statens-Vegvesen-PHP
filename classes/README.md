
To use this class, you'll need to do the following:

1. Include the class in your project by adding the `use` statement at the top of your file:

   ```php
   use Vehicle\VehicleDataFetcher;
   ```

2. Create a new instance of the `VehicleDataFetcher` class:

   ````php
   $vehicleDataFetcher = new VehicleDataFetcher();
   ```

3. Call the `getVehicleData()` method with the registration number as a parameter:

   ````php
   $regNummer = 'ABC12345';
   $vehicleData = $vehicleDataFetcher->getVehicleData($regNummer);
   ```

4. Handle the returned data or error message:

   ````php
   if (is_array($vehicleData)) {
       // Process the vehicle data
       echo "Vehicle data: \n";
       print_r($vehicleData);
   } else {
       // Handle the error message
       echo "Error: " . $vehicleData;
   }
   ```
