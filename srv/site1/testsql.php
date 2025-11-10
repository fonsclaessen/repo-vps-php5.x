<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Activeer FreeTDS debug logging
putenv('TDSDUMP=/tmp/freetds.log');
putenv('TDSDUMPCONFIG=/tmp/freetds_config.log');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Azure SQL Database Test (Debug Mode)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }
        pre { background: #f4f4f4; padding: 15px; overflow: auto; max-height: 400px; border-radius: 5px; font-size: 12px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔌 Azure SQL Database Connectie Test (Debug Mode)</h1>
        
        <?php
        // === CONFIGURATIE ===
        $server = 'Azure';
        $username = 'coopadz';
        $password = 'CP#k%$Dg^%$DGHrr29VD';
        $database = 'bcm_adz';
        
        echo "<div class='info'>";
        echo "<strong>📋 Connectie Parameters:</strong><br>";
        echo "Server: <code>" . htmlspecialchars($server) . "</code> (FreeTDS alias)<br>";
        echo "Username: <code>" . htmlspecialchars($username) . "</code><br>";
        echo "Database: <code>" . htmlspecialchars($database) . "</code><br>";
        echo "Encryption: <code>OFF</code> (test mode)<br>";
        echo "FreeTDS Debug Log: <code>/tmp/freetds.log</code>";
        echo "</div>";
        
        // === Test DNS resolutie ===
        echo "<h2>🌐 DNS Resolutie Test</h2>";
        $hostname = 'iqmatrix.database.windows.net';
        $ip = gethostbyname($hostname);
        
        if ($ip === $hostname) {
            echo "<div class='error'>";
            echo "❌ DNS resolutie mislukt voor <code>$hostname</code><br>";
            echo "De hostname kan niet worden omgezet naar een IP-adres.";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "✅ DNS resolutie succesvol: <code>$hostname</code> → <code>$ip</code>";
            echo "</div>";
        }
        
        // === Test poort connectiviteit ===
        echo "<h2>🔌 Poort Connectiviteit Test</h2>";
        $fp = @fsockopen($hostname, 1433, $errno, $errstr, 5);
        
        if (!$fp) {
            echo "<div class='error'>";
            echo "❌ Kan niet verbinden met <code>$hostname:1433</code><br>";
            echo "<strong>Error:</strong> [$errno] " . htmlspecialchars($errstr);
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "✅ TCP verbinding succesvol naar <code>$hostname:1433</code>";
            echo "</div>";
            fclose($fp);
        }
        
        // === Controleer mssql extensie ===
        echo "<h2>🔧 PHP mssql Extensie Check</h2>";
        
        if (!function_exists('mssql_connect')) {
            echo "<div class='error'>";
            echo "❌ <code>mssql_connect()</code> functie bestaat niet<br>";
            echo "<strong>Geladen extensies:</strong> " . implode(', ', get_loaded_extensions());
            echo "</div>";
            exit;
        }
        
        echo "<div class='success'>";
        echo "✅ <code>mssql_connect()</code> functie is beschikbaar";
        echo "</div>";
        
        // === Probeer te verbinden ===
        echo "<h2>🔄 Database Connectie Poging</h2>";
        
        // Verwijder oude log
        @unlink('/tmp/freetds.log');
        


        //  $dsn = "sqlsrv:Server=$server;Database=bcm_adz;";
          $dsn = "dblib:host=$server:1433;dbname=$database;charset=utf8";
          $conn = new PDO($dsn, $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Verbonden met PDO!";

//        $conn = @mssql_connect($server, $username, $password);





        
        if (!$conn) {
            $error = mssql_get_last_message();
            
            echo "<div class='error'>";
            echo "❌ <strong>Verbinding Mislukt</strong><br><br>";
            
            if (empty($error)) {
                echo "<strong>Error:</strong> <em>(geen foutmelding beschikbaar)</em><br><br>";
                echo "Dit betekent meestal een laag-niveau connectie probleem.";
            } else {
                echo "<strong>Error:</strong> " . htmlspecialchars($error);
            }
            
            echo "</div>";
            
            // Toon FreeTDS debug log
            echo "<h3>🐛 FreeTDS Debug Log</h3>";
            
            if (file_exists('/tmp/freetds.log')) {
                $log_content = file_get_contents('/tmp/freetds.log');
                
                if (!empty($log_content)) {
                    // Zoek naar specifieke fouten
                    if (stripos($log_content, 'SSL') !== false || stripos($log_content, 'TLS') !== false) {
                        echo "<div class='warning'>";
                        echo "⚠️ <strong>TLS/SSL gerelateerde berichten gevonden in log!</strong><br>";
                        echo "Dit bevestigt dat Azure encryptie vereist.";
                        echo "</div>";
                    }
                    
                    echo "<pre>" . htmlspecialchars($log_content) . "</pre>";
                } else {
                    echo "<div class='warning'>⚠️ Log bestand is leeg</div>";
                }
            } else {
                echo "<div class='warning'>⚠️ Log bestand niet gevonden op <code>/tmp/freetds.log</code></div>";
                echo "<p>Controleer of de webserver schrijfrechten heeft voor <code>/tmp/</code></p>";
            }
            
            // Toon FreeTDS configuratie
            echo "<h3>📄 FreeTDS Configuratie</h3>";
            if (file_exists('/etc/freetds/freetds.conf')) {
                echo "<pre>" . htmlspecialchars(file_get_contents('/etc/freetds/freetds.conf')) . "</pre>";
            }
            
            exit;
        }
        
        echo "<div class='success'>";
        echo "✅ <strong>Succesvol verbonden!</strong>";
        echo "</div>";
        
        mssql_close($conn);
        ?>
        
        <hr style="margin: 30px 0;">
        <div class="info">
            <strong>📌 Systeeminformatie</strong><br>
            PHP Versie: <code><?php echo phpversion(); ?></code><br>
            Server: <code><?php echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Onbekend'; ?></code>
        </div>
    </div>
</body>
</html>
