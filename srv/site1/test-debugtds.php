<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Azure SQL Debug Test</h1>";

// Zet FreeTDS debug logging aan
putenv('TDSDUMP=/tmp/freetds.log');
putenv('TDSDUMPCONFIG=/tmp/freetds-config.log');

echo "<h2>Test 1: FreeTDS direct connection</h2>";

$server = "iqmatrix.database.windows.net";
$server1 = "Azure";
$database = "bcm_adz";
$username = "coopadz";
$password = "CP#k%\$Dg^%\$DGHrr29VD";

// Test verschillende DSN formats
$tests = [
    "Test 1: Basis DSN" => "dblib:host=$server:1433;dbname=$database",
    "Test 2: Met dbname syntax" => "dblib:host=$server;dbname=$database;port=1433",
    "Test 3: Server name format" => "dblib:server=$server;database=$database",
    "Test 4: volle naam" => "dblib:host=$server:1433;dbname=$database",
    "Test 5: FreeTDS sectie + poort (FOUT)" => "dblib:host=$server1:1433;dbname=$database",
    "Test 6: FreeTDS sectie (CORRECT)" => "dblib:host=$server1;dbname=$database",
];


foreach ($tests as $name => $dsn) {
    echo "<h3>$name</h3>";
    echo "<p><code>$dsn</code></p>";
    
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10
        ]);
        
        echo '<p style="color:green;">✅ SUCCESS!</p>';
        
        $stmt = $pdo->query("SELECT @@VERSION");
        $version = $stmt->fetchColumn();
        echo "<p><strong>SQL Server:</strong> " . htmlspecialchars(substr($version, 0, 100)) . "...</p>";
        
        break; // Stop bij eerste succesvolle connectie
        
    } catch (PDOException $e) {
        echo '<p style="color:red;">❌ FAILED</p>';
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
    }
    
    echo "<hr>";
}

// Lees FreeTDS debug log
echo "<h2>FreeTDS Debug Log</h2>";
echo "<pre style='background:#f4f4f4;padding:10px;max-height:400px;overflow:auto;'>";
if (file_exists('/tmp/freetds.log')) {
    echo htmlspecialchars(file_get_contents('/tmp/freetds.log'));
} else {
    echo "No debug log generated";
}
echo "</pre>";

echo "<h2>FreeTDS Config Log</h2>";
echo "<pre style='background:#f4f4f4;padding:10px;max-height:200px;overflow:auto;'>";
if (file_exists('/tmp/freetds-config.log')) {
    echo htmlspecialchars(file_get_contents('/tmp/freetds-config.log'));
} else {
    echo "No config log generated";
}
echo "</pre>";

// Toon FreeTDS config
echo "<h2>Active FreeTDS Config</h2>";
echo "<pre style='background:#f4f4f4;padding:10px;'>";
echo htmlspecialchars(file_get_contents('/etc/freetds.conf'));
echo "</pre>";

// Toon PHP info
echo "<h2>PHP PDO Drivers</h2>";
echo "<pre>";
print_r(PDO::getAvailableDrivers());
echo "</pre>";

phpinfo(INFO_MODULES);
?>



