<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class texts_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	function get_texts($num, $offset) {
		if ($offset == null) {
			$offset = 0;
		}
		$sql = "SELECT * FROM qe_texts WHERE actief <> 'D' order by deelnemer, id LIMIT $offset, $num ";	
		$query = $this->db->query ( $sql );
		return $query;
	
	}
	
	function update_text($p_id, $p_deelnemer, $p_bezoeker, $p_plaats, $p_quote, $p_actief, $p_moderated) {
		$e_bezoeker = $this->db->escape($p_bezoeker);
		$e_plaats = $this->db->escape($p_plaats );
		$e_quote = $this->db->escape($p_quote);
//		$sql = "UPDATE qe_texts SET deelnemer='$p_deelnemer', naam_bezoeker='$p_bezoeker', woonplaats_bezoeker='$p_plaats', content_text='$p_quote', actief='$p_actief', moderated='$p_moderated' , created_at=current_timestamp() WHERE id = $p_id";
		$sql = "UPDATE qe_texts SET deelnemer='$p_deelnemer'"
		. ", naam_bezoeker=" . $e_bezoeker 
		. ", woonplaats_bezoeker=" . $e_plaats 
		. ", content_text="	. $e_quote 
		. ", actief='$p_actief', moderated='$p_moderated' , created_at=current_timestamp() WHERE id = $p_id";
		
		$query = $this->db->query ( $sql );
		return $query;
	}

	//mod003
	function delete_text($p_id) {
		$sql = "UPDATE qe_texts SET actief='D', created_at=current_timestamp() WHERE id = $p_id";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_texts_count() {
		$sql = "SELECT count(*) as aantal FROM qe_texts WHERE actief <> 'D' ";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
//		$row_count = $rows [0]->aantal - 1;
		$row_count = $rows [0]->aantal; //BELANGRIJK TESTEN!!! TODOFCL
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}
	
	function get_edit_text($p_id) {
		$sql = "SELECT * FROM qe_texts WHERE id = $p_id";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		return $rows [0];
	}
	
	function get_moderate_texts($num, $offset) {
		if ($offset == null) {
			$offset = 0;
		}
		$sql = "SELECT * FROM qe_texts WHERE actief <> 'D' and moderated = 'N' order by deelnemer LIMIT $offset , $num ";
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	function get_moderate_texts_count() {
		$sql = "SELECT count(*) as aantal FROM qe_texts WHERE actief <> 'D' and moderated = 'N' ";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal - 1;
		$row_count = $rows [0]->aantal; //BELANGRIJK TESTEN!! TODOFCL		
		if ($row_count < 1) {
			$row_count = 0;
		}
		return $row_count;
	}

	function insert_new_text($p_deelnemer, $p_bezoeker, $p_stad, $p_tekst, $p_actief, $p_moderated) {
		$e_bezoeker = $this->db->escape($p_bezoeker);
		$e_stad = $this->db->escape($p_stad );
		$e_tekst = $this->db->escape($p_tekst );
		$sql = "INSERT INTO qe_texts (naam_bezoeker, woonplaats_bezoeker, content_text, actief, moderated, deelnemer) VALUES ( " .
		$e_bezoeker . ", " . 
		$e_stad . ", "  .
		$e_tekst . ", " .
		"'$p_actief', '$p_moderated', '$p_deelnemer' )";
		
		$query = $this->db->query ( $sql );
		return $query;
	}
	
	
	function insert_new_text_2($p_deelnemer, $p_bezoeker, $p_stad, $p_tekst, $p_actief, $p_moderated) {
		$e_bezoeker = $this->db->escape($p_bezoeker);
		$e_stad = $this->db->escape($p_stad );
		$e_tekst = $this->db->escape($p_tekst );
		
		
		$sql = "INSERT INTO qe_texts (naam_bezoeker, woonplaats_bezoeker, content_text, actief, moderated, deelnemer) VALUES ( " .
		$e_bezoeker . ", " . 
		$e_stad . ", "  .
		$e_tekst . ", " .
		"'$p_actief', '$p_moderated', '$p_deelnemer' )";

		/*
		$sql_data = array(
				'naam_bezoeker' => $e_bezoeker, 
				'woonplaats_bezoeker' => $e_stad,  
				'content_text' => $e_tekst,
				'actief' => $p_actief,
				'moderated' => $p_moderated, 
				'deelnemer' => $p_deelnemer
		);
		$str = $this->db->insert_string('qe_texts', $sql_data);
		*/
		
		
		$query = $this->db->query ( $sql );
		$last_id = $this->db->insert_id();
		return $last_id;
	}
	
	function random_texts() {
		$sql = "SELECT count(*) as aantal FROM qe_texts WHERE moderated = 'Y' and actief = 'A'";
		$query = $this->db->query ( $sql );
		$rows = $query->result ();
		$row_count = $rows [0]->aantal - 1;
		if ($row_count < 1) {
			//    	echo "rowcount!</br>";
		}
		$random_text_index = mt_rand ( 0, $row_count );
		$sql = "SELECT * FROM qe_texts WHERE moderated = 'Y' and actief = 'A' LIMIT $random_text_index, 1";
		$query_texts = $this->db->query ( $sql );
		$rows = $query_texts->result ();
		$update_id = $rows [0]->id;
		if ($update_id === NULL) {		
		} else {
			$sql = "UPDATE qe_texts set display_count = display_count + 1 WHERE moderated = 'Y' and actief = 'A' and id = $update_id";
			$query = $this->db->query ( $sql );
		}
		return $query_texts;
	}
	
	function get_text_id($p_text_id) {		
		if ($p_text_id==0) {
			$sql = "SELECT count(*) as aantal FROM qe_texts WHERE moderated = 'Y' and actief = 'A'";
			$query = $this->db->query ( $sql );
			$rows = $query->result ();
			$row_count = $rows [0]->aantal - 1;
			if ($row_count < 1) {
				//    	echo "rowcount!</br>";
			}
			$random_text_index = mt_rand ( 0, $row_count );
			$sql = "SELECT * FROM qe_texts WHERE moderated = 'Y' and actief = 'A' LIMIT $random_text_index, 1";
			$query_texts = $this->db->query ( $sql );
			$rows = $query_texts->result ();
			$update_id = $rows [0]->id;
			if ($update_id === NULL) {		
			} else {
				$sql = "UPDATE qe_texts set display_count = display_count + 1 WHERE moderated = 'Y' and actief = 'A' and id = $update_id";
				$query = $this->db->query ( $sql );
			}			
		} else {			
			$sql = "SELECT count(*) as aantal FROM qe_texts";
			$query = $this->db->query ( $sql );
			$rows = $query->result ();
			$row_count = $rows [0]->aantal - 1;
			if ($row_count < 1) {
				//    				echo "rowcount!</br>";
			}
			$sql = "SELECT * FROM qe_texts WHERE id = $p_text_id";
			$query_texts = $this->db->query ( $sql );
			$rows = $query_texts->result ();
			$update_id = $rows [0]->id;
			if ($update_id === NULL) {			
			} else {
				$sql = "UPDATE qe_texts set display_count = display_count + 1 WHERE id = $update_id";
				$query = $this->db->query ( $sql );
			}			
		}		
		return $query_texts;		
	}
}