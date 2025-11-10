<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


function formaturen_uit_minuten_totaal($minuten)
{
	$turen = $minuten / 60;   //uren met minuten decimaal
	$voordekomma = floor($turen);
	$nadekomma = round ((($turen - $voordekomma) * 60.00));   //minuten decimaal weer naar hele minuten.	
	$rv = sprintf("%2d", $voordekomma);
	$rn = sprintf("%02d", $nadekomma);
	return "$rv:$rn";
//	return "2347:11";
	//return ($minuten);
//fout 	return formaturen($turen);
	
}


function mssql_number_format($getal, $decimalen, $teken, $tekenmoetworden) //number_format(12.75, 2, ',', '.');
{
	if (($getal == null) || ($getal == '') || ($getal == '.00')) {
		return number_format('0.00', $decimalen, $teken, $tekenmoetworden);
	} else {
		return number_format($getal, $decimalen, $teken, $tekenmoetworden);
	}
}

function mssql_number_bereken($getal) {
	if (($getal == null) || ($getal == '') || ($getal == '.00')) {
		return 0.00;
	} else {
		return $getal;
	}
}
function mssql2_number_format($getal, $decimalen, $teken, $tekenmoetworden) //number_format(12.75, 2, ',', '.');
{
	if (($getal == null) || ($getal == '') || ($getal == '.00')) {
		return '0,00';
	} else {
		return number_format($getal, $decimalen, $teken, $tekenmoetworden);
	}
}


function formaturen_was($uren)
{
	$voordekomma = floor($uren);      
	$nadekomma = round ((($uren - $voordekomma) * 100.00), 2);
	//$nadekomma = (($uren - $voordekomma) * 100.00);	
	//$rv = sprintf("%02d", $voordekomma);  //ook met voorloopnullen.
	$rv = sprintf("%2d", $voordekomma);
	$rn = sprintf("%02d", $nadekomma);
	return "$rv:$rn";
	//return "$voordekomma:$nadekomma";
	//return "$voordekomma";
	//return "$nadekomma";
}

function formaturen($uren)
{
	$teken="";
	if ($uren < 0.0) {
		$teken="-";
	}
	$voordekomma = floor(abs($uren));
	$nadekomma = round (((abs($uren) - $voordekomma) * 100.00), 2);
	$rv = sprintf("%2d", $voordekomma);
	$rn = sprintf("%02d", $nadekomma);
	return "$teken$rv:$rn";
}


function dagnummer_naar_dagnaam($inummer = 0)
{
   $dag="Maandag.";
   switch ($inummer) {
    case 1:
        $dag="Maandag";
        break;
    case 2:
        $dag="Dinsdag";
        break;
    case 3:
        $dag="Woensdag";
        break;
    case 4:
        $dag="Donderdag";
        break;
    case 5:
        $dag="Vrijdag";
        break;
    case 6:
        $dag="Zaterdag";
        break;
    case 7:
        $dag="Zondag";
        break;
    default:
       $dag="Maandag";
	}
	return $dag;
}

?>