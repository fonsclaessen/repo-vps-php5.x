<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class prices_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	function insert_prices_10($p_deelnemer) {
		//herstel de database voor deze gebruiker tot 10 oplopende records
		$sql = "SELECT * FROM qe_prices WHERE deelnemer = '$p_deelnemer' order by volgnummer";
		$query = $this->db->query ( $sql );
		$vn = array ();
		foreach ( $query->result () as $row ) {
			//in array toevoegen $row->volgnummer
			$vn [] = $row->volgnummer;
		}
		$x = 1;
		for($x == 1; $x <= 10; $x ++) {
			if (in_array ( $x, $vn )) {
				//bestaat, sla over, test: echo "yep";
			} else {
				$this->insert_new_price ( $p_deelnemer, $x );
			}
		}
		return $vn;
	}
	
	
	function update_price_image($p_edit_id, $p_deelnemer, $p_price_image_filename) {
		$sql = "UPDATE qe_prices SET image = '$p_price_image_filename' where id = $p_edit_id and deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		return $query;
	}

	function update_price_collected ( $price_collected, $p_deelnemer ) {
		$sql = "UPDATE qe_winners SET price_afgehaald=1, price_gehaald_at=current_timestamp()  where deelnemer = '$p_deelnemer' and price_code='$price_collected' ";		
		$query = $this->db->query ( $sql );
		return $query;
	}
			
	function update_price_edit($p_edit_id, $p_deelnemer, $p_edit_tekst, $p_edit_omschrijving, $p_edit_trigger_counter, $p_edit_price_actief) {
		$e_edit_tekst = $this->db->escape($p_edit_tekst);
		$e_edit_omschrijving = $this->db->escape($p_edit_omschrijving);
//was		$sql = "UPDATE qe_prices SET tekst = '$p_edit_tekst', omschrijving = '$p_edit_omschrijving', trigger_counter = $p_edit_trigger_counter, actief = '$p_edit_price_actief ' where id = $p_edit_id and deelnemer = '$p_deelnemer'";
		$sql = "UPDATE qe_prices SET tekst = " . $e_edit_tekst .
		", omschrijving = " . $e_edit_omschrijving . 
		", trigger_counter = $p_edit_trigger_counter, actief = '$p_edit_price_actief ' where id = $p_edit_id and deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		return $query;
	}
	function insert_new_price($p_deelnemer, $p_volgnummer) {
		$sql = "INSERT INTO qe_prices (volgnummer, tekst, omschrijving, image, deelnemer) " . " VALUES ($p_volgnummer, 'qr_events', 'qr_events', '../qr-events.jpg', '$p_deelnemer')";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_price_edit($p_edit_id, $p_deelnemer) {
		$sql = "SELECT * FROM qe_prices WHERE deelnemer = '$p_deelnemer' and id = $p_edit_id";
		$query = $this->db->query ( $sql );
		$row = $query->result ();
		return $row [0];
	}
	function get_prices($num, $offset, $p_deelnemer) {
		if ($this->get_prices_count_all ( $p_deelnemer ) < 10) {
			$v = $this->insert_prices_10 ( $p_deelnemer );
		}
		if ($offset == null) {
			$offset = 0;
		}
		$sql = "SELECT * FROM qe_prices WHERE deelnemer = '$p_deelnemer' order by volgnummer LIMIT $offset, $num ";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_prices_all($p_deelnemer) {	
		if ($this->get_prices_count_all ( $p_deelnemer ) < 10) {
			$v = $this->insert_prices_10 ( $p_deelnemer );
		}
		$sql = "SELECT * FROM qe_prices WHERE deelnemer = '$p_deelnemer' order by volgnummer";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_prices_count($p_deelnemer) {	
		$sql = "SELECT count(*) as aantal FROM qe_prices WHERE deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal - 1;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}
	function get_prices_count_all($p_deelnemer) {
		$sql = "SELECT count(*) as aantal FROM qe_prices WHERE deelnemer = '$p_deelnemer' ";		
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal - 1;
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}
	
	function new_winner($p_deelnemer, $p_price_id, $p_price_omschrijving) {
		$sql = "SELECT uuid() as uniek_uuid";
		$query_uuid = $this->db->query ( $sql );
		$rows_uuid = $query_uuid->result ();
		$price_uuid = $rows_uuid [0]->uniek_uuid;
		//$price_md5_hash = md5($price_uuid );  kan wel eens eenzelfde hash geven, dan maar de uuid uitsturen.
		$price_md5_hash = $price_uuid;		
		$e_price_omschrijving = $this->db->escape($p_price_omschrijving);		
//was		$sql = "INSERT INTO qe_winners (deelnemer, price_code, price_id, prijs_omschrijving) VALUES ('$p_deelnemer', '$price_md5_hash', $p_price_id, '$p_price_omschrijving')";
		$sql = "INSERT INTO qe_winners (deelnemer, price_code, price_id, prijs_omschrijving) VALUES ('$p_deelnemer', '$price_md5_hash', $p_price_id, " . $e_price_omschrijving . ")";		
		$query_insert = $this->db->query ( $sql );
		$last_winner_id = $this->db->insert_id ();
		$sql = "SELECT * FROM qe_winners WHERE actief = 'A' and deelnemer = '$p_deelnemer' and id = $last_winner_id";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_price_winner($p_price_uuid, $p_deelnemer) {	
		if (isset ( $_SESSION ['qreventssessionprice'] )) {
			if ($_SESSION ['qreventssessionprice'] != $p_price_uuid) {
				$qdata ['price_actief'] = FALSE; //prijs uuid niet gelijk aan session, dus kan zelf op url ingetik zijn 
				return $qdata;
			}
		} else { //session waarde niet eens gezet, dus mogelijk cache leeg of zelf url ingetikt, geen prijs helaas.
			$qdata ['price_actief'] = FALSE; //prijs uuid niet gelijk aan session, dus kan zelf op url ingetik zijn 
			return $qdata;
		}
		//1800 versus 120 TODO
//mod002		$sql = "SELECT *, (TIME_TO_SEC(TIMEDIFF(created_at, now())) + 120) as tijdverschil FROM qe_winners WHERE actief = 'A' and deelnemer = '$p_deelnemer' and price_code ='$p_price_uuid' ";
		$sql = "SELECT *, (TIME_TO_SEC(TIMEDIFF(created_at, now())) + 900) as tijdverschil FROM qe_winners WHERE actief = 'A' and deelnemer = '$p_deelnemer' and price_code ='$p_price_uuid' ";
		$query = $this->db->query ( $sql );
		$rows_winner = $query->result ();
		if ($query->num_rows () === 0) {
			$qdata ['price_actief'] = FALSE; //prijs niet gevonden, is dus niet actief en kan nooit gegeven worden.
		} else {
			$price_id = $rows_winner [0]->price_id;
			$sql = "SELECT * FROM qe_prices WHERE actief = 'A' and deelnemer = '$p_deelnemer' and id =$price_id ";
			$query_prices = $this->db->query ( $sql );
			$rows_prices = $query_prices->result ();
			if ($query_prices->num_rows () === 0) {
				$qdata ['price_actief'] = FALSE; //surprise is niet meer actief. kan niet uit gegeven worden.
			} else {
				$qdata ['price_actief'] = TRUE;
				$qdata ['price_image'] = $rows_prices [0]->image;
				$qdata ['price_tekst'] = $rows_prices [0]->tekst;
				$qdata ['price_omschrijving'] = $rows_prices [0]->omschrijving;
			}
			$prijs_moment = $rows_winner [0]->created_at;
			$tijd_verschil = $rows_winner [0]->tijdverschil;
			$tijd_verschil_min = floor ( $rows_winner [0]->tijdverschil / 60 );
			$qdata ['minuten'] = $tijd_verschil_min;
			
			if ($tijd_verschil <= 0) {
				$qdata ['tijd_verstreken'] = TRUE;
			} else {
				$qdata ['tijd_verstreken'] = FALSE;
			}
			if ($rows_winner [0]->price_gehaald_at === null) {
				$qdata ['price_gekregen'] = FALSE;
			} else {
				$qdata ['price_gekregen'] = TRUE;
			}
		}
		return $qdata;
	}
	
	function check_price_winner_2($p_deelnemer, $since_last_price_counter, $last_price_index) {
		//ook de NIET actieve, die skippen we.
		$sql = "SELECT * FROM qe_prices WHERE deelnemer = '$p_deelnemer' order by volgnummer";
		$query = $this->db->query ( $sql );
		$qdata ['winner'] = FALSE;
		if ($query->num_rows () === 0) {
			$qdata ['winner'] = FALSE;
		} else {
			$flag_next_row_found = FALSE;
			$flag_price_row_found = FALSE;
			$flag_price_actief = FALSE;
			$ix = $last_price_index + 1; //volgende prijs na de laatste uitgedeelde prijs.
			if ($ix > 10) {
				$ix = 1;
			}
			foreach ( $query->result () as $row ) {
				if (($flag_next_row_found === TRUE) && ($flag_price_actief === FALSE)) {
					// we hebben de row index+1 gevonden, maar het was niet actief
					if ($row->actief == "A") {
						$flag_price_actief = TRUE; //nu we de volgende hebben daarna niet meer testen
						if ($row->trigger_counter == $since_last_price_counter) {
							$flag_price_row_found = TRUE;
							$qdata ['winner'] = TRUE;
							$qdata ['query_row'] = $row;
							break;
						}
					}
				} else {
					if ($row->volgnummer == $ix) {
						$flag_next_row_found = TRUE;
						if ($row->actief == "A") {
							$flag_price_actief = TRUE;
							if ($row->trigger_counter == $since_last_price_counter) {
								$flag_price_row_found = TRUE;
								$qdata ['winner'] = TRUE;
								$qdata ['query_row'] = $row;							
								break;
							}
						}
					}
				}
			}
			//er van uitgaande dat de next row wel in de table staat.
			//er moeten 10 records zijn, anders klopt de table niet en moet gerepareerd.
			//dat doe je door eenmalig de prijzen op te roepen met inlog op deelnemer account.
			if (($flag_next_row_found === TRUE) && ($flag_price_actief === FALSE)) {
				foreach ( $query->result () as $row ) {
					if ($row->actief == "A") {
						$flag_price_actief = TRUE;
						if ($row->trigger_counter == $since_last_price_counter) {
							$flag_price_row_found = TRUE;
							$qdata ['winner'] = TRUE;
							$qdata ['query_row'] = $row;
							break;
						}
					}
				}
			}
		}
		return $qdata;
	}
	
	
	function get_price_max_counter ( $p_deelnemer ) {	
		$sql = "SELECT max(trigger_counter) as maxtrigger FROM qe_prices WHERE actief = 'A' and deelnemer = '$p_deelnemer' ";
		$query = $this->db->query ( $sql );
		$row = $query->result ();
		return $row[0];
	}
	
	function check_price_winner($counter, $p_deelnemer) {	
		$sql = "SELECT * FROM qe_prices WHERE actief = 'A' and deelnemer = '$p_deelnemer' and (($counter % trigger_counter) = 0)";
		$query = $this->db->query ( $sql );
		$qdata ['winner'] = FALSE;
		if ($query->num_rows () === 0) {
			$qdata ['winner'] = FALSE;
		} else {
			$random_index = mt_rand ( 1, $query->num_rows () );
			$ix = 0;
			foreach ( $query->result () as $row ) {
				$ix = $ix + 1;
				$mo = $counter % $row->trigger_counter;
				//				echo "mo: " . $mo . "   "  . $counter . "   " . $row->trigger_counter . "<br/>";
				if ($ix == $random_index) {
					$qdata ['winner'] = TRUE;
					$qdata ['query_row'] = $row;
					break;
				}
			}
		}
		return $qdata;
	}
	
	function check_price_winner1($counter, $p_deelnemer) {
		$sql = "SELECT * FROM qe_prices WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		//$rows = $query->result();
		$qdata ['winner'] = FALSE;
		if ($query->num_rows () === 0) {
			$qdata ['winner'] = FALSE;
		} else {
			foreach ( $query->result () as $row ) {
				$mo = $counter % $row->trigger_counter;
				//echo "mo: " . $mo . "   "  . $counter . "   " . $row->trigger_counter . "<br/>";
				//				if ($counter == $row->trigger_counter)
				if ($mo == 0) {
					$qdata ['winner'] = TRUE;
					$qdata ['query_row'] = $row;
					break;
				}
			}
		}
		return $qdata;
	}

}