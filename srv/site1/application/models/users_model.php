<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class users_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}

	function get_users_oud($num, $offset) {
		$query = $this->db->get ( 'zzp_users', $num, $offset );
		return $query;
	}

	/* niet meer gebruikt? */ 
	function get_users($num, $offset) {
		//MSSQLTODO
		$query = "SELECT * FROM zzp_users WHERE rowstatus = '' LIMIT $offser, $num ";
		return $query;
	}

	//niet meer voorlopig
	function update_password ( $gebruikerid, $pw1, $soortdeelnemer) {
		$md5_pw = md5 ( $pw1 );
		//		. " FROM pXersoneel "
		
		$sql = "UPDATE zzp_users set pw_decrypted = '$pw1',  password = '$md5_pw', formervisit_at = getdate() WHERE GebruikerID = $gebruikerid AND rowstatus = '' AND deelnemer='$soortdeelnemer' ";		
		
		$query = $this->db->query ( $sql );
		return $query;
	}

	//niet meer voorlopig
	function update_formervisit($gebruikerid, $soortdeelnemer) {
		$sql = "UPDATE zzp_users set formervisit_at = getdate() WHERE GebruikerID = $gebruikerid AND rowstatus = '' AND deelnemer='$soortdeelnemer' ";  //MOD001
		$query = $this->db->query ( $sql );
		return $query;
	}


	/* niet meer gebruikt */
	function get_btw_welofniet($id) 
	{
		//MSSQLTODo
		$sql = "SELECT * FROM zzp_users WHERE GebruikerID =  $id AND rowstatus = '' ";
		$query = $this->db->query ( $sql );
		$btw_flag = false;
		if ($query->num_rows() > 0) 
		{
			$rows = $query->result ();
			$btw = $rows [0]->btw;
			if ($btw == 1) {
				$btw_flag = true;
			}
		}
		return $btw_flag;
	}


	function OUD_check_user_exist_id($id, $soortdeelnemer) {
		$sql = "SELECT * FROM zzp_users WHERE GebruikerID =  $id AND rowstatus = '' AND deelnemer='$soortdeelnemer' ";
		$query = $this->db->query ( $sql );
		return $query;
	}

	function OUD_check_user_exist_parameter($guid) {
		$sql = "SELECT * FROM zzp_users WHERE guid = '$guid' AND rowstatus = '' ";
		$query = $this->db->query ( $sql );
		return $query;
	}

	function OUD_getAdmninUserById($id) {
		$sql = "SELECT * FROM zzp_users WHERE GebruikerID =  $id AND rowstatus = ''";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$company = $rows [0]->company_name;
		return $company;
	}


	function wachtwoord_vergeten_email($p_name)
	{
		if ($p_name == '') 
		{
			$p_name = 'neebestaatniet_echtniet';
		}
		$sql = "SELECT * FROM zzp_users where username = '$p_name'";
		$query = $this->db->query ( $sql );
		$email="info@dordtonline.nl";
		if ($query->num_rows() > 0) 
		{
			$rows = $query->result ();
			$email = $rows [0]->email;
			$ww = $rows [0]->pw_decrypted;
			
			$this->load->library('email');

			$config['protocol'] = 'smtp';
			$config['wordwrap'] = TRUE;
/*  zie config			
			$config['smtp_host'] = 'mail.circum.nl';
			$config['smtp_port'] = 26;
			$config['smtp_user'] = 'test@circum.nl';
			$config['smtp_pass'] = 'nite01';
			$config['mailtype'] = 'html';
*/			
			
			$this->email->initialize($config);

			$this->email->from('noreply@brightersoftware.nl', 'Brightersoftware');
			$this->email->to($email); 
			$this->email->bcc('fons@brightersoftware.nl'); 
			$this->email->subject('CoopInfo Wachtwoord informatie.');
			$this->email->message("<p>Uw wachtwoord is: <b>$ww</b></p>");	
			$this->email->send();		
			return true;
		}
		else 
		{
			return false;
		}
	}

	function OUD_check_user_exist($name, $pw) {
		//MSSQLTODo
		$md5_pw = md5 ( $pw );
		$sql = "SELECT * FROM zzp_users WHERE username = '$name' and pw_decrypted = '$pw' AND rowstatus = '' ";
		$query = $this->db->query ( $sql );
		return $query;
	}


	function insert_login_record($gebruikerid, $soortdeelnemer) {
		//MSSQLTODo
		/*
				require_once '/Mobile_Detect.php';
				$detect = new Mobile_Detect;
				$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');	
		*/

		$soort_device = "iphone";
		$soort_os = "ios";
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$applicatie = "zzp-coopinfo";

		$sql = "INSERT INTO zzp_users_log (user_id, deelnemer, http_user_agent, soort_device, soort_os, applicatie) VALUES ($gebruikerid, '$soortdeelnemer', '$agent', '$soort_device', '$soort_os', '$applicatie')";		
		$query = $this->db->query ( $sql );
		return $query;
	}


	function get_users_list($p_deelnemer) {
		//MSSQLTODo
		$sql = "SELECT deelnemer FROM zzp_users WHERE rowstatus = '' ORDER BY deelnemer";
		$query = $this->db->query ( $sql );
		$cb = "<select name='deelnemer_selected' id='deelnemer_selected'>";
		foreach ($query->result() as $row) {
			if ($p_deelnemer == $row->deelnemer) {
				$cb .= "<option value='$row->deelnemer' selected='selected'>$row->deelnemer</option>";				
			} else {
				$cb .= "<option value='$row->deelnemer'>$row->deelnemer</option>";				
			}
		}
		$cb .= "</select>"; 		
		return $cb;
	}

	function OUD_get_users_count($p_searchfor) {
		//MSSQLTODo
		if ($p_searchfor == "") {
			$sql = "SELECT count(*) as aantal FROM zzp_users WHERE role <> 'administrator' AND deelnemer='zzp' AND rowstatus = '' ";				
		} else {
			$sql = "SELECT count(*) as aantal FROM zzp_users WHERE role <> 'administrator' AND deelnemer='zzp' AND rowstatus = '' AND (" 
				. "first_name LIKE '%$p_searchfor%' "
				. "or last_name LIKE '%$p_searchfor%' "
				. "or company_name LIKE '%$p_searchfor%' "
				. "or email LIKE '%$p_searchfor%' "
				. "or username LIKE '%$p_searchfor%' "
				. ") "; 
		}
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}

	function OUD_get_zzp_er_list($num, $offset, $werknemerid, $p_searchfor) {
		//MSSQLTODo
		if ($offset == null) {
			$offset = 0;
		}	
		$eraf = 0;
		if ($p_searchfor == "") {
			//geen zoek veld opgegeven, dan alles tonen
			$sql = "SELECT * FROM zzp_users WHERE role <> 'administrator' AND deelnemer='zzp' AND rowstatus = '' order by company_name " 
				. " OFFSET ($offset - $eraf) ROWS "
				. " FETCH NEXT $num ROWS ONLY"
			; 
			
		} else {
			$sql = "SELECT * FROM zzp_users WHERE role <> 'administrator' AND deelnemer='zzp' AND rowstatus = '' AND (" 
				. "first_name like '%$p_searchfor%' "
				. "or last_name like '%$p_searchfor%' "
				. "or company_name like '%$p_searchfor%' "
				. "or email like '%$p_searchfor%' "
				. "or username like '%$p_searchfor%' "
				. ") order by company_name "
				. " OFFSET ($offset - $eraf) ROWS "
				. " FETCH NEXT $num ROWS ONLY"
			; 

		}
		print_r($sql);
		$query = $this->db->query ( $sql );
		return $query;
	}


	function get_zzp_er_list($num, $offset, $sidx, $sord, $p_searchfor ) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE parameter tells CI that you'd like to return the database object.
		if ($offset == null) {
			$offset = 0;
		}	
		$eraf = 0;
		if ($p_searchfor == "") {

			$sql = "SELECT id,"
				."bedrijfsnaam as company_name, "
				. "naam as last_name, "
				. "emailadres as email, "
				. "inlognaam as username, "
				. "id as GebruikerID "
				. " FROM personeel WHERE  rowstatus = '' "
				. " ORDER BY bedrijfsnaam "
				. " OFFSET ($offset - $eraf) ROWS "
				. " FETCH NEXT $num ROWS ONLY"
			;	
		} else {
			$sql = "SELECT id, "
				."bedrijfsnaam as company_name, "
				. "naam as last_name, "
				. "emailadres as email, "
				. "inlognaam as username, "
				. "id as GebruikerID "
				. " FROM personeel WHERE  rowstatus = '' AND "
				. " (naam LIKE '%$p_searchfor%' OR  BedrijfsNaam LIKE '%$p_searchfor%' OR  emailadres LIKE '%$p_searchfor%' OR  inlognaam LIKE '%$p_searchfor%') "
				. " ORDER BY bedrijfsnaam "
				. " OFFSET ($offset - $eraf) ROWS "
				. " FETCH NEXT $num ROWS ONLY"
			;
		}		
		$query = $otherdb->query ( $sql );
		return $query;
	}

	function check_user_exist_id($id, $soortdeelnemer) {
		$sql = "SELECT * FROM zzp_users WHERE GebruikerID =  $id AND rowstatus = '' AND deelnemer='$soortdeelnemer' ";
		$query = $this->db->query ( $sql );
		return $query;
	}

	//HIERUSER
	function check_user_exist($name, $pw) 
	{  
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
		if (strtolower($name) == 'administrator') {
			$sql = "SELECT Factoring, FouteInlog, BedrijfsNaam AS company_name, id as GebruikerID, CASE WHEN inlognaam = 'administrator' THEN 'adm' ELSE 'zzp' END as deelnemer, CASE WHEN inlognaam = 'administrator' THEN 'administrator' ELSE 'user' END as role "
				. " FROM personeel "
				. " WHERE (inlognaam = '$name') AND (wachtwoord = '$pw')";
		} else {
			$sql = "SELECT Factoring, FouteInlog, BedrijfsNaam AS company_name, id as GebruikerID, CASE WHEN inlognaam = 'administrator' THEN 'adm' ELSE 'zzp' END as deelnemer, CASE WHEN inlognaam = 'administrator' THEN 'administrator' ELSE 'user' END as role  "
				. " FROM personeel "
				. " WHERE (inlognaam ='$name') AND (wachtwoord = '$pw') AND (rowstatus = '') AND (InternetToegangBlokkeren = 0)";
		}
		$query = $otherdb->query ( $sql );
		
		if ($query->num_rows === 1) {
			$row = $query->result ();
			foreach ( $query->result () as $row ) { //het is er maar 1!
				$personeel_id =  $row->GebruikerID; //was id;
				$aantalkeren = $row->FouteInlog;
				if ($aantalkeren < 15) {
					$sql = "UPDATE personeel set FouteInlog = 0 WHERE id = $personeel_id ";
					$queryUpdate = $otherdb->query ( $sql ); 
				} else {
					return null;
				}
				break;
			}

		}
		return $query;
	}
	function sqlsecape($text) {
		$otherdb = $this->load->database('bcm_adz', TRUE);
	//	print_r($otherdb);
		//print_r($otherdb->db);
	//	print_r($otherdb["conn_id"]);
	//print_r($otherdb->conn_id);
//echo $otherdb["conn_id"];
		//return strip_tags(mysql_real_escape_string(trim($text), $this->conn_id));
		//return strip_tags(mysqli_real_escape_string(otherdb->db->link , trim($text)));
	//	return mysql_real_escape_string(trim($text));
		return trim($text);
	}
	
	//hieroo
	function check_user_exist_fout_wachtwoord ( $name, $pw) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.		
	
		$sql = "SELECT FouteInlog, id as GebruikerID "
				. " FROM personeel "
				. " WHERE (inlognaam ='$name') AND (rowstatus = '') AND (InternetToegangBlokkeren = 0)";
 		$query = $otherdb->query ( $sql );
		$results = $query ;
		$personeel_id = -1; 
        $aantalkeren = 0;
	
		if ($results->num_rows === 1) {
			$row = $results->result ();
			foreach ( $results->result () as $row ) { //het is er maar 1!
				$personeel_id =  $row->GebruikerID; //was id;
				$aantalkeren = $row->FouteInlog + 1;
				break;
			}
		}
		//echo "<br>" . $aantalkeren . "   " . $personeel_id . "   " . $name . "    "   . $pw;
		if ($personeel_id <> -1) {
			$sql = "UPDATE personeel set FouteInlog = $aantalkeren WHERE id = $personeel_id ";	
			$query = $otherdb->query ( $sql );  
 			$sql = "INSERT INTO Inlogfouten (datum, inlognaam, wachtwoord, userid) VALUES (getdate(), '$name', '$pw', $personeel_id)";		
			$query = $otherdb->query ( $sql );
		}
		return $aantalkeren;
		
	}
	
	function get_users_count($p_searchfor) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE parameter tells CI that you'd like to return the database object.
		if ($p_searchfor == "") {
			$sql = "SELECT count(*) as aantal FROM personeel WHERE rowstatus = '' "
			;	
		} else {
			$sql = "SELECT count(*) as aantal FROM personeel WHERE  rowstatus = '' AND "
				. " (naam LIKE '%$p_searchfor%' OR  BedrijfsNaam LIKE '%$p_searchfor%' OR  emailadres LIKE '%$p_searchfor%' OR  inlognaam LIKE '%$p_searchfor%') "
			;
		}		
		$query = $otherdb->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}

	function getAdmninUserById($id) {
		$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE parameter tells CI that you'd like to return the database object.
		$sql = "SELECT Bedrijfsnaam as company_name FROM personeel WHERE id =  $id AND rowstatus = '' ";
		$query = $otherdb->query ( $sql );
		$rows = $query->result ();
		$company = $rows [0]->company_name;
		return $company;
	}

	
	//loginppp
	function check_user_exist_parameter($guid) {
	$otherdb = $this->load->database('bcm_adz', TRUE); // the TRUE parameter tells CI that you'd like to return the database object.
	
	$sql = "SELECT Factoring, inlognaam as username, wachtwoord as pw_decrypted, BedrijfsNaam AS company_name, id as GebruikerID, CASE WHEN inlognaam = 'administrator' THEN 'adm' ELSE 'zzp' END as deelnemer, CASE WHEN inlognaam = 'administrator' THEN 'administrator' ELSE 'user' END as role  "
		. " FROM personeel "
		. " WHERE guid = '$guid' "
	;
	$query = $otherdb->query ( $sql );
	$rows = $query->result ();
	$gebruikernaam = $rows [0]->username;
		if ($gebruikernaam != 'administrator') {  //Dan nogmaals testen maar dan ook op rowstatus en internettoegang, want het is een normale gebruiker.
			$sql = "SELECT Factoring, inlognaam as username, wachtwoord as pw_decrypted, BedrijfsNaam AS company_name, id as GebruikerID, CASE WHEN inlognaam = 'administrator' THEN 'adm' ELSE 'zzp' END as deelnemer, CASE WHEN inlognaam = 'administrator' THEN 'administrator' ELSE 'user' END as role  "
				. " FROM personeel "
				. " WHERE guid = '$guid' AND rowstatus = '' AND (InternetToegangBlokkeren = 0)"
			;
			$query = $otherdb->query ( $sql );
		}
		return $query;		
	}


	
	
}