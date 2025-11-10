<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Texts extends CI_Controller {
	
	public $resource = "";

	function __construct() {
		parent::__construct ();
		$this->load->library ( 'zacl' );
		$this->resource = $this->uri->segment ( 1 ) . '_' . $this->uri->segment ( 2 );
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/index' );
		}
	}
	
	function save() {
		$moderated_list = $this->uri->segment ( 3 );		
		$page_num = $this->input->post ( 'text_page_hidden' );
		$text_id = $this->input->post ( 'text_id_hidden' );
		$text_deelnemer = $this->input->post ( 'texts_deelnemer' );		
		$text_bezoeker = $this->input->post ( 'texts_bezoeker' );
		$text_plaats = $this->input->post ( 'texts_plaats' );
		$text_quote = $this->input->post ( 'quote_text' );
		$text_moderated = $this->input->post ( 'texts_moderated' );
		$text_actief = $this->input->post ( 'texts_actief' );
		if (strtolower ( $text_moderated ) == "on") {
			$pmoderated = "Y";
		} else {
			$pmoderated = "N";
		}
		if (strtolower ( $text_actief ) == "on") {
			$pactief = "A";
		} else {
			$pactief = "N";
		}
		$this->load->model ( 'texts_model' );
		$results = $this->texts_model->update_text ( $text_id, $text_deelnemer, $text_bezoeker, $text_plaats, $text_quote, $pactief, $pmoderated );
		if ($moderated_list ===FALSE){
			redirect ( '/texts/index/' . $page_num );
		}	else {
			redirect ( '/texts/moderate/' . $page_num );
		}		
	}

	function savenew() {	
		$deelnemer = $_SESSION ['deelnemer'];		
		$page_num = $this->input->post ( 'text_page_hidden' );
		$text_bezoeker = $this->input->post ( 'texts_bezoeker' );
		$text_plaats = $this->input->post ( 'texts_plaats' );
		$text_quote = $this->input->post ( 'quote_text' );
		$text_moderated = $this->input->post ( 'texts_moderated' );
		$text_actief = $this->input->post ( 'texts_actief' );
		$text_selected_deelnemer = $this->input->post ( 'deelnemer_selected' );
		if ($text_selected_deelnemer == "") {
			$text_selected_deelnemer = $deelnemer; 
		} 		
		if (strtolower ( $text_moderated ) == "on") {
			$pmoderated = "Y";
		} else {
			$pmoderated = "N";
		}
		if (strtolower ( $text_actief ) == "on") {
			$pactief = "A";
		} else {
			$pactief = "N";
		}
		
		$this->load->model ( 'texts_model' );
		$results = $this->texts_model->insert_new_text ($text_selected_deelnemer, $text_bezoeker, $text_plaats, $text_quote, $pactief, $pmoderated);		

		redirect ( '/texts/index/' . $page_num );
	
	}
	
	
	function index() {
		$page_num = $this->uri->segment ( 3 );
		if ($page_num == null) {
			$page_num = 0;
		}
		$moderated_list = $this->uri->segment ( 4 );
		$deelnemer = $_SESSION ['deelnemer'];
		$data ["moderated"] = FALSE;  //moderated_list?? raar		
		$data ["page_number"] = $page_num;
		$this->load->model ( 'texts_model' );
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'index.php/texts/index/';
		$config ['total_rows'] = $this->texts_model->get_texts_count ();
		$config ['per_page'] = '5';
		$config ['full_tag_open'] = '<p>';
		$config ['full_tag_close'] = '</p>';
		$this->pagination->initialize ( $config );
		$data ['kop'] = "Tekst beheer";
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_prices"] = $this->zacl->check_acl ( "prices_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ['results'] = $this->texts_model->get_texts ( $config ['per_page'], $this->uri->segment ( 3 ) ); //was 1 maar dat is een andere waarde, het is nu seg 3
		$data ['paginate_links'] = $this->pagination->create_links ();		
		$this->load->view ( 'texts_list', $data );
	}
	
	function add() {
		$page_num = $this->uri->segment ( 3 );
		$deelnemer = $_SESSION ['deelnemer'];
		$data ['page_number'] = $page_num;
		$data ['deelnemer'] = $deelnemer;
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_prices"] = $this->zacl->check_acl ( "prices_index" );
		
		$this->load->model ( 'users_model' );
		$data ['deelnemer_combobox'] = $this->users_model->get_users_list ($deelnemer);
		$this->load->view ( 'text_new', $data );		
	}
	
	function edit() {
		/*		if (!$this->zacl->check_acl($this->resource))
        {
           $this->load->helper('url');
           redirect('/users/login');
        }*/
//		$password = $this->input->post ( 'password' );
		$edit_id = $this->uri->segment ( 3 );
		$page_num = $this->uri->segment ( 4 );
		if ($this->uri->segment ( 5 ) === false) {
			$data ['moderate_save'] = "";
			$data ["moderated"] = FALSE;			
		} else {
			$data ['moderate_save'] = "moderated";
			$data ["moderated"] = TRUE;			
		}
		$deelnemer = $_SESSION ['deelnemer'];
		$data ['id'] = $edit_id;
		$data ['page_number'] = $page_num;
		$data ['deelnemer'] = $deelnemer;
		$this->load->model ( 'texts_model' );
		$data ['sqlrow'] = $this->texts_model->get_edit_text ( $edit_id );
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_prices"] = $this->zacl->check_acl ( "prices_index" );
		$this->load->view ( 'texts_edit', $data );
	}
	
	function delete() {
		$edit_id = $this->uri->segment ( 3 );
		$page_num = $this->uri->segment ( 4 );
		$this->load->model ( 'texts_model' );		
		$sql_result = $this->texts_model->delete_text ( $edit_id );
		if ($page_num == null) {
			$page_num = 0;
		}
		redirect ( '/texts/index/' . $page_num );
	}
	
	
	function moderate() {
		$this->load->library ( 'pagination' );		
		$page_num = $this->uri->segment ( 3 );
		if ($page_num == null) {
			$page_num = 0;
		}
		$data ["moderated"] = TRUE;
		$data ["page_number"] = $page_num;
		$this->load->model ( 'texts_model' );
		$this->load->library ( 'pagination' );
		$deelnemer = $_SESSION ['deelnemer'];
		$config ['base_url'] = base_url () . 'index.php/texts/moderate/';
		$config ['total_rows'] = $this->texts_model->get_moderate_texts_count ();
		$config ['per_page'] = '5';
		$config ['full_tag_open'] = '<p>';
		$config ['full_tag_close'] = '</p>';
		$this->pagination->initialize ( $config );
		$data ['kop'] = "Tekst beheer";
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_prices"] = $this->zacl->check_acl ( "prices_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ['results'] = $this->texts_model->get_moderate_texts ( $config ['per_page'], $this->uri->segment ( 3 ) ); //was 1 maar dat is een andere waarde, het is nu seg 3
		$data ['paginate_links'] = $this->pagination->create_links ();
		$this->load->view ( 'texts_list', $data );
	}


	function twitter()
	{
	/*	require_once('twitter/twitter.class.php');
		$consumerKey = "KqxORGrW8Qom0BDa37sew";
		$consumerSecret = "w6rbLPUsFZskxBYQKMoQg9CdxBk0tuP8BiZ5l1jgWc";
		$accessToken = "82625591-2CU7xAI3RCCZ62p7BRoTr5NZ8f2R4osyVGTChVbWk";
		$accessTokenSecret = "YBHg125wTl3rM6ZV8yn7ktimLkiH117SCuW68mU1Y";
		$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
		$twitter->send('Dinsdag 2 uur begint de Fenexpo in Gorinchem!');

	$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);



Consumer key
337vZbuxeannyrg6bHZwhQ

Consumer secret
qZnCZaDVnVAfoiXLrEO981ssXLenkeBoOHNMXoPEo

Request token URL
https://api.twitter.com/oauth/request_token

Access token URL
https://api.twitter.com/oauth/access_token

Authorize URL
https://api.twitter.com/oauth/authorize



Access Token (oauth_token)
82625591-TmuvYOtgCuOE8aJfa7jOPhU2MAsyLpNtD8FalX8T0

Access Token Secret (oauth_token_secret)
S4OQ4efbqjvQUXPpvlSpd9gKi8M7rPfpEmIZuUQw2yg

Permission Level for this access token
read, write, and direct messages

		
		*
		*
		*
		*
		*/
	}
	
}