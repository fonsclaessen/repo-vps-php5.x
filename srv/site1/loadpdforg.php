<?php
        //server controller
		defined('PDFTEMP_PATH') || define('PDFTEMP_PATH', realpath(dirname(__FILE__) . '/../../pdf'));		
		// server direct
		//defined('PDFTEMP_PATH') || define('PDFTEMP_PATH', realpath(dirname(__FILE__) . '/pdf'));
        //mac
        //!! defined('PDFTEMP_PATH') || define('PDFTEMP_PATH', realpath(dirname(__FILE__) . '/pdf'));		
		
		
		$pdf_filename = $_GET["pdf"];
       	$werknemer_id = $_GET["werknemer_id"];
        $jaar = date("Y"); 
		$path = PDFTEMP_PATH . "/" . $jaar . "/" . $werknemer_id . "/" . $pdf_filename;
		header('Content-Length: '.filesize($path)); 
		header("Content-type: application/pdf");
		//header("Content-Disposition: attachment; filename=factuur_" . date("Ymd-His") . ".pdf");
		header("Content-Disposition: attachment; filename=". $path);
		readfile($path);