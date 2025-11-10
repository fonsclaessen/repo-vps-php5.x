<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class pdf_model extends CI_Model {

	function __construct() {
		parent::__construct ();
	}

	public function aanelkaar($tekst) {
		$resultaat = str_replace(" ", "", $tekst);
		$resultaat = str_replace("/", "", $resultaat);
		$resultaat = str_replace("\\", "", $resultaat);
		$resultaat = str_replace("&", "", $resultaat);
		$resultaat = str_replace("-", "", $resultaat);
		$resultaat = str_replace("_", "", $resultaat);
		$resultaat = str_replace(".", "", $resultaat);
		return $resultaat;
	}
	
	public function metstreepje($tekst) {
		$resultaat = str_replace(" ", "", $tekst);
		$resultaat = str_replace("/", "", $resultaat);
		$resultaat = str_replace("\\", "", $resultaat);
		return $resultaat;
	}

	public function plurkje_met_slesjbek($tekst) {
		$resultaat = str_replace("&", "/&", $tekst);
		return $resultaat;
	}

	function get_chart_pie ( $werknemer_id  ) {
		$jaartal = Date("Y");

		$resultaat = array();
		
		$sql = "SELECT soort, percentage " .  
			"FROM zzp_grafiek_pie " .
			" WHERE (userid = $werknemer_id) AND (rowstatus = '') AND (jaar = $jaartal) ";
		
		$t = "[ ";
		$query = $this->db->query ( $sql );
		$volgende = false;
		foreach ( $query ->result() as $row ) {
			if ($volgende) {
				$t = $t . ", ";
			}
			$t = $t . "{ Uren: '$row->soort', Share: $row->percentage }";
			if ($volgende==false) {
				$volgende=true;
			}
		}
		$t = $t . " ]";
		$resultaat['data'] = $t;
		$resultaat['ditjaar'] = $jaartal;
		
		$resultaat['tonen'] = true;
		return $resultaat;
	}

	function get_chart_staaf ($werknemer_id) {
		$jaartal = Date("Y");
		$vorig_jaar = Date("Y") -1;

		$resultaat = array();
		
		$sql = " SELECT naam, max, omzet_ditjaar, omzet_vorigjaar" .
			" FROM zzp_grafiek_staaf" .		
			" WHERE (userid = $werknemer_id) AND (rowstatus = '') AND (jaar = $jaartal) ";
		
		$query = $this->db->query ( $sql );
		$t = "[ ";
		$vorigjaar = "{ Jaar: '$vorig_jaar', "; 
		$ditjaar = "{ Jaar: '$jaartal', ";
		$aantal_vorigjaar = 0;
		$aantal_ditjaar = 0;
		$volgende = false;
		foreach ( $query ->result() as $row ) {
			if ($row->omzet_vorigjaar==0) {
				continue;
			}
			$aantal_vorigjaar++;
			if ($volgende) {
				$vorigjaar  = $vorigjaar  . ", ";
			}
			$vorigjaar = $vorigjaar  . $this->aanelkaar($row->naam) . ": " . $row->omzet_vorigjaar;
			if ($volgende==false) {
				$volgende=true;
			}
		}
		$vorigjaar = $vorigjaar . "}";
		$volgende = false;
		$legandavelden = "[ ";
		foreach ( $query ->result() as $row ) {
			if ($volgende) {
				$legandavelden = $legandavelden  . ", ";
			}
			$legandavelden = $legandavelden . "{ dataField: '" . $this->aanelkaar($row->naam) . "', displayText: '$row->naam', showLabels: true }";
			if ($volgende==false) {
				$volgende=true;
			}
		}		
		$legandavelden = $legandavelden . " ]";
		$volgende = false;
		$max = 50000;
		$interval = 5000;
		foreach ( $query ->result() as $row ) {

			if ($row->omzet_ditjaar==0) {
				continue;
			}
			$max = $row->max;
			$aantal_ditjaar++;
			if ($volgende) {
				$ditjaar  = $ditjaar  . ", ";
			}
			$ditjaar  = $ditjaar  . $this->aanelkaar($row->naam) . ": " . $row->omzet_ditjaar;
			if ($volgende==false) {
				$volgende=true;
			}
		}
		$ditjaar = $ditjaar . "}";
		if ($aantal_vorigjaar>0) {
			$t = $t . $vorigjaar;
		}
		if (($aantal_vorigjaar>0) && ($aantal_ditjaar>0)) {
			$t = $t . ", ";
		}
		if ($aantal_ditjaar>0) {
			$t = $t . $ditjaar;
		}
		$t = $t . "]";
		$resultaat['legenda'] = $legandavelden;
		$resultaat['data'] = $t;
		$resultaat['tonen'] = true;
		
		$interval = round($max / 10);
		$resultaat['max'] = $max;
		$resultaat['interval'] = $interval;
		$resultaat['ditjaar'] = $jaartal;
		$resultaat['vorigjaar'] = $vorig_jaar;
		return $resultaat;
	}


	function get_chart_pie2 ( $werknemer_id  ) {
		$jaartal = Date("Y");

		$resultaat = array();
		
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		$sql = "SELECT YEAR(GETDATE()) AS jaar, nawnr,
(SELECT naam
FROM naw
WHERE (naw = uren.nawnr)) AS naam, SUM(duur_uren * 60 + duur_minuten) AS Expr1,
(SELECT        SUM(duur_uren * 60 + duur_minuten) AS minuten
FROM            uren AS u2
WHERE        (rowstatus = '') AND (jaar = YEAR(GETDATE())) AND (werknemer_id = $werknemer_id)) AS max, CAST(SUM(duur_uren * 60 + duur_minuten) AS decimal(10, 1)) /
((SELECT        SUM(duur_uren * 60 + duur_minuten) AS Expr1
FROM            uren AS u2
WHERE        (rowstatus = '') AND (jaar = YEAR(GETDATE())) AND (werknemer_id = $werknemer_id)) / 100) AS percentage
FROM            uren
WHERE        (rowstatus = '') AND (jaar = YEAR(GETDATE())) AND (werknemer_id = $werknemer_id) "
			. " AND (Datum < CONVERT(date, GETDATE()))"
			. " GROUP BY nawnr
ORDER BY percentage DESC";
		
		
		
		$t = "[ ";
		
		//$query = $this->db->query ( $sql );
		$query = $otherdb->query ( $sql );
		
		$volgende = false;
		foreach ( $query ->result() as $row ) {
			if ($volgende) {
				$t = $t . ", ";
			}
			$t = $t . "{ Uren: '$row->naam', Share: " . round($row->percentage, 1) . " }";
			if ($volgende==false) {
				$volgende=true;
			}
		}
		$t = $t . " ]";
		$resultaat['data'] = $t;
		$resultaat['ditjaar'] = $jaartal;
		
		$resultaat['tonen'] = true;
		return $resultaat;
	}

	function get_chart_staaf2 ($werknemer_id) {
		$jaartal = Date("Y");
		$vorig_jaar = Date("Y") -1;

		$resultaat = array();
		
		
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		
		
		$sql = "SELECT YEAR(GETDATE()) AS jaar, WerknemerID AS userid, pst_naam AS naam,
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE()) - 1) AND (rowstatus = '') AND (soort = 'U') AND (pst_naam = facturen.pst_naam)) AS omzet_vorigjaar,
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE())) AND (rowstatus = '') AND (soort = 'U') AND (pst_naam = facturen.pst_naam)) AS omzet_ditjaar, CASE WHEN
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE()) - 1) AND (rowstatus = '') AND (soort = 'U')) >
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE())) AND (rowstatus = '') AND (soort = 'U')) THEN
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE()) - 1) AND (rowstatus = '') AND (soort = 'U')) ELSE
   (SELECT ISNULL(SUM(bedrag), 0.0) AS Expr1
      FROM  facturen AS f2
      WHERE (WerknemerID = facturen.WerknemerID) AND (jaar = YEAR(GETDATE())) AND (rowstatus = '') AND (soort = 'U')) END AS max
FROM facturen
WHERE werknemerid = $werknemer_id and  (rowstatus = '') AND (soort = 'U') AND (jaar >= YEAR(GETDATE()) - 1)"
			. " AND (Datum < CONVERT(date, GETDATE()))"
			. "GROUP BY WerknemerID, pst_naam
ORDER BY omzet_ditjaar DESC, omzet_vorigjaar DESC";
		
		
		
		$query = $otherdb->query ( $sql );
		
		
		$t = "[ ";
		$vorigjaar = "{ Jaar: '$vorig_jaar', "; 
		$ditjaar = "{ Jaar: '$jaartal', ";
		$aantal_vorigjaar = 0;
		$aantal_ditjaar = 0;
		$volgende = false;

		$labelnummer= 0;
		$zucht  = 2;

		foreach ( $query ->result() as $row ) {
			if ($row->omzet_vorigjaar==0) {
				continue;
			}
			$aantal_vorigjaar++;
			if ($volgende) {
				$vorigjaar  = $vorigjaar  . ", ";
			}

			if (1==$zucht) {
				$labelnummer++;
				$label = "label$labelnummer";
				$vorigjaar = $vorigjaar  . $label . ": " . $row->omzet_vorigjaar;
			} else {
				//zo was het, geeft soms problemen met vreemde tekens in de naam
				$vorigjaar = $vorigjaar  . $this->aanelkaar($row->naam) . ": " . $row->omzet_vorigjaar;
			}

			if ($volgende==false) {
				$volgende=true;
			}
		}
		$vorigjaar = $vorigjaar . "}";
		$volgende = false;
		$legandavelden = "[ ";

		$labelnummer= 0;
		foreach ( $query ->result() as $row ) {
			if ($volgende) {
				$legandavelden = $legandavelden  . ", ";
			}

			if (1==$zucht) {
				$labelnummer++;
				$label = "label$labelnummer";
				$legandavelden = $legandavelden . "{ dataField: '" . $label  . "', displayText: '" . $this->plurkje_met_slesjbek($row->naam) . "', showLabels: true }";			
			} else {
				//zo was het, geeft soms problemen met vreemde tekens in de naam
				$legandavelden = $legandavelden . "{ dataField: '" . $this->aanelkaar($row->naam) . "', displayText: '" . $this->plurkje_met_slesjbek($row->naam) . "', showLabels: true }";
			}

			if ($volgende==false) {
				$volgende=true;
			}
		}		
		$legandavelden = $legandavelden . " ]";

		$volgende = false;
		$max = 50000;
		$interval = 5000;
		$labelnummer= 0;
		foreach ( $query ->result() as $row ) {

			if ($row->omzet_ditjaar==0) {
				continue;
			}
			$max = $row->max;
			$aantal_ditjaar++;
			if ($volgende) {
				$ditjaar  = $ditjaar  . ", ";
			}

			if (1==$zucht) {
				$labelnummer++;
				$label = "label$labelnummer";
				$ditjaar  = $ditjaar  . $label  . ": " . $row->omzet_ditjaar . ", labels: { visible: true, offset: { x: 30, y: 0 } }";
			} else {
				//zo was het, geeft soms problemen met vreemde tekens in de naam
				$ditjaar  = $ditjaar  . $this->aanelkaar($row->naam) . ": " . $row->omzet_ditjaar . ", labels: { visible: true, offset: { x: 30, y: 0 } }";
			}

			if ($volgende==false) {
				$volgende=true;
			}
		}
		$ditjaar = $ditjaar . "}";
		if ($aantal_vorigjaar>0) {
			$t = $t . $vorigjaar;
		}
		if (($aantal_vorigjaar>0) && ($aantal_ditjaar>0)) {
			$t = $t . ", ";
		}
		if ($aantal_ditjaar>0) {
			$t = $t . $ditjaar;
		}
		$t = $t . "]";
		
		/** /		
				$legandavelden = "[ { dataField: 'DeRIBWHeuvellandXMaasvallei', displayText: 'De RIBW Heuvelland \& Maasvallei', showLabels: true },
		 { dataField: 'MOVEOOMaatchappelijkeOpvang', displayText: 'MOVEOO Maatchappelijke Opvang', showLabels: true }, 
		 { dataField: 'MOVEOOMaatschappelijkeOpvang', displayText: 'MOVEOO Maatschappelijke Opvang', showLabels: true },
		 { dataField: 'OZOdoeikmee', displayText: 'OZO doe ik mee', showLabels: true }, 
		 { dataField: 'StichtingXonar', displayText: 'Stichting Xonar', showLabels: true } ]";

				$t = "[ { Jaar: '2014',
		 DeRIBWHeuvellandXMaasvallei: 1202.25,
		 MOVEOOMaatchappelijkeOpvang: 8328.31,
		 MOVEOOMaatschappelijkeOpvang: 6139.35, 
		 OZOdoeikmee: 78.75,
		 StichtingXonar: 19564.49}, 
		 { Jaar: '2015',
		 DeRIBWHeuvellandXMaasvallei: 3254.20, 
		 MOVEOOMaatschappelijkeOpvang: 18204.13, 
		 OZOdoeikmee: 5445.80}]";
		/**/		
		
		$resultaat['legenda'] = $legandavelden;
		$resultaat['data'] = $t;


		$resultaat['tonen'] = true;
		
		$interval = round($max / 10);
		$resultaat['max'] = $max;
		$resultaat['interval'] = $interval;
		$resultaat['ditjaar'] = $jaartal;
		$resultaat['vorigjaar'] = $vorig_jaar;
		return $resultaat;
	}


	function get_fiatering_count ($werknemer_id, $filteropfiat, $soortdeelnemer='zzp')
	{
		$sql = "SELECT count(*) as aantal FROM zzp_projecten p, zzp_urenbriefje b, zzp_uren u, zzp_users l " .
			"WHERE p.projectnr = b.projectnr " .
			"AND b.id = u.urenbriefjeID " .
			"AND p.opdrachtgeverID = $werknemer_id " .
			"AND b.fiat_zzp = 'J' " .
			"AND l.deelnemer = '$soortdeelnemer' " .
			"AND l.gebruikerID = b.WerknemerID ";
		
		if ($filteropfiat == 1)
		{
			$sql .= " AND b.fiat_opdrachtgever = 'N' " ;
		}

		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}


	function get_fiatering_list($num, $offset, $werknemerid, $filteropfiat, $soortdeelnemer='zzp') 
	{  
		//MSSQLTOD!
		$sql = "SELECT b.id, b.volgnr, b.fiat_zzp, b.fiat_opdrachtgever, b.afgekeurd_opdrachtgever, p.projectnr, b.week, b.jaar, l.company_name " .
			"FROM zzp_projecten p, zzp_urenbriefje b, zzp_users l " .
			"WHERE p.projectnr = b.projectnr " .
			"AND p.opdrachtgeverID = $werknemerid " .
			"AND b.fiat_zzp = 'J' " .
			"AND l.deelnemer = '$soortdeelnemer' " .
			"AND l.gebruikerID = b.WerknemerID " ;
		
		if ($filteropfiat == 1)
		{
			$sql .= " AND b.fiat_opdrachtgever = 'N' " ;
		}

		$sql .= " ORDER BY b.werknemerid, p.projectnr, b.jaar, b.week ";
		
		$sql .= " LIMIT $offset, $num ";
		
		$query = $this->db->query ( $sql );
		return $query;
	}

	function get_fiatering_list_oud($num, $offset, $werknemerid, $filteropfiat, $soortdeelnemer='zzp') 
	{ 
		//MSSQLTODO
		$sql = "SELECT p.projectnr, b.week, b.jaar, b.fiat_opdrachtgever, u.totaal, DATE(u.datum) as dedatum, l.company_name, u.id as uren_id FROM zzp_projecten p, zzp_urenbriefje b, zzp_uren u, zzp_users l " .
			"WHERE p.projectnr = b.projectnr " .
			"AND b.id = u.urenbriefjeID " .
			"AND p.opdrachtgeverID = $werknemerid " .
			"AND u.fiat_uren = 'N' " .
			"AND DATE(u.mut_datum) < DATE(NOW())  " .
			"AND l.gebruikerID = b.WerknemerID " .
			"AND l.deelnemer = '$soortdeelnemer' " .
			"LIMIT $offset, $num ";
		
		$query = $this->db->query ( $sql );
		return $query;
	}

	function set_fiat_afgekeurd_reden_perwerkbriefje($werkbriefje_id, $fiat, $afgekeurd, $reden) 
	{
		$sql = "select id FROM zzp_urenbriefje b " . 
			" WHERE id=$werkbriefje_id and fiat_zzp='J'" ;
		
		$aantal_rows = 1; //fix, we testen niet meer op fiat_zzp, omdat die na afkeuren J ook op N gezet wordt
		
		if ($aantal_rows == 0) {
			$resultaat = false;
		} else {
			$extra = "";
			if ($afgekeurd == "J") {
				$extra = " fiat_zzp = 'N', ";
			}
			$sql = "UPDATE zzp_urenbriefje b SET b.fiat_opdrachtgever='$fiat', b.afgekeurd_opdrachtgever='$afgekeurd', ". $extra . "b.afgekeurd_reden='$reden'" . " WHERE id=$werkbriefje_id and fiat_zzp='J'" ;
			$query = $this->db->query ( $sql );
			$resultaat = true;
		}
		return $resultaat;
	}

	function get_fiat_details_week($werknemer_id = -1, $uren_werkbriefje_id = -1, $soortdeelnemer='zzp')
	{
		$sql = "SELECT b.id, b.volgnr, b.fiat_zzp, b.fiat_opdrachtgever, b.afgekeurd_opdrachtgever, b.afgekeurd_reden, b.declareer_km, p.projectnr, p.locatie, p.afdeling, b.week, b.jaar, l.company_name " .
			"FROM zzp_projecten p, zzp_urenbriefje b, zzp_users l " .
			"WHERE p.projectnr = b.projectnr " .
			"AND p.opdrachtgeverID = $werknemer_id " .
			"AND b.fiat_zzp = 'J' " .
			"AND l.gebruikerID = b.WerknemerID " .
			"AND l.deelnemer = '$soortdeelnemer' " .
			"AND b.id = $uren_werkbriefje_id ";
		
		$query = $this->db->query ( $sql );
		return $query;
	}

	function get_fiat_details_week_dagen($werknemer_id = -1, $uren_werkbriefje_id = -1, $soortdeelnemer='zzp')
	{
		//de dagen met de uren en km en onkosten details.	
		$sql = "SELECT p.projectnr, b.week, b.jaar, b.werknemerID, WEEKDAY(u.datum)+1 as dagvanweek, DATE(u.datum) as dedatum, l.company_name, u.* " .
			"FROM zzp_projecten p, zzp_urenbriefje b, zzp_uren u, zzp_users l " .
			"WHERE p.projectnr = b.projectnr " .
			"AND b.id = u.urenbriefjeID " . 
			"AND p.opdrachtgeverID = $werknemer_id " .
			"AND l.gebruikerID = b.WerknemerID " .
			"AND l.deelnemer = '$soortdeelnemer' " . 
			"AND b.id = $uren_werkbriefje_id " .
			"ORDER BY WEEKDAY(u.datum) + 1 ";
		
		$query = $this->db->query ( $sql );
		return $query;
	}


	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//>>>>> OUD
	function grid_get_pdf_count($werknemerid, $filter_jaartal) {
		$query = $this->db->query ("SELECT COUNT(*) as aantal FROM zzp_facturen WHERE WerknemerID = $werknemerid AND Week <> 0 AND actief = 'A' AND boekjaar = $filter_jaartal");
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}
	function get_pdf($num, $offset, $sidx, $sord, $werknemerid, $filter_jaartal) {		
		if ($offset == null) {
			$offset = 0;
		}
		$eraf = 0;
		$sql = "SELECT id, WerknemerID, Opdrachtgever, Factuurnr,
			CAST(FactuurDatum AS DATE) AS FactuurDatumDATEONLY,
			CAST(VervalDatum AS DATE) AS VervalDatumDATEONLY,
			CAST(Betaalddatum AS DATE) AS BetaalddatumDATEONLY,
			(Bemiddeling + BemiddelingBTW + Administratie + AdministratieBTW + Contributie + ContributieBTW + BorgZZP + KostenVoorschot + KostenVoorschotBTW) as kosten, 
			Week, FactuurTotaal, FactuurTotaalBTW, Voorschot, KostenVoorschot, KostenVoorschotBTW, BorgZZP,
			FactuurADZ, Bemiddeling, BemiddelingBTW, Administratie, AdministratieBTW, Contributie, ContributieBTW, uren,
			Resteert, 	
			(FactuurTotaalBTW-(KostenVoorschotBTW+BemiddelingBTW+AdministratieBTW+ContributieBTW)) as BtwSaldo2,
			CASE WHEN FactuurTotaalBTW<>0 THEN FactuurTotaalBTW-(KostenVoorschotBTW+BemiddelingBTW+AdministratieBTW+ContributieBTW ) ELSE 0 END as BtwSaldo1,
			CASE WHEN btwcode<>0 THEN FactuurTotaalBTW-(KostenVoorschotBTW+BemiddelingBTW+AdministratieBTW+ContributieBTW) ELSE 0 END as BtwSaldo,
			Actief
			FROM zzp_facturen " 
			. "WHERE WerknemerID = $werknemerid AND Week <> 0 AND actief = 'A' and boekjaar = $filter_jaartal order by $sidx $sord "  //default is : order by WerknemerID, Factuurnr
			. " OFFSET ($offset - $eraf) ROWS "
			. " FETCH NEXT $num ROWS ONLY"
		;
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	//<<<<<<<<<< OUD


	function grid_get_pdf2($num, $offset, $sidx, $sord, $werknemerid, $filter_jaartal) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		if ($offset == null) {
			$offset = 0;
		}
		$eraf = 0;


//deze geeft een fout, dat er meer dan 1 record in een subquery zit.
		$sql = "SELECT id
     , pst_naam AS Opdrachtgever
     , Factuurnr
     , Datum AS FactuurDatumDATEONLY
     ,  convert(DATE, BetaaldDatum) AS BetaalddatumDATEONLY, DATEADD(DAY, betalingstermijn, Datum) AS VervalDatumDATEONLY, Week, bedrag + btwbedrag AS FactuurTotaal, 
                         btwbedrag AS FactuurTotaalBTW, admFactuur AS FactuurADZ, ISNULL
                             (( SELECT sum(duur_uren * 60 + duur_minuten) AS Expr1
                                FROM
                                  uren
                                WHERE
                                  (facturen.urenbriefjeid = urenbriefjeid)
                                  AND (rowstatus = '')), 0) AS minuten, ISNULL
                             ((
                                SELECT bedrag - borg AS A
                                FROM
                                  bce_regels
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS contributie, ISNULL
                             ((
                                SELECT borg
                                FROM
                                  bce_regels AS bce_regels_2
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS BorgZZP,
                             (
                                SELECT convert(DATE, datum) AS Expr1
                                FROM
                                  bce_regels AS bce_regels_1
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)) AS contributieDatum, ISNULL
                             ((
                                SELECT sum(Bedrag) AS Expr1
                                FROM
                                  Voorschotten
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS Voorschot, CASE WHEN geen_btw = 0 THEN btwbedrag - (round(ISNULL
                             ((
                                SELECT sum(KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_2
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0), 2) + round(ISNULL
                             ((
                                SELECT sum((bedrag / 100) * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0), 2) + round(ISNULL
                             ((
                                SELECT sum((bedrag / 100) * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_2
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0), 2)) ELSE 0 END AS BtwSaldo, ISNULL
                             ((
                                SELECT sum(Kosten) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_3
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS kostenvoorschot, ISNULL
                             ((
                                SELECT sum(KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_2
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS kostenvoorschotbtw,
                             (
                                SELECT max(mutdatum) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_1
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')) AS Voorschotdatum, soort AS factuursoort, 
								
                             (bedrag + btwbedrag) - (ISNULL
                             ((SELECT        SUM(Bedrag + Kosten + KostenBTW) AS Expr1
                                 FROM            Voorschotten AS Voorschotten_4
                                 WHERE        (factuurid = facturen.id) AND (rowstatus = '')), 0.0) + ISNULL
                             ((SELECT        bedrag - borg AS contributie
                                 FROM            bce_regels AS bce_regels_3
                                 WHERE        (rowstatus = '') AND (zzpfactuurnr = facturen.factuurnr)), 0.0) + ISNULL
                             ((SELECT        SUM(bedrag + bedrag / 100 * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_1
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) + ISNULL
                             ((SELECT        SUM(bedrag + bedrag / 100 * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_1
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0)) AS Resteert

                             , ISNULL
                             ((SELECT        SUM(Kosten + KostenBTW) AS Expr1
                                 FROM            Voorschotten AS Voorschotten_4
                                 WHERE        (factuurid = facturen.id) AND (rowstatus = '')), 0.0) + ISNULL
                             ((SELECT        SUM(bedrag + bedrag / 100 * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_1
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) + ISNULL
                             ((SELECT        SUM(bedrag + bedrag / 100 * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_1
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS kosten
								
                             , ISNULL
                             ((
                                SELECT sum(duur_uren * 60 + duur_minuten) / 60 + cast(sum(duur_uren * 60 + duur_minuten) - sum(duur_uren * 60 + duur_minuten) / 60 * 60 AS DECIMAL) / 100 AS Expr1
                                FROM
                                  uren AS uren_1
                                WHERE
                                  (facturen.urenbriefjeid = urenbriefjeid)
                                  AND (rowstatus = '')), 0.0) AS uren, ISNULL
                             ((
                                SELECT sum(bedragverdelen) AS Expr1
                                FROM
                                  Urenbriefje
                                WHERE
                                  (facturen.urenbriefjeid = id)
                                  AND (rowstatus = ' ')), 0) AS verdeelminuten, ISNULL
                             ((
                                SELECT sum(bedrag) AS Expr1
                                FROM
                                  facturen_regels
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS Administratie, ISNULL
                             ((
                                SELECT sum(bedrag) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS Bemiddeling, ISNULL
                             ((
                                SELECT sum(bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_2
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS AdministratieBTW, ISNULL
                             ((
                                SELECT sum(bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS BemiddelingBTW, mutdatum, id AS localid
			FROM facturen "
			. " WHERE WerknemerID = $werknemerid AND soort = 'U' AND rowstatus = '' and jaar = $filter_jaartal AND (Datum < CONVERT(date, GETDATE()))"
			. " ORDER BY $sidx $sord "
		;

		///banaan
		$sql = "SELECT id
     , pst_naam AS Opdrachtgever
     , Factuurnr
     , Datum AS FactuurDatumDATEONLY
     , convert(DATE, BetaaldDatum) AS BetaalddatumDATEONLY
     , dateadd(DAY, betalingstermijn, Datum) AS VervalDatumDATEONLY
     , Week
     , bedrag + btwbedrag AS FactuurTotaal
     , btwbedrag AS FactuurTotaalBTW
     , admFactuur AS FactuurADZ
     , isnull((SELECT sum(duur_uren * 60 + duur_minuten) AS Expr1
               FROM
                 uren
               WHERE
                 (facturen.urenbriefjeid = urenbriefjeid)
                 AND (rowstatus = '')), 0) AS minuten
     , isnull((SELECT sum(bedrag - borg) AS A
               FROM
                 bce_regels
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS contributie
     , isnull((SELECT sum(borg)
               FROM
                 bce_regels AS bce_regels_2
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS BorgZZP
     , (SELECT convert(DATE, max(datum)) AS Expr1
        FROM
          bce_regels AS bce_regels_1
        WHERE
          (rowstatus = '')
          AND (zzpfactuurnr = facturen.factuurnr)) AS contributieDatum
     , isnull((SELECT sum(Bedrag) AS Expr1
               FROM
                 Voorschotten
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS Voorschot
     , CASE
         WHEN geen_btw = 0 THEN
           btwbedrag - (round(isnull((SELECT sum(KostenBTW) AS Expr1
                                      FROM
                                        Voorschotten AS Voorschotten_2
                                      WHERE
                                        (factuurid = facturen.id)
                                        AND (rowstatus = ' ')), 0.0), 2) + round(isnull((SELECT sum((bedrag / 100) * btw) AS Expr1
                                                                                         FROM
                                                                                           facturen_regels AS facturen_regels_1
                                                                                         WHERE
                                                                                           (vrachtbrief = facturen.factuurnr)
                                                                                           AND (rowstatus = '')
                                                                                           AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0), 2) + round(isnull((SELECT sum((bedrag / 100) * btw) AS Expr1
                                                                                                                                                              FROM
                                                                                                                                                                facturen_regels AS facturen_regels_2
                                                                                                                                                              WHERE
                                                                                                                                                                (vrachtbrief = facturen.factuurnr)
                                                                                                                                                                AND (rowstatus = '')
                                                                                                                                                                AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0), 2))
         ELSE
           0
       END AS BtwSaldo
     , isnull((SELECT sum(Kosten) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_3
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschot
     , isnull((SELECT sum(KostenBTW) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_2
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschotbtw
     , (SELECT max(mutdatum) AS Expr1
        FROM
          Voorschotten AS Voorschotten_1
        WHERE
          (factuurid = facturen.id)
          AND (rowstatus = ' ')) AS Voorschotdatum
     , soort AS factuursoort
     , (bedrag + btwbedrag) - (isnull((SELECT sum(Bedrag + Kosten + KostenBTW) AS Expr1
                                       FROM
                                         Voorschotten AS Voorschotten_4
                                       WHERE
                                         (factuurid = facturen.id)
                                         AND (rowstatus = '')), 0.0) + isnull((SELECT sum(bedrag - borg) AS contributie
                                                                               FROM
                                                                                 bce_regels AS bce_regels_3
                                                                               WHERE
                                                                                 (rowstatus = '')
                                                                                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) + isnull((SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                                                                                                                          FROM
                                                                                                                                            facturen_regels AS facturen_regels_1
                                                                                                                                          WHERE
                                                                                                                                            (vrachtbrief = facturen.factuurnr)
                                                                                                                                            AND (rowstatus = '')
                                                                                                                                            AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) + isnull((SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                                                                                                                                                                                     FROM
                                                                                                                                                                                                       facturen_regels AS facturen_regels_1
                                                                                                                                                                                                     WHERE
                                                                                                                                                                                                       (vrachtbrief = facturen.factuurnr)
                                                                                                                                                                                                       AND (rowstatus = '')
                                                                                                                                                                                                       AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0)) AS Resteert
     , isnull((SELECT sum(Kosten + KostenBTW) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_4
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = '')), 0.0) + isnull((SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                                       FROM
                                                         facturen_regels AS facturen_regels_1
                                                       WHERE
                                                         (vrachtbrief = facturen.factuurnr)
                                                         AND (rowstatus = '')
                                                         AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) + isnull((SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                                                                                                  FROM
                                                                                                                    facturen_regels AS facturen_regels_1
                                                                                                                  WHERE
                                                                                                                    (vrachtbrief = facturen.factuurnr)
                                                                                                                    AND (rowstatus = '')
                                                                                                                    AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS kosten
     , isnull((SELECT sum(duur_uren * 60 + duur_minuten) / 60 + cast(sum(duur_uren * 60 + duur_minuten) - sum(duur_uren * 60 + duur_minuten) / 60 * 60 AS DECIMAL) / 100 AS Expr1
               FROM
                 uren AS uren_1
               WHERE
                 (facturen.urenbriefjeid = urenbriefjeid)
				 AND year(uren_1.datum)=$filter_jaartal
                 AND (rowstatus = '')), 0.0) AS uren
     , isnull((SELECT sum(bedragverdelen) AS Expr1
               FROM
                 Urenbriefje
               WHERE
                 (facturen.urenbriefjeid = id)
                 AND (rowstatus = ' ')), 0) AS verdeelminuten
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS Administratie
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS Bemiddeling
     , isnull((SELECT sum(bedrag / 100 * btw) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_2
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS AdministratieBTW
     , isnull((SELECT sum(bedrag / 100 * btw) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS BemiddelingBTW
     , mutdatum
     , id AS localid
FROM
  facturen
WHERE
  WerknemerID = $werknemerid
  AND soort = 'U'
  AND rowstatus = ''
  AND jaar = $filter_jaartal
  AND (Datum < convert(DATE, getdate()))
ORDER BY $sidx $sord "
;

			//. " WHERE WerknemerID = $werknemerid AND soort = 'U' AND rowstatus = '' and jaar = $filter_jaartal AND (Datum < CONVERT(date, GETDATE()))"
			//. " ORDER BY $sidx $sord "
		
		$query = $otherdb->query ( $sql );
		return $query;
	}


	function grid_get_voorsommeren($werknemerid, $filter_jaartal) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		
		$sql = " SELECT id, pst_naam AS Opdrachtgever
     , Factuurnr
     , Datum AS FactuurDatumDATEONLY
     , convert(DATE, BetaaldDatum) AS BetaalddatumDATEONLY
     , dateadd(DAY, betalingstermijn, Datum) AS VervalDatumDATEONLY
     , Week
     , bedrag AS FactuurTotaal
     , btwbedrag AS FactuurTotaalBTW
     , admFactuur AS FactuurADZ
     , isnull((SELECT sum(duur_uren * 60 + duur_minuten) AS Expr1
               FROM
                 uren
               WHERE
                 facturen.urenbriefjeid = urenbriefjeid
                 AND rowstatus = ''), 0) AS minuten
     , isnull((SELECT bedrag - borg AS A
               FROM
                 bce_regels
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS contributie
     , isnull((SELECT borg
               FROM
                 bce_regels AS bce_regels_2
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS BorgZZP
     , (SELECT convert(DATE, datum) AS Expr1
        FROM
          bce_regels AS bce_regels_1
        WHERE
          (rowstatus = '')
          AND (zzpfactuurnr = facturen.factuurnr)) AS contributieDatum
     , isnull((SELECT sum(Bedrag) AS Expr1
               FROM
                 Voorschotten
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS Voorschot
				
				
,CASE WHEN geen_btw = 0 THEN btwbedrag - (round(ISNULL
                             ((SELECT        SUM(KostenBTW) AS Expr1
                                 FROM            Voorschotten AS Voorschotten_2
                                 WHERE        (factuurid = facturen.id) AND (rowstatus = ' ')), 0.0) + 0.004, 2) + round(ISNULL
                             ((SELECT        SUM((bedrag / 100) * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_1
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0), 2) + round(ISNULL
                             ((SELECT        SUM((bedrag / 100) * btw) AS Expr1
                                 FROM            facturen_regels AS facturen_regels_2
                                 WHERE        (vrachtbrief = facturen.factuurnr) AND (rowstatus = '') AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0), 2)) ELSE 0 END AS BtwSaldo

				
				
     , isnull((SELECT sum(Kosten) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_3
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschot
     , isnull((SELECT sum(KostenBTW) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_2
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschotbtw
     , (SELECT max(mutdatum) AS Expr1
        FROM
          Voorschotten AS Voorschotten_1
        WHERE
          (factuurid = facturen.id)
          AND (rowstatus = ' ')) AS Voorschotdatum
     , soort AS factuursoort
     , (bedrag + btwbedrag) - (isnull((SELECT sum(Bedrag + Kosten + KostenBTW) AS Expr1
                                       FROM
                                         Voorschotten AS Voorschotten_4
                                       WHERE
                                         (factuurid = facturen.id)
                                         AND (rowstatus = '')), 0.0) + isnull((SELECT bedrag - borg AS contributie
                                                                               FROM
                                                                                 bce_regels AS bce_regels_3
                                                                               WHERE
                                                                                 (rowstatus = '')
                                                                                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) + isnull((SELECT sum(btwbedrag + bedrag) AS Expr1
                                                                                                                                          FROM
                                                                                                                                            facturen_regels AS facturen_regels_1
                                                                                                                                          WHERE
                                                                                                                                            (vrachtbrief = facturen.factuurnr)
                                                                                                                                            AND (rowstatus = '')
                                                                                                                                            AND (artnr IN ('BEMKB001', 'BEMKP001', 'ADMKB001', 'ADMKP001'))), 0.0)) AS Resteert
     , isnull((SELECT sum(Bedrag + Kosten + KostenBTW) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_4
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = '')), 0.0) + isnull((SELECT bedrag - borg AS contributie
                                                       FROM
                                                         bce_regels AS bce_regels_3
                                                       WHERE
                                                         (rowstatus = '')
                                                         AND (zzpfactuurnr = facturen.factuurnr)), 0.0) + isnull((SELECT sum(btwbedrag + bedrag) AS Expr1
                                                                                                                  FROM
                                                                                                                    facturen_regels AS facturen_regels_1
                                                                                                                  WHERE
                                                                                                                    (vrachtbrief = facturen.factuurnr)
                                                                                                                    AND (rowstatus = '')
                                                                                                                    AND (artnr IN ('BEMKB001', 'BEMKP001', 'ADMKB001', 'ADMKP001'))), 0.0) AS kosten
     , isnull((SELECT sum(duur_uren * 60 + duur_minuten) / 60 + cast(sum(duur_uren * 60 + duur_minuten) - sum(duur_uren * 60 + duur_minuten) / 60 * 60 AS DECIMAL) / 100 AS Expr1
               FROM
                 uren
               WHERE
                 (facturen.urenbriefjeid = urenbriefjeid)
                 AND (rowstatus = '')), 0.0) AS uren
     , isnull((SELECT sum(bedragverdelen) AS Expr1
               FROM
                 Urenbriefje
               WHERE
                 (facturen.urenbriefjeid = id)
                 AND (rowstatus = ' ')), 0) AS verdeelminuten
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS Administratie
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS Bemiddeling
     , isnull((SELECT sum(btwbedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_2
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS AdministratieBTW
     , isnull((SELECT sum(btwbedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS BemiddelingBTW
     , mutdatum
     , id AS localid   FROM facturen " 
			. "WHERE WerknemerID = $werknemerid AND soort = 'U' AND rowstatus = '' and jaar = $filter_jaartal  AND (Datum < CONVERT(date, GETDATE()))"
		;

		$sql = "SELECT id
     , pst_naam AS Opdrachtgever
     , Factuurnr
     , Datum AS FactuurDatumDATEONLY
     ,  convert(DATE, BetaaldDatum) AS BetaalddatumDATEONLY, DATEADD(DAY, betalingstermijn, Datum) AS VervalDatumDATEONLY, Week, bedrag + btwbedrag AS FactuurTotaal, 
                         btwbedrag AS FactuurTotaalBTW, admFactuur AS FactuurADZ, ISNULL
                             (( SELECT sum(duur_uren * 60 + duur_minuten) AS Expr1
                                FROM
                                  uren
                                WHERE
                                  (facturen.urenbriefjeid = urenbriefjeid)
                                  AND (rowstatus = '')), 0) AS minuten, ISNULL
                             ((
                                SELECT bedrag - borg AS A
                                FROM
                                  bce_regels
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS contributie, ISNULL
                             ((
                                SELECT borg
                                FROM
                                  bce_regels AS bce_regels_2
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS BorgZZP,
                             (
                                SELECT convert(DATE, datum) AS Expr1
                                FROM
                                  bce_regels AS bce_regels_1
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)) AS contributieDatum, ISNULL
                             ((
                                SELECT sum(Bedrag) AS Expr1
                                FROM
                                  Voorschotten
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS Voorschot, CASE WHEN geen_btw = 0 THEN btwbedrag - (round(ISNULL
                             ((
                                SELECT sum(KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_2
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0), 2) + round(ISNULL
                             ((
                                SELECT sum((bedrag / 100) * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0), 2) + round(ISNULL
                             ((
                                SELECT sum((bedrag / 100) * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_2
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0), 2)) ELSE 0 END AS BtwSaldo, ISNULL
                             ((
                                SELECT sum(Kosten) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_3
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS kostenvoorschot, ISNULL
                             ((
                                SELECT sum(KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_2
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')), 0.0) AS kostenvoorschotbtw,
                             (
                                SELECT max(mutdatum) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_1
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = ' ')) AS Voorschotdatum, soort AS factuursoort, (bedrag + btwbedrag) - (ISNULL
                             ((
                                SELECT sum(Bedrag + Kosten + KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_4
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = '')), 0.0) + ISNULL
                             ((
                                SELECT bedrag - borg AS contributie
                                FROM
                                  bce_regels AS bce_regels_3
                                WHERE
                                  (rowstatus = '')
                                  AND (zzpfactuurnr = facturen.factuurnr)), 0.0) + ISNULL
                             ((
                                SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001', 'ADMKB001', 'ADMKP001'))), 0.0)) AS Resteert, ISNULL
                             ((
                                SELECT sum(Kosten + KostenBTW) AS Expr1
                                FROM
                                  Voorschotten AS Voorschotten_4
                                WHERE
                                  (factuurid = facturen.id)
                                  AND (rowstatus = '')), 0.0) + ISNULL
                             ((
                                SELECT sum(bedrag + bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001', 'ADMKB001', 'ADMKP001'))), 0.0) AS kosten, ISNULL
                             ((
                                SELECT sum(duur_uren * 60 + duur_minuten) / 60 + cast(sum(duur_uren * 60 + duur_minuten) - sum(duur_uren * 60 + duur_minuten) / 60 * 60 AS DECIMAL) / 100 AS Expr1
                                FROM
                                  uren AS uren_1
                                WHERE
                                  (facturen.urenbriefjeid = urenbriefjeid)
                                  AND (rowstatus = '')), 0.0) AS uren, ISNULL
                             ((
                                SELECT sum(bedragverdelen) AS Expr1
                                FROM
                                  Urenbriefje
                                WHERE
                                  (facturen.urenbriefjeid = id)
                                  AND (rowstatus = ' ')), 0) AS verdeelminuten, ISNULL
                             ((
                                SELECT sum(bedrag) AS Expr1
                                FROM
                                  facturen_regels
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS Administratie, ISNULL
                             ((
                                SELECT sum(bedrag) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS Bemiddeling, ISNULL
                             ((
                                SELECT sum(bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_2
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS AdministratieBTW, ISNULL
                             ((
                                SELECT sum(bedrag / 100 * btw) AS Expr1
                                FROM
                                  facturen_regels AS facturen_regels_1
                                WHERE
                                  (vrachtbrief = facturen.factuurnr)
                                  AND (rowstatus = '')
                                  AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS BemiddelingBTW, mutdatum, id AS localid
FROM            facturen "
			. "WHERE WerknemerID = $werknemerid AND soort = 'U' AND rowstatus = '' and jaar = $filter_jaartal  AND (Datum < CONVERT(date, GETDATE()))"
		;

		
		$query = $otherdb->query ( $sql );
		return $query;
	}


	function grid_get_pdf_count2($werknemerid, $filter_jaartal) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		$sql = "SELECT COUNT(*) as aantal FROM facturen WHERE WerknemerID = $werknemerid AND soort = 'U' AND rowstatus = '' and jaar = $filter_jaartal";
		$query = $otherdb->query ( $sql );		
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}


	function get_week_totaal_uren($werknemerid, $filter_jaartal)
	{
		$sql = "SELECT week, sum( FLOOR(uren) ) AS alleenuren, sum(ROUND(((uren - FLOOR(uren)) * 100.00), 2)) as alleenminuten, 
		SUM((FLOOR(uren) * 60) + ROUND(((uren - FLOOR(uren)) * 100.00), 2)) as minuten 
		FROM zzp_facturen 
			WHERE FactuurSoort = 'U' 
			AND WerknemerID=$werknemerid 
			AND boekjaar = $filter_jaartal 
		GROUP BY week 
		ORDER BY week";
		$query = $this->db->query ( $sql );
		return $query;
	}

	function get_uren_totaal_in_minuten($werknemerid, $filter_jaartal)
	{
		$sql = "SELECT SUM((FLOOR(uren) * 60) + ROUND(((uren - FLOOR(uren)) * 100.00), 2)) as minuten FROM zzp_facturen WHERE week <> 0 AND WerknemerID = $werknemerid  and boekjaar = $filter_jaartal ";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$totaalaantalminuten = $rows [0]->minuten;
		return $totaalaantalminuten;
	}
	
	
	function OUD_get_week_totaal_uren2($werknemerid, $filter_jaartal)
	{
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

		$sql =  "SELECT week, SUM(duur_uren * 60 + duur_minuten) AS minuten
                FROM uren
				WHERE (rowstatus = '') AND (jaar = $filter_jaartal) AND (werknemer_id = $werknemerid) AND (factuurnr <> '')
				GROUP BY week
				ORDER BY week";

		$query = $otherdb->query ( $sql );
		return $query;
	}

	function OUD_get_uren_totaal_in_minuten2($werknemerid, $filter_jaartal)
	{
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		$sql =  "SELECT SUM(duur_uren * 60 + duur_minuten) AS minuten
		FROM uren
		WHERE (rowstatus = '') AND (jaar = $filter_jaartal) AND (werknemer_id = $werknemerid) AND (factuurnr <> '')
		GROUP BY werknemer_id";

		$query = $otherdb->query ( $sql );
		$rows = $query->result ();
		$totaalaantalminuten = $rows [0]->minuten;
		return $totaalaantalminuten;
	}
	
	
	function get_week_totaal_uren2($werknemerid, $filter_jaartal)
	{
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

		$sql =  "SELECT week, SUM(duur_uren * 60 + duur_minuten) AS minuten
                FROM uren
                           WHERE (rowstatus = '') AND (jaar = $filter_jaartal) AND (werknemer_id = $werknemerid) AND (factuurnr <> '')
AND
                             ((SELECT        Datum
                                 FROM            facturen
                                 WHERE        (factuurnr = uren.factuurnr) AND (rowstatus = '')) < CONVERT(date, GETDATE()))

                           GROUP BY week
                           ORDER BY week";

		$query = $otherdb->query ( $sql );
		return $query;
	}

	function get_uren_totaal_in_minuten2($werknemerid, $filter_jaartal)
	{
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		$sql =  "SELECT SUM(duur_uren * 60 + duur_minuten) AS minuten
              FROM uren
              WHERE (rowstatus = '') AND (jaar = $filter_jaartal) AND (werknemer_id = $werknemerid) AND (factuurnr <> '')
AND
                             ((SELECT        Datum
                                 FROM            facturen
                                 WHERE        (factuurnr = uren.factuurnr) AND (rowstatus = '')) < CONVERT(date, GETDATE()))

              GROUP BY werknemer_id";

		$query = $otherdb->query ( $sql );
		$rows = $query->result ();
		$totaalaantalminuten = $rows [0]->minuten;
		return $totaalaantalminuten;
	}

	

	function get_pdf_totaal($werknemerid, $filter_jaartal) {
		$sql = "SELECT
			sum(FactuurTotaal) AS SUMFactuurTotaal, 
			sum(FactuurTotaalBTW) AS SUMFactuurTotaalBTW, 
			sum(Voorschot) AS SUMVoorschot, 
			sum(KostenVoorschot) AS SUMKostenVoorschot, 
			sum(KostenVoorschotBTW) AS SUMKostenVoorschotBTW, 
			sum(BorgZZP) AS SUMBorgZZP,
			sum(Bemiddeling) AS SUMBemiddeling, 
			sum(BemiddelingBTW) AS SUMBemiddelingBTW, 
			sum(Administratie) AS SUMAdministratie, 
			sum(AdministratieBTW) AS SUMAdministratieBTW, 
			sum(Contributie) AS SUMContributie, 
			sum(ContributieBTW)  AS SUMContributieBTW,
			sum(Resteert) as SUMResteert,
			sum(FactuurTotaalBTW-(KostenVoorschotBTW+BemiddelingBTW+AdministratieBTW+ContributieBTW)) as totaal_btwsaldo1,
			sum( CASE WHEN FactuurTotaalBTW<>0 THEN FactuurTotaalBTW-(KostenVoorschotBTW+BemiddelingBTW+AdministratieBTW+ContributieBTW) ELSE 0 END) as totaal_btwsaldo,
			sum(Bemiddeling + BemiddelingBTW + Administratie + AdministratieBTW + Contributie + ContributieBTW + BorgZZP + KostenVoorschot + KostenVoorschotBTW) as totaal_kosten
		FROM zzp_facturen WHERE WerknemerID = $werknemerid AND Week <> 0 AND actief = 'A'  and boekjaar = $filter_jaartal  ";
		$query = $this->db->query ( $sql );
		return $query;
	}

	function get_pdf_count($werknemerid, $filter_jaartal) {
		//ANDEREDATABASE
		$sql = "SELECT count(*) as aantal FROM zzp_facturen WHERE WerknemerID = $werknemerid AND Week <> 0 AND actief = 'A' AND boekjaar = $filter_jaartal";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}


	function get_pdf_details2($werknemerid, $factuurnr, $filter_jaartal) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		

		$sql = "
SELECT id, isnull((SELECT bedrag - borg AS A
               FROM
                 bce_regels
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS contributie
     , isnull((SELECT borg
               FROM
                 bce_regels AS bce_regels_2
               WHERE
                 (rowstatus = '')
                 AND (zzpfactuurnr = facturen.factuurnr)), 0.0) AS BorgZZP
     , isnull((SELECT sum(Kosten) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_3
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschot
     , isnull((SELECT sum(KostenBTW) AS Expr1
               FROM
                 Voorschotten AS Voorschotten_2
               WHERE
                 (factuurid = facturen.id)
                 AND (rowstatus = ' ')), 0.0) AS kostenvoorschotbtw
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS Administratie
     , isnull((SELECT sum(bedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS Bemiddeling
     , isnull((SELECT sum(btwbedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_2
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('ADMKB001', 'ADMKP001'))), 0.0) AS AdministratieBTW
     , isnull((SELECT sum(btwbedrag) AS Expr1
               FROM
                 facturen_regels AS facturen_regels_1
               WHERE
                 (vrachtbrief = facturen.factuurnr)
                 AND (rowstatus = '')
                 AND (artnr IN ('BEMKB001', 'BEMKP001'))), 0.0) AS BemiddelingBTW
FROM
facturen
WHERE Factuurnr = '$factuurnr' AND rowstatus = ''";

		//$query = $this->db->query ( $sql );
		$query = $otherdb->query ( $sql );
		return $query;
	}

	function get_pdf_details($werknemerid, $factuurnr, $filter_jaartal) {
		$sql = "SELECT id, WerknemerID, Opdrachtgever, Factuurnr,
			CAST(FactuurDatum AS DATE) AS FactuurDatumDATEONLY,
			CAST(VervalDatum AS DATE) AS VervalDatumDATEONLY,
			CAST(Betaalddatum AS DATE) AS BetaalddatumDATEONLY,
			(Bemiddeling + Administratie + Contributie + BorgZZP + KostenVoorschot) as kosten, 
			(BemiddelingBTW + AdministratieBTW + ContributieBTW + KostenVoorschotBTW) as kostenBTW,			
			Week, FactuurTotaal, FactuurTotaalBTW, Voorschot, KostenVoorschot, KostenVoorschotBTW, BorgZZP,
			FactuurADZ, Bemiddeling, BemiddelingBTW, Administratie, AdministratieBTW, Contributie, ContributieBTW, uren,
			Resteert, Actief
			FROM zzp_facturen " 
			. "WHERE WerknemerID = 33 ";
		//. "WHERE WerknemerID = $werknemerid AND Factuurnr = '$factuurnr' AND Week <> 0 AND actief = 'A' AND boekjaar = $filter_jaartal order by WerknemerID, Factuurnr";
		//TESTLUUK  moet wel where bij uiteraard.
		//ANDEREDATABASE
		$query = $this->db->query ( $sql );
		return $query;
	}

	function get_pdf_blob_filename($werknemerid, $pdf_id, $filter_jaartal) 
	{

		defined ( 'PDFTEMP_PATH' ) || define ( 'PDFTEMP_PATH', realpath ( dirname ( __FILE__ ) . '/../../pdftemp' ) );

		//was
		//$sql = "SELECT Factuurnr FROM zzp_facturen WHERE WerknemerID = $werknemerid AND id= $pdf_id AND actief = 'A' AND boekjaar = $filter_jaartal ";				
		//$query = $this->db->query ( $sql );
		
		/** /
		$otherdb1 = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		$sql = "SELECT Factuurnr FROM facturen WHERE id= $pdf_id ";
		$query = $otherdb1->query ( $sql );
		$rows = $query->result ();
		$factuurnr = $rows [0]->Factuurnr;
		/**/
		
		
		$factuurnr  = $pdf_id;

		//LET OP!, schakelt over naar de andere database!   mag alleen READ ONLY
		//$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		$otherdb = $this->load->database('bcm_adz_bijlages', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		
		$sql = "SELECT TOP (1) id  FROM  archief  WHERE (rowstatus = '') AND (nummer = '$factuurnr') AND (soort = 'factuur')  ORDER BY id DESC";
		$query_bcm = $otherdb->query ( $sql );
		$bcm_rows = $query_bcm->result ();
		$archief_id = $bcm_rows [0]->id;
		
		$sql = "SELECT bestand, bestandsnaam FROM bijlage WHERE (archief_id = $archief_id )";
		$query_bcm = $otherdb->query ( $sql );
		$rows = $query_bcm->result ();
		
		$pdf_content = $rows [0]->bestand;
		//MSSQLTODO nog de folder en bestandnaam
		$guid = $this->create_guid ();
		$tmp_pdf_filename = "pdf_" . "$werknemerid" . "_" . $guid . ".pdf";
		file_put_contents ( PDFTEMP_PATH . "/" . $tmp_pdf_filename, $pdf_content );

		//om de filewrite wat tijd te geven doe ik hier een onzinnige bewerking, alleen maar om de write een commit op de schijf te kunnen laten doen.
		//ik kreeg anders meldingen van corrupte pdf's, mogelijk doordat de write nog niet helemaal klaar was.
		//gevaarlijk bij druk gebruikte systemen, dan kan de write een aardige vertraging oplopen.
		$cnt = $this->grid_get_pdf_count2 ( $werknemerid, $filter_jaartal); //TODOFCL dit moet beter!
		
		//return PDFTEMP_PATH . "/" . $tmp_pdf_filename;
		return $tmp_pdf_filename;
	}

	function get_pdf_blob_filename_adZ($werknemerid, $pdf_factuur_adz, $filter_jaartal) {
		defined ( 'PDFTEMP_PATH' ) || define ( 'PDFTEMP_PATH', realpath ( dirname ( __FILE__ ) . '/../../pdftemp' ) );
		
		//OUD
		//$sql = "SELECT Factuurnr FROM zzp_facturen WHERE Factuurnr = '$pdf_factuur_adz' AND Week = 0 AND actief = 'A' ";
		//$query = $this->db->query ( $sql );
		//$rows = $query->result ();
		
		/** /
		$otherdb1 = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		$sql = "SELECT Factuurnr FROM facturen WHERE Factuurnr = '$pdf_factuur_adz'" ;
		$query = $otherdb1->query ( $sql );
		$factuurnr = $rows [0]->Factuurnr;
		/**/
		
		$factuurnr = $pdf_factuur_adz;
		$otherdb = $this->load->database('bcm_adz_bijlages', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		$sql = "SELECT TOP (1) id  FROM  archief  WHERE (rowstatus = '') AND (nummer = '$factuurnr') AND (soort = 'factuur')  ORDER BY id DESC";
		$query_bcm = $otherdb->query ( $sql );
		$bcm_rows = $query_bcm->result ();
		$archief_id = $bcm_rows [0]->id;
		
		$sql = "SELECT bestand, bestandsnaam FROM bijlage WHERE (archief_id = $archief_id )";
		$query_bcm = $otherdb->query ( $sql );
		$rows = $query_bcm->result ();
		
		$pdf_content = $rows [0]->bestand;
		//MSSQLTODO nog de folder en bestandnaam
		$guid = $this->create_guid ();
		$tmp_pdf_filename = "pdf_" . "$werknemerid" . "_" . $guid . ".pdf";
		file_put_contents ( PDFTEMP_PATH . "/" . $tmp_pdf_filename, $pdf_content );
		
		$cnt = $this->grid_get_pdf_count2 ( $werknemerid, $filter_jaartal);  //TODOFCL dit moet beter!   dit is alleen maar een file write vertraging.

		//return PDFTEMP_PATH . "/" . $tmp_pdf_filename;
		return $tmp_pdf_filename;
	}

	function create_guid() {
		$sql = "SELECT NEWID() as pdf_guid";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$pdf_guid = $rows [0]->pdf_guid;
		return $pdf_guid;
	}
}

//1042 1757