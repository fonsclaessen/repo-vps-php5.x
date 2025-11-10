<?php
// proxy_fetch_sas.php - Voor PHP 5.3
// Roept een proxy aan op een moderne server om een SAS URL op te halen

$proxy_url = 'https://invoice.dordtonline.nl/proxy_sas.php'; // Proxy endpoint op moderne server
$proxy_url = 'http://invoice.dordtonline.nl/proxy_sas.php'; // Proxy endpoint op moderne server
$proxy_url = 'http://httpbin.org/get';
$proxy_url = 'http://invoice.dordtonline.nl/duh.php'; // Proxy endpoint op moderne server

// Parameters ophalen uit GET of POST
$werknemer_id = isset($_REQUEST['werknemer_id']) ? $_REQUEST['werknemer_id'] : 2020;
$jaar = isset($_REQUEST['jaar']) ? $_REQUEST['jaar'] : 2025;

// Bouw query string
$query = http_build_query(array(
    'werknemer_id' => $werknemer_id,
    'jaar' => $jaar
));

// Gebruik file_get_contents (werkt op PHP 5.3 als allow_url_fopen aan staat)
$url = $proxy_url . '?' . $query;
$headers = array(
    'Accept: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
);
$options = array(
    'http' => array(
        'method'  => 'GET',
        'header'  => implode("\r\n", $headers)
    )
);
$context  = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

header('Content-Type: application/json');
if ($response === false) {
    $error = error_get_last();
    $debug = array('error' => 'Fout bij ophalen van de SAS-URL via proxy.');
    if ($error) {
        $debug['php_error'] = $error;
    }
    if (isset($http_response_header)) {
        $debug['http_response_header'] = $http_response_header;
    }
    echo json_encode($debug);
} else {
    echo $response;
}
