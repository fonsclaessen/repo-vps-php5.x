//   /srv/zzp/site1/test-azure2.php 
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Azure SQL Test - Fixed DSN</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Azure SQL Connection Test (Fixed DSN)</h1>
    
    <h2>PHP Info</h2>
    <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
    
    <h3>Required Extensions:</h3>
    <ul>
        <li>PDO: <?php echo extension_loaded('pdo') ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Missing</span>'; ?></li>
        <li>PDO_DBLIB: <?php echo extension_loaded('pdo_dblib') ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Missing</span>'; ?></li>
        <li>MSSQL: <?php echo extension_loaded('mssql') ? '<span class="success">✅ Loaded</span>' : '<span class="error">❌ Missing</span>'; ?></li>
    </ul>
    
    <hr>
    
    <h2>Azure SQL Connection Test</h2>
    
    <?php
    $server = "iqmatrix.database.windows.net";
    $database = "bcm_adz";
    $username = "coopadz";
    $password = "CP#k%\$Dg^%\$DGHrr29VD";
    
    echo "<p><strong>Server:</strong> $server</p>";
    echo "<p><strong>Database:</strong> $database</p>";
    echo "<p><strong>Username:</strong> $username</p>";
    
    // Test 1: DSN zonder charset
    echo "<h3>Test 1: DSN zonder charset</h3>";
    try {
       //TODOFCL  $dsn = "dblib:host=$server:1433;dbname=$database";
       $dsn = "dblib:host=$server:1433;dbname=$database;TrustServerCertificate=yes";
       echo "<p><strong>DSN:</strong> <code>$dsn</code></p>";
        
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 30
        ]);
        
        echo '<p class="success">✅ CONNECTION SUCCESSFUL!</p>';
        
        // Test query
        $stmt = $pdo->query("SELECT @@VERSION as version, GETDATE() as current_time");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>SQL Server Info:</h3>";
        echo "<pre>" . htmlspecialchars($result['version']) . "</pre>";
        echo "<p><strong>Server Time:</strong> " . $result['current_time'] . "</p>";
        
        // Test table query
        echo "<h3>Database Tables (Top 5):</h3>";
        $stmt = $pdo->query("SELECT TOP 5 TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM INFORMATION_SCHEMA.TABLES ORDER BY TABLE_NAME");
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Schema</th><th>Table Name</th><th>Type</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['TABLE_SCHEMA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['TABLE_NAME']) . "</td>";
            echo "<td>" . htmlspecialchars($row['TABLE_TYPE']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } catch (PDOException $e) {
        echo '<p class="error">❌ CONNECTION FAILED</p>';
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
    }
    ?>
    
    <hr>
    
    <h3>FreeTDS Config Check:</h3>
    <pre><?php 
    if (file_exists('/etc/freetds.conf')) {
        echo htmlspecialchars(file_get_contents('/etc/freetds.conf'));
    } else {
        echo "FreeTDS config not found";
    }
    ?></pre>
    
    <hr>
    <p><small>Test uitgevoerd op: <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html>
