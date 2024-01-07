<?php


include "database.php";
$databaseConnection = connectToDatabase();
print (getTemperature($databaseConnection));
