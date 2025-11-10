<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

require_once ('twitter/twitter.class.php');

class Cms extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'zacl' );
		$this->resource = $this->uri->segment ( 1 ) . '_' . $this->uri->segment ( 2 );
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/cms/index/qr-events' );
		}
		$cms_deelnemer = $this->uri->segment ( 3 );
	}
	
	public function index() { //index page  HOME
		$cms = $this->uri->segment ( 3 );
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		$data ['cms_path'] = $cms;
		$this->load->view ( '../../' . $cms . '/index.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	//Dit is de scan behorende bij map: sailors-inn versie voor aanpassen, nu heet die map Sailors-inn-V1
	public function scan_bewaren_scan() { //qrcode landingpage  via 0/loader.html
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$timestamp = $this->uri->segment ( 5 );
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		
		//mod002
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;

		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
		
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";	
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$data ['deelnemer'] = $cms;
		$data ['qrcode'] = $qrcode;
		$_SESSION ['ok'] = 'ok';
		$data ['timestamp'] = $timestamp;
		$this->load->view ( '../../' . $cms . '/0-loader.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html			
	}

	//Dit is de scan_2 behorende bij sailors-inn-test, nu omgezet naar sailors-inn voor productie....
	//public function scan_2() { //qrcode landingpage  via 0/loader.html	
	public function scan() { //qrcode landingpage  via 0/loader.html
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$timestamp = $this->uri->segment ( 5 );
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		
		//mod002
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;

		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";		
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$data ['deelnemer'] = $cms;
		$data ['qrcode'] = $qrcode;
		$_SESSION ['ok'] = 'ok';
		$data ['timestamp'] = $timestamp;
		$this->load->view ( '../../' . $cms . '/3-0-loader.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html			
	}
	
	public function scan_2() { //qrcode landingpage  via 0/loader.html
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$timestamp = $this->uri->segment ( 5 );
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		
		//mod002
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;

		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";		
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$data ['deelnemer'] = $cms;
		$data ['qrcode'] = $qrcode;
		$_SESSION ['ok'] = 'ok';
		$data ['timestamp'] = $timestamp;
		$this->load->view ( '../../' . $cms . '/3-0-loader.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html			
	}
	
	public function scan_qr() { //qrcode landingpage
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		//todo, alleen als er een verrassing staat
		$data ['refresh_meta'] = '';
		$ses_ok = $_SESSION ['ok']; //indien leeg: scan direct, zonder redirect en zonder knop, session waarde is niet gezet in scan.
		$_SESSION ['ok'] = ''; //bij een refresh is session dan niet gezet.  		
		$data ['ip_qtag'] = $_SERVER ['SERVER_ADDR'];
		$winner = FALSE;
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$data ['deelnemer'] = $cms;
		$price_url_part = "";
		$data ['return_url'] = $this->uri->uri_string;
		$this->load->model ( 'prices_model' );
		$this->load->model ( 'deelnemers_model' );
		$this->load->model ( 'texts_model' );
		$qrcode_compared = $this->deelnemers_model->check_qrcode_deelnemer_exists ( $cms, $qrcode );
		if (($qrcode_compared->num_rows == 0) || ($ses_ok != "ok")) { //als session niet gezet bleek, dan is het een refresh, dus nooit verrassing.	
			$winner = FALSE; //code klopt niet met deelnemer qrcode, geen verrassing dus.
		} else {
			$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			$max_price_counter = $this->prices_model->get_price_max_counter ( $cms );
			if ($price_counter->last_price_counter > $max_price_counter->maxtrigger) { //vangnetje.
				//reset de counter, deze keer geen verrassing, maar gaat wel weer meetellen.
				$reset_result = $this->deelnemers_model->reset_deelnemer_last_counter ( $cms );
				$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			}
			$results_prices = $this->prices_model->check_price_winner_2 ( $cms, $price_counter->last_price_counter, $price_counter->last_price_index );
			if ($results_prices ['winner'] === TRUE) {
				$lst_row = $results_prices ['query_row'];
				$last_index_found = $lst_row->volgnummer;
				$price_counter_update = $this->deelnemers_model->update_price_counters ( $cms, $last_index_found );
				$winner = TRUE;
				$rows_prices = $results_prices ['query_row'];
				$data ['price_image'] = $rows_prices->image;
				$data ['price_tekst'] = $rows_prices->tekst;
				$data ['price_omschrijving'] = $rows_prices->omschrijving;
				$result_new_winner = $this->prices_model->new_winner ( $rows_prices->deelnemer, $rows_prices->id, $rows_prices->omschrijving );
				$rows_new_winner = $result_new_winner->result ();
				$price_url_part = $rows_new_winner [0]->price_code;
				$_SESSION ['qreventssessionprice'] = $price_url_part; //zorg ervoor dat je bij de suprise opvragen checked of session gelijk is aan URL part			
				redirect ( "/cms/winner/$cms/$price_url_part" );
			}
		}
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		$data ['winner'] = $winner;
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";		
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);		
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";
		if ($winner) {
			$data ['qr_event_prijs'] = ""; //mod002
			$data ['refresh_meta'] = '<meta http-equiv="refresh" content="60"></meta>';
		} else {
			$data ['qr_event_prijs'] = "Helaas, geen verrassing...<br/>Scan, Schrijf of probeer het opnieuw. Succes!";
		}
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/1-qrevents-page.html', $data );
	}
	
	public function scan_qr_2() { //qrcode landingpage
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		//todo, alleen als er een verrassing staat
		$data ['refresh_meta'] = '';
		$ses_ok = $_SESSION ['ok']; //indien leeg: scan direct, zonder redirect en zonder knop, session waarde is niet gezet in scan.
		$_SESSION ['ok'] = ''; //bij een refresh is session dan niet gezet.  		
		$data ['ip_qtag'] = $_SERVER ['SERVER_ADDR'];
		$winner = FALSE;
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$data ['deelnemer'] = $cms;
		$price_url_part = "";
		$data ['return_url'] = $this->uri->uri_string;
		$this->load->model ( 'prices_model' );
		$this->load->model ( 'deelnemers_model' );
		$this->load->model ( 'texts_model' );
		$qrcode_compared = $this->deelnemers_model->check_qrcode_deelnemer_exists ( $cms, $qrcode );
		if (($qrcode_compared->num_rows == 0) || ($ses_ok != "ok")) { //als session niet gezet bleek, dan is het een refresh, dus nooit verrassing.	
			$winner = FALSE; //code klopt niet met deelnemer qrcode, geen verrassing dus.
		} else {
			$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			$max_price_counter = $this->prices_model->get_price_max_counter ( $cms );
			if ($price_counter->last_price_counter > $max_price_counter->maxtrigger) { //vangnetje.
				//reset de counter, deze keer geen verrassing, maar gaat wel weer meetellen.
				$reset_result = $this->deelnemers_model->reset_deelnemer_last_counter ( $cms );
				$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			}
			$results_prices = $this->prices_model->check_price_winner_2 ( $cms, $price_counter->last_price_counter, $price_counter->last_price_index );
			if ($results_prices ['winner'] === TRUE) {
				$lst_row = $results_prices ['query_row'];
				$last_index_found = $lst_row->volgnummer;
				$price_counter_update = $this->deelnemers_model->update_price_counters ( $cms, $last_index_found );
				$winner = TRUE;
				$rows_prices = $results_prices ['query_row'];
				$data ['price_image'] = $rows_prices->image;
				$data ['price_tekst'] = $rows_prices->tekst;
				$data ['price_omschrijving'] = $rows_prices->omschrijving;
				$result_new_winner = $this->prices_model->new_winner ( $rows_prices->deelnemer, $rows_prices->id, $rows_prices->omschrijving );
				$rows_new_winner = $result_new_winner->result ();
				$price_url_part = $rows_new_winner [0]->price_code;
				$_SESSION ['qreventssessionprice'] = $price_url_part; //zorg ervoor dat je bij de suprise opvragen checked of session gelijk is aan URL part			
				redirect ( "/cms/winner/$cms/$price_url_part" );
			} else {
				//echo "geen prijs!";
			}
		}
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		$data ['winner'] = $winner;
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);		
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";
		if ($winner) {
			$data ['qr_event_prijs'] = ""; //mod002
			$data ['refresh_meta'] = '<meta http-equiv="refresh" content="60"></meta>';
		} else {
			$data ['qr_event_prijs'] = "Helaas, geen verrassing...<br/>Scan, Schrijf of probeer het opnieuw. Succes!";
		}
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/11-qrevents-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	public function pricecollect() {
		$cms = $this->uri->segment ( 3 );
		$price_collected = $this->uri->segment ( 4 );
		//TODOFCL opslaan : een 1 en een datum, geeft aan dat price is gecollecteerd...
		$this->load->model ( 'prices_model' );
		$results_price_collected = $this->prices_model->update_price_collected ( $price_collected, $cms );
		redirect ( '/cms/winner/' . $cms . '/' . $price_collected );
	}
	
	public function newtext_2() {
		$cms = $this->uri->segment ( 3 );
		$price_code = $this->uri->segment ( 4 );
		$data ['deelnemer'] = $cms;
		$data ['price_code'] = $price_code;
		$bezoeker = $this->input->post ( 'naam' );
		$stad = $this->input->post ( 'stad' );
		$tekst = $this->input->post ( 'tekst' );
		$return_url = $this->input->post ( 'return_url' );
		//TODO beveiligen met session, niet meer dan 1 keer inserten, vanaf verrassing/geen verrassing pagina.
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->insert_new_text_2 ( $cms, $bezoeker, $stad, $tekst, "A", "Y" ); //moderated nu direct op JA.
		$_SESSION ['quote'] = 'ok'; //mod004 	
		$this->publish_twitter ($bezoeker, $tekst );
		redirect ( '/cms/dank_2/' . $cms . '/' . $results_texts );
	}
	
	
	function get_twit_text($p_naam, $p_tekst) {
		$bitly_url = "http://bit.ly/poA7ZP";  //20 tekens...
		$len_bitly = strlen($bitly_url);		
		$naam = trim($p_naam) . " zegt: ";
		$tekst = trim($p_tekst);
		$len_naam = strlen($naam);
		$twittertext =  "&quot;" . substr($tekst, 0, 100) . "..." . "&quot"; 
		return $twittertext;
	}
	
	function publish_twitter($p_naam, $tweet_tekst = "qr-events") {
		try {
		//	$tweet = $this->input->post("qtweet");
		//	$tweet = $tweet . " http://bit.ly/gNVs75";
		$bitly_url = "http://bit.ly/poA7ZP";  //20 tekens...
		$len_bitly = strlen($bitly_url);		
		$naam = trim($p_naam) . " zegt: ";
		$tekst = trim($tweet_tekst);
		$len_naam = strlen($naam);
		$tweet = $naam . substr($tekst, 0, ((130-$len_naam)-$len_bitly)) . "... " . $bitly_url;
		//	$tweet = substr ( $tweet_tekst, 0, 140 );
			
			/*		
		Consumer key
Tt7h55y6opcAETkCvdwjsQ
Consumer secret

K4Q4oP8XA5XpQajk1EXJLnbJg2VUZAPDFEZRBjY

Access Token (oauth_token)

90830420-K7pOCXVoDT7WC0Ql6GfztY6XwcN3J3LXQDdvdngfg

Access Token Secret (oauth_token_secret)

7AGUN0sXq599Efbwm7qdhwS3agUhia3eGN6V2DxMzc4
*/
			/*
Consumer key 	uvWdRRmIxrJHjkJes1dTw
Consumer secret 	Gl9Aritku6PTqrfzk4CRlpWdJhjfCAtJhZVGDjvkg
Request token URL 	https://api.twitter.com/oauth/request_token
Authorize URL 	https://api.twitter.com/oauth/authorize
Access token URL 	https://api.twitter.com/oauth/access_token


Access token 	313187266-uMwdC2fg7q5Xl9rfzRnkILgexDEwZAt8pqhxZlKF
Access token secret 	nRzv5vWW8lkvAvtuADLZ6Q81ywYgWqjTj3x0l3zFQ
Access level 	Read and write
*/
			
			$consumerKey_qrevents = "uvWdRRmIxrJHjkJes1dTw";
			$consumerSecret_qrevents = "Gl9Aritku6PTqrfzk4CRlpWdJhjfCAtJhZVGDjvkg";
			$accessToken_qrevents = "313187266-uMwdC2fg7q5Xl9rfzRnkILgexDEwZAt8pqhxZlKF";
			$accessTokenSecret_qrevents = "nRzv5vWW8lkvAvtuADLZ6Q81ywYgWqjTj3x0l3zFQ";
			
			$twitter = new Twitter ( $consumerKey_qrevents, $consumerSecret_qrevents, $accessToken_qrevents, $accessTokenSecret_qrevents );
			$twitter->send ( $tweet );
		
		} catch ( Exception $e ) {
		}
	
	}
	
	public function dank_2() {
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		$text_id = $this->uri->segment ( 4 );
		$data ['text_id'] = $text_id;
		
		$ses_ok = $_SESSION ['quote']; //mod004
		$_SESSION ['quote'] = ''; //bj refresh niet meer aanwezig, en na binnenkomst via twitter ook niet meer...
		

		if ($ses_ok != "ok") {
			//	redirect("http://qr-events.nl/$cms/index.html"); 						
		}
		
		$this->load->model ( 'texts_model' );
		
		//	$results_texts = $this->texts_model->random_texts ();
		$results_texts = $this->texts_model->get_text_id ( $text_id );
		
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
		
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";		
		//$_SESSION['quote']
		$data ["test"] = "test";
		//na verzenden tekst, nieuwe pagina sturen, daar kunnen ze twitteren, add this...
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/3-dank-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	public function landingspage() {
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		$text_id = $this->uri->segment ( 4 );
		$data ['text_id'] = $text_id;
		
		$ses_ok = $_SESSION ['quote']; //mod004
		$_SESSION ['quote'] = ''; //bj refresh niet meer aanwezig, en na binnenkomst via twitter ook niet meer...
		

		if ($ses_ok != "ok") {
			//	redirect("http://qr-events.nl/$cms/index.html"); 						
		}
		
		$this->load->model ( 'texts_model' );
		
		//	$results_texts = $this->texts_model->random_texts ();
		$results_texts = $this->texts_model->get_text_id ( $text_id );
		
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";		
		//$_SESSION['quote']
		$data ["test"] = "test";
		//na verzenden tekst, nieuwe pagina sturen, daar kunnen ze twitteren, add this...
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/5-landing-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	public function newtext() {
		$cms = $this->uri->segment ( 3 );
		$price_code = $this->uri->segment ( 4 );
		$data ['deelnemer'] = $cms;
		$data ['price_code'] = $price_code;
		$bezoeker = $this->input->post ( 'naam' );
		$stad = $this->input->post ( 'stad' );
		$tekst = $this->input->post ( 'tekst' );
		$return_url = $this->input->post ( 'return_url' );
		//TODO beveiligen met session, niet meer dan 1 keer inserten, vanaf verrassing/geen verrassing pagina.
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->insert_new_text ( $cms, $bezoeker, $stad, $tekst, "A", "N" );
		$_SESSION ['quote'] = 'ok'; //mod004 		
		redirect ( '/cms/dank/' . $cms );
	}
	
	public function dank() {
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		
		$ses_ok = $_SESSION ['quote']; //mod004
		$_SESSION ['quote'] = ''; //bj refresh niet meer aanwezig, en na binnenkomst via twitter ook niet meer...
		

		if ($ses_ok != "ok") {
			//	redirect("http://qr-events.nl/$cms/index.html"); 						
		}
		
		//TODO beveiligen met een session : na eerste keer wissen en dan dus de insert overslaan... 
		//	echo "deelnemer: " . $cms . "<br/>";
		//		echo "bezoeker: " . $bezoeker . "<br/>";
		//	echo "plaats: " . $stad . "<br/>";
		//	echo "tekst: " . $tekst . "<br/>";
		//$this->load->model ( 'texts_model' );
		//$results_texts = $this->texts_model->insert_new_text ( $cms, $bezoeker, $stad, $tekst, "A", "N" );
		$this->load->model ( 'texts_model' );
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";		
		//$_SESSION['quote']
		$data ["test"] = "test";
		//na verzenden tekst, nieuwe pagina sturen, daar kunnen ze twitteren, add this...
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/3-dank-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	public function message() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		$data ['refresh_meta'] = '';
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 ); //mod002
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		$_SESSION ['ok'] = 'ok'; //!! mod002  dit er weer uit
		$data ['timestamp'] = $timestamp;
		$data ['deelnemer'] = $cms;
		$data ['qrcode'] = $qrcode;
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$this->load->view ( '../../' . $cms . '/2-message-page.html', $data );
	}
	
	public function message_2() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		$data ['refresh_meta'] = '';
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 ); //mod002
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		//	$_SESSION['ok'] = 'ok'; //!! mod002  dit er weer uit
		$data ['timestamp'] = $timestamp;
		$data ['deelnemer'] = $cms;
		$data ['qrcode'] = $qrcode;
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$this->load->view ( '../../' . $cms . '/2-message-page.html', $data );
	}
	
	public function winner() { //qrcode landingpage
		$winner = FALSE;
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		$price_code_url = $this->uri->segment ( 4 );
		//todo waarom leeg gemaakt??		$price_url_part = "";		
		$message_type = $this->uri->segment ( 5 ); //1=uw tekst is ontvangen, na goedkeuring wordt deze getoond!
		$data ['return_url'] = $this->uri->uri_string;
		$this->load->model ( 'prices_model' );
		$this->load->model ( 'deelnemers_model' );
		$this->load->model ( 'texts_model' );
		$prijs_reeds_opgehaald = FALSE; //mod001
		$prijs_tijd_verlopen = FALSE;
		if ($price_code_url === FALSE) {
			$winner = FALSE;
		} else {
			//test of code nog geldig is, niet verlopen en niet reeds opgehaald..
			//deze gegevens ophalen als er een code is gegeven, via de code opzoeken
			//in de winner table vind je de id van de prijs.
			//daar deze gegevens ophalen.
			//get_price_winner($price_uuid, $deelnemer);
			$results_winner = $this->prices_model->get_price_winner ( $price_code_url, $cms );
			if ($results_winner ['price_actief'] === TRUE) {
				$winner = TRUE;
				$data ['price_image'] = $cms . "/" . $results_winner ['price_image'];
				$data ['price_tekst'] = $results_winner ['price_tekst'];
				$data ['price_omschrijving'] = $results_winner ['price_omschrijving'];
				if ($results_winner ['minuten'] > 0) {
					$data ['tijd_verlopen'] = "Je hebt ongeveer " . $results_winner ['minuten'] . " minuten om de verrassing in ontvangst te nemen.";
				} else {
					$data ['tijd_verlopen'] = "De tijd om de verrassing in ontvangst te nemen is ongeveer " . abs ( $results_winner ['minuten'] ) . " minuten verstreken!";
				}
				$data ['minuten'] = $results_winner ['minuten'];
				$prijs_reeds_opgehaald = $results_winner ['price_gekregen'];
				$prijs_tijd_verlopen = $results_winner ['tijd_verstreken'];
			} else {
				$winner = FALSE;
				$prijs_reeds_opgehaald = FALSE;
				$prijs_tijd_verlopen = FALSE;
			}
			if ($prijs_reeds_opgehaald === FALSE) {
				$data ['collect_price_button'] = "<button style='color:black; width:333px; height:50px; margin-left:0px; margin-right:15px;' onClick=\"location.href='/index.php/cms/pricecollect/$cms/$price_code_url'\">JA, IK HEB DE VERRASSING ONTVANGEN! &raquo;</button>";
			} else {
				$data ['collect_price_button'] = "";
			}
		}
		$data ['refresh_meta'] = '';
		$data ['price_gekregen'] = $prijs_reeds_opgehaald; //mod001
		$data ['tijd_verstreken'] = $prijs_tijd_verlopen;
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		if ($winner) {
			
			$data ['qr_event_prijs'] = ""; //mod002			
			$data ['refresh_meta'] = '<meta http-equiv="refresh" content="60"></meta>';
			if ($prijs_reeds_opgehaald) {
				$data ['qr_event_prijs'] = "GEFELICITEERD, VEEL PLEZIER MET JE VERRASSING!";
				$data ['refresh_meta'] = "";
			} else {
				if ($prijs_tijd_verlopen) {
					$data ['qr_event_prijs'] = "HELAAS, DE TIJD IS VERSTREKEN!";
					
					$data ['refresh_meta'] = "";
				}
			}
		} else {
			$data ['qr_event_prijs'] = "HELAAS, GEEN VERRASSING...";
		}
		$data ['winner'] = $winner;
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = $this->get_twit_text($naam_bezoeker, $rows_texts [0]->content_text);
		
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/1-qrevents-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	
	function avfooter() {
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		$this->load->view ( '../../' . $cms . '/4-av-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html	
	}
	public function scan1() { //qrcode landingpage  via 0/loader.html
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$timestamp = $this->uri->segment ( 5 );
		$timestamp_raw = "qrevents" . ( string ) mt_rand ( 11111111, 99999999 );
		$timestamp = md5 ( $timestamp_raw );
		$data ['deelnemer_qrcode'] = $cms . "/" . $qrcode; //TODO  eventueel unieke timestamp!
		$_SESSION ['ok'] = 'ok';
		$data ['timestamp'] = $timestamp;
		$this->load->view ( '../../' . $cms . '/0-loader.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html			
	}
	
	public function scan_qr1() { //qrcode landingpage
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		//todo, alleen als er een verrassing staat
		$data ['refresh_meta'] = '';
		$ses_ok = $_SESSION ['ok']; //indien leeg: scan direct, zonder redirect en zonder knop, session waarde is niet gezet in scan.
		$_SESSION ['ok'] = ''; //bij een refresh is session dan niet gezet.  		
		$data ['ip_qtag'] = $_SERVER ['SERVER_ADDR'];
		$winner = FALSE;
		$cms = $this->uri->segment ( 3 );
		$qrcode = $this->uri->segment ( 4 );
		$data ['deelnemer'] = $cms;
		$price_url_part = "";
		$data ['return_url'] = $this->uri->uri_string;
		$this->load->model ( 'prices_model' );
		$this->load->model ( 'deelnemers_model' );
		$this->load->model ( 'texts_model' );
		$qrcode_compared = $this->deelnemers_model->check_qrcode_deelnemer_exists ( $cms, $qrcode );
		if (($qrcode_compared->num_rows == 0) || ($ses_ok != "ok")) { //als session niet gezet bleek, dan is het een refresh, dus nooit verrassing.	
			$winner = FALSE; //code klopt niet met deelnemer qrcode, geen verrassing dus.
		} else {
			$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			$max_price_counter = $this->prices_model->get_price_max_counter ( $cms );
			if ($price_counter->last_price_counter > $max_price_counter->maxtrigger) { //vangnetje.
				//reset de counter, deze keer geen verrassing, maar gaat wel weer meetellen.
				$reset_result = $this->deelnemers_model->reset_deelnemer_last_counter ( $cms );
				$price_counter = $this->deelnemers_model->get_price_counter_2 ( $cms );
			}
			$results_prices = $this->prices_model->check_price_winner_2 ( $cms, $price_counter->last_price_counter, $price_counter->last_price_index );
			if ($results_prices ['winner'] === TRUE) {
				$lst_row = $results_prices ['query_row'];
				$last_index_found = $lst_row->volgnummer;
				$price_counter_update = $this->deelnemers_model->update_price_counters ( $cms, $last_index_found );
				$winner = TRUE;
				$rows_prices = $results_prices ['query_row'];
				$data ['price_image'] = $rows_prices->image;
				$data ['price_tekst'] = $rows_prices->tekst;
				$data ['price_omschrijving'] = $rows_prices->omschrijving;
				$result_new_winner = $this->prices_model->new_winner ( $rows_prices->deelnemer, $rows_prices->id, $rows_prices->omschrijving );
				$rows_new_winner = $result_new_winner->result ();
				$price_url_part = $rows_new_winner [0]->price_code;
				$_SESSION ['qreventssessionprice'] = $price_url_part; //zorg ervoor dat je bij de verrassing opvragen checked of session gelijk is aan URL part			
				redirect ( "/cms/winner/$cms/$price_url_part" );
			}
		}
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		$data ['winner'] = $winner;
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = "&quot;" . substr($rows_texts [0]->content_text, 0, 138) . "&quot;";
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";
		if ($winner) {			
			$data ['refresh_meta'] = '<meta http-equiv="refresh" content="60"></meta>';
		} else {
			$data ['qr_event_prijs'] = "Helaas, geen prijs...";
		}
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/1-qrevents-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}
	public function winner1() { //qrcode landingpage
		$winner = FALSE;
		$cms = $this->uri->segment ( 3 );
		$data ['deelnemer'] = $cms;
		$price_code_url = $this->uri->segment ( 4 );
		//todo waarom leeg gemaakt??		$price_url_part = "";		
		$message_type = $this->uri->segment ( 5 ); //1=uw tekst is ontvangen, na goedkeuring wordt deze getoond!
		$data ['return_url'] = $this->uri->uri_string;
		$this->load->model ( 'prices_model' );
		$this->load->model ( 'deelnemers_model' );
		$this->load->model ( 'texts_model' );
		$prijs_reeds_opgehaald = FALSE; //mod001
		$prijs_tijd_verlopen = FALSE;
		if ($price_code_url === FALSE) {
			$winner = FALSE;
		} else {
			//test of code nog geldig is, niet verlopen en niet reeds opgehaald..
			//deze gegevens ophalen als er een code is gegeven, via de code opzoeken
			//in de winner table vind je de id van de prijs.
			//daar deze gegevens ophalen.
			//get_price_winner($price_uuid, $deelnemer);
			$results_winner = $this->prices_model->get_price_winner ( $price_code_url, $cms );
			if ($results_winner ['price_actief'] === TRUE) {
				$winner = TRUE;
				$data ['price_image'] = $cms . "/" . $results_winner ['price_image'];
				$data ['price_tekst'] = $results_winner ['price_tekst'];
				$data ['price_omschrijving'] = $results_winner ['price_omschrijving'];
				if ($results_winner ['minuten'] > 0) {
					$data ['tijd_verlopen'] = "Je hebt ongeveer " . $results_winner ['minuten'] . " minuten om de verrassing in ontvangst te nemen.";
				} else {
					$data ['tijd_verlopen'] = "De tijd om de verrassing op in ontvangst te nemen is ongeveer " . abs ( $results_winner ['minuten'] ) . " minuten verstreken!";
				}
				$data ['minuten'] = $results_winner ['minuten'];
				$prijs_reeds_opgehaald = $results_winner ['price_gekregen'];
				$prijs_tijd_verlopen = $results_winner ['tijd_verstreken'];
			} else {
				$winner = FALSE;
				$prijs_reeds_opgehaald = FALSE;
				$prijs_tijd_verlopen = FALSE;
			}
			if ($prijs_reeds_opgehaald === FALSE) {
				$data ['collect_price_button'] = "<button style='color:black; width:333px; height:50px; margin-left:0px; margin-right:15px;' onClick=\"location.href='/index.php/cms/pricecollect/$cms/$price_code_url'\">JA, IK HEB DE PRIJS ONTVANGEN!</button>";
			} else {
				$data ['collect_price_button'] = "";
			}
		}
		$data ['refresh_meta'] = '';
		$data ['price_gekregen'] = $prijs_reeds_opgehaald; //mod001
		$data ['tijd_verstreken'] = $prijs_tijd_verlopen;
		if ($cms === FALSE) { //indien geen gegeven, dan de default...
			$cms = "qr-events";
		}
		if ($winner) {
			$data ['qr_event_prijs'] = "JE HEBT EEN VERRASSING!";
			$data ['refresh_meta'] = '<meta http-equiv="refresh" content="60"></meta>';
			if ($prijs_reeds_opgehaald) {
				$data ['qr_event_prijs'] = "GEFELICITEERD, VEEL PLEZIER MET JE VERRASSING!";
				$data ['refresh_meta'] = "";
			} else {
				if ($prijs_tijd_verlopen) {
					$data ['qr_event_prijs'] = "HELAAS, DE TIJD IS VERSTREKEN!";
					
					$data ['refresh_meta'] = "";
				}
			}
		} else {
			$data ['qr_event_prijs'] = "HELAAS, GEEN VERRASSING...";
		}
		$data ['winner'] = $winner;
		$results_texts = $this->texts_model->random_texts ();
		$rows_texts = $results_texts->result ();
		$data ['cms_path'] = $cms;
		$naam_bezoeker = $rows_texts [0]->naam_bezoeker;
		$woonplaats_bezoeker = $rows_texts [0]->woonplaats_bezoeker;
		
		$data ['content_text'] = "&quot;" . $rows_texts [0]->content_text . "&quot;";
		$data ['content_twitter'] = "&quot;" . substr($rows_texts [0]->content_text, 0, 138) . "&quot;";
				
		$data ['naam_plaats'] = $naam_bezoeker . " uit " . $woonplaats_bezoeker . " ";
		header ( "Cache-control: private" );
		header ( "Last-Modified: " . gmdate ( "D, j M Y H:i:s" ) . " GMT" ); // Date in the past 
		header ( "Expires: " . gmdate ( "D, j M Y H:i:s", time () ) . " GMT" ); // always modified 
		header ( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1 
		header ( "Cache-Control: post-check=0, pre-check=0", FALSE );
		header ( "Pragma: no-cache" );
		$this->load->view ( '../../' . $cms . '/1-qrevents-page.html', $data ); //komt dan in de root uit dus http://qe-events.nl//sailors-inn/index.html		
	}

}

/* End of file */