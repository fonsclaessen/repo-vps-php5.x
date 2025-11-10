<?php

/**
 * Een eenvoudig script om de verbinding met de Azure SQL database te testen
 * via de pdo_sqlsrv driver.
 */

// --- Pas deze variabelen aan met uw eigen gegevens ---
$serverName = "iqmatrix.database.windows.net";
$database   = "bcm_adz";
$username   = "coopadz";
$password   = "CP#k%$Dg^%$DGHrr29VD";
// ----------------------------------------------------

// De DSN (Data Source Name) voor de pdo_sqlsrv driver.
// De 'Server' parameter bevat de servernaam en de poort.
$dsn = "sqlsrv:Server={$serverName},1433;Database={$database}";

echo "Proberen verbinding te maken met de database...\n";
echo "Server: {$serverName}\n";
echo "Database: {$database}\n\n";

try {
    // Probeer een nieuwe PDO-verbinding te maken.
    $conn = new PDO($dsn, $username, $password);
    
    // Stel de error mode in op 'exception' voor duidelijke foutmeldingen.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Als we hier komen, is de verbinding gelukt!
    echo "========================================\n";
    echo "SUCCESS: Verbinding is succesvol gelegd!\n";
    echo "========================================\n";

    // Sluit de verbinding weer.
    $conn = null;

} catch (PDOException $e) {
    // Als de verbinding mislukt, vang de fout op en toon deze.
    echo "========================================\n";
    echo "ERROR: Verbinding mislukt.\n";
    echo "========================================\n";
    echo "Foutmelding: " . $e->getMessage() . "\n";
    
    // Exit met een error code.
    exit(1);
}
