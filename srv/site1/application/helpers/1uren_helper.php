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

function formaturen($uren)
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