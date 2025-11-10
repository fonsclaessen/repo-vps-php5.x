cat > /srv/zzp/site1/test-sqlsrv.php << 'EOF'
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Azure SQL Test - Microsoft Driver</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f4f4f4; padding: 10px; }
    </style>
</head>
<body>
    <h1>Azure SQL Test - Microsoft SQLSRV Driver</h1>
    
    <h2>PHP Info</h2>
    <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
    
    <h3>SQL Server Extensions:</h3>
    <ul>
        <li>PDO: <?php echo extension_loaded('pdo') ? '<span class="success">✅</span>' : '<span class="error">❌</span>'; ?></li>
        <li>PDO_SQLSRV: <?php echo extension_loaded('pdo_sqlsrv') ? '<span class="success">✅</span>' : '<span class="error">❌</span>'; ?></li>
        <li>SQLSRV: <?php echo extension_loaded('sqlsrv') ? '<span class="success">✅</span>' : '<span class="error">❌</span>'; ?></li>
    </ul>
    
    <hr>
    
    <h2>Connection Test</h2>
    
    <?php
    $serverName = "iqmatrix.database.windows.net";
    $database = "bcm_adz";
    $username = "coopadz";
    $password = "CP#k%\$Dg^%\$DGHrr29VD";
    
    try {
        $dsn = "sqlsrv:Server=$serverName;Database=$database";
        echo "<p><strong>DSN:</strong> <code>$dsn</code></p>";
        
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        echo '<p class="success">✅ CONNECTION SUCCESSFUL!</p>';
        
        $stmt = $pdo->query("SELECT @@VERSION as version, GETDATE() as time");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Server Info:</h3>";
        echo "<pre>" . htmlspecialchars($result['version']) . "</pre>";
        echo "<p><strong>Time:</strong> " . $result['time'] . "</p>";
        
        $stmt = $pdo->query("SELECT TOP 5 TABLE_NAME FROM INFORMATION_SCHEMA.TABLES");
        echo "<h3>Tables:</h3><ul>";
        while ($row = $stmt->fetch()) {
            echo "<li>" . htmlspecialchars($row['TABLE_NAME']) . "</li>";
        }
        echo "</ul>";
        
    } catch (PDOException $e) {
        echo '<p class="error">❌ FAILED</p>';
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</body>
</html>
EOF
