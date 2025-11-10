<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class deelnemers_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	function get_deelnemers($num, $offset) {
		$query = $this->db->get ( 'qe_deelnemers', $num, $offset );
		return $query;
	}
	
	function check_qrcode_deelnemer_exists($p_deelnemer, $p_qrcode) {	
		$sql = "SELECT * from qe_deelnemers WHERE deelnemer = '$p_deelnemer' and qrcode = '$p_qrcode' ";
		$query = $this->db->query ( $sql );
		return $query;
	}
	function get_price_counter($p_deelnemer) {
		$sql = "UPDATE qe_deelnemers set price_counter = price_counter + 1 WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query_update = $this->db->query ( $sql );
		//indien nu iemand tegelijk scanned kan het voorkomen dat beiden geen prijs hebben.
		//voorbeeld je begint bij 10 en bij 11 is er een prijs.
		//beiden hogen de waarde met 1 op.
		//daarna lezen ze, beiden kunnen dan 12 lezen en hebben dan geen prijs.
		//zou zeer toevallig zijn, maar is altijd nog beter dan 2 tegelijk prijs.
		//bij 12 lezen duurt het gewoon alleen wat langer voordat er weer prijs is.
		$sql = "SELECT * FROM qe_deelnemers WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		if ($query->num_rows () == 0) {
			$count = - 1;
		} else {
			$count = $rows [0]->price_counter;
		}
		return $count;
	}
	
	function reset_deelnemer_last_counter($p_deelnemer) {	
		$sql = "UPDATE qe_deelnemers set last_price_counter = 0 WHERE deelnemer = '$p_deelnemer' ";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_price_counter_2($p_deelnemer) {
		$sql = "UPDATE qe_deelnemers set price_counter = price_counter + 1, last_price_counter = last_price_counter + 1 WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query_update = $this->db->query ( $sql );
		//indien nu iemand tegelijk scanned kan het voorkomen dat beiden geen prijs hebben.
		//voorbeeld je begint bij 10 en bij 11 is er een prijs.
		//beiden hogen de waarde met 1 op.
		//daarna lezen ze, beiden kunnen dan 12 lezen en hebben dan geen prijs.
		//zou zeer toevallig zijn, maar is altijd nog beter dan 2 tegelijk prijs.
		//bij 12 lezen duurt het gewoon alleen wat langer voordat er weer prijs is.
		$sql = "SELECT * FROM qe_deelnemers WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		if ($query->num_rows () == 0) {
			return null;
		} else {
			return $rows [0];
		}
	}
	
	function update_price_counters($p_deelnemer, $p_lastindex) {	
		$sql = "UPDATE qe_deelnemers set last_price_counter = 0, last_price_index = $p_lastindex WHERE actief = 'A' and deelnemer = '$p_deelnemer'";
		$query_update = $this->db->query ( $sql );
	}

}