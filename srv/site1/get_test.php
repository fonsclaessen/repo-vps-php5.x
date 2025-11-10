<?php

$data = json_decode(file_get_contents('php://input'), true);
$factuurnummer = isset($data['FactuurNummer']) ? $data['FactuurNummer'] : '';

echo '<pre>';
$output="testje";
//$output = shell_exec('dir');
$output = shell_exec('');

$php = '"C:\\Program Files\\PHP\\v7.0\\php.exe"';  //was php_cgi.exe php.exe
$script = '"C:\\inetpub\\wwwroot\\invoice\\proxtest.php"';
$script = '"C:\\inetpub\\wwwroot\\invoice\\proxy_fetch_sas_factuur.php"';
$cmd = $php . ' ' . $script .  ' ' . "3315-25005" . ' 2>&1';
$output2 = shell_exec($cmd);

echo $factuurnummer;
echo "  xx]" . $output2  . " [xx  ";
echo $output;
echo '</pre>';
?>