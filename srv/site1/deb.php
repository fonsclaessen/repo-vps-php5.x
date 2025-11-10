// /var/www/html/debug.php
<?php
echo "PHP versie: " . PHP_VERSION . "<br>";
if (function_exists('mssql_connect')) {
    echo "mssql_connect() is GELADEN<br>";
} else {
    die("mssql_connect() NIET GELADEN – php5-sybase ontbreekt");
}

$conn = mssql_connect('tcp:iqmatrix.database.windows.net', 'coopadz', 'CP#k%$Dg^%$DGHrr29VD');
if ($conn) {
    echo "DIRECTE CONNECTIE GELUKT!<br>";
    if (mssql_select_db('bcm_adz', $conn)) {
        echo "Database geselecteerd<br>";
        $res = mssql_query('SELECT 1 AS test', $conn);
        $row = mssql_fetch_array($res);
        echo "Query werkt: " . $row['test'];
    }
    mssql_close($conn);
    $xxx =  mssql_get_last_message();
    echo "tja   " . $xxx;
} else {
    echo "Connectie mislukt: ]" . mssql_get_last_message() ."[";
}
?>
