<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Users extends CI_Controller {
	
	public $resource = "";
	
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'zacl' );
		$this->resource = $this->uri->segment ( 1 ) . '_' . $this->uri->segment ( 2 );
		if (! $this->zacl->check_acl ( $this->resource )) {
			//       $this->load->helper('url');
			//       redirect('/users/login2');
			//echo "login error";
		}
	}
	
	function logout() {
		$_SESSION ['filter_jaartal'] = date("Y");  //zet maar weer terug filter_jaartal
		$_SESSION ['adminusergrid'] = false;

		$http_url = "http";
		if (GEBRUIK_HTTPS == 1){
			$http_url = "https";
		}

		if (isset($_SESSION['komtvanuren'])) {
			session_destroy ();
			//WAS redirect ( 'http://coopinfo.nl' );   //redirectextern
			redirect ( $http_url . '://www.coopinfo.nl' );   //redirectextern			
		} else {
			session_destroy ();
			redirect ( '/users/login' );
		}
	}
	
	function terugnaarkeuzemenu() {
		$_SESSION ['filter_jaartal'] = date("Y");  //zet maar weer terug filter_jaartal
		$_SESSION ['adminusergrid'] = false;
		$http_url = "http";
		if (GEBRUIK_HTTPS == 1){
			$http_url = "https";
		}
		//WAS redirect ( 'http://www.coopinfo.nl/index.php/uren/keuze_pagina' );   //redirectextern
		redirect ( $http_url . '://www.coopinfo.nl/index.php/uren/keuze_pagina' );   //redirectextern
		
	}
	
	function adminselect() {
		$gebruikers_id = $this->uri->segment ( 3 );
		$_SESSION ['user_id'] = $gebruikers_id; //overruled dus de admin user_id, maar die werd verder niet gebruikt.
		
		$_SESSION ['adminusergrid'] = false;
		//effectief geef je hiermee het programma een andere id, waar verder mee gefiltered wordt op o.a. lijsten. 
		//redirect ( '/pdf/index' );   
		
		$this->load->model ( 'users_model' );

		$company = $this->users_model->getAdmninUserById ( $gebruikers_id); //,  $soortdeelnemer);
		$_SESSION ['company'] = $company;
		$data['company'] = $company;
		
		//redirect ( '/pdf/index_grid' );   //gridchart aanpassing
		redirect ( '/users/indexcharts', $data );   //gridchart aanpassing
	}
	
	function firstlogin() {
		
		$_SESSION ['filter_jaartal'] = date("Y");  //zet maar weer terug filter_jaartal
		
		$soortdeelnemer = $_SESSION ['deelnemer'];
		
		$password1 = $this->input->post ( 'password1' );
		$password2 = $this->input->post ( 'password2' );
		//sla pw op en neem dan session over zoals bij normale login.
		$data ['user_logged_in_error'] = 0; //niet tonen
		$data ['password1'] = $password1;
		$data ['password2'] = $password2;
		if ($password1 === FALSE) {
			//$data ['user_logged_in_error'] = 1;			
		} else {
			if ($password1 === FALSE) {
				$password1 = "";
			}
			if ($password2 === FALSE) {
				$password2 = "";
			}
			$password1 = trim ( $password1 );
			$password2 = trim ( $password2 );
			
			if (($password1 === $password2) && ($password1 != "") && ($password2 != ""))  {
				
				$this->load->model ( 'users_model' );
				
				$gebruikerid = $_SESSION ['user_id'];
				$this->users_model->update_password ( $gebruikerid, $password1, $soortdeelnemer );
				$data ['results'] = $this->users_model->check_user_exist_id ( $gebruikerid,  $soortdeelnemer);
				$results = $data ['results'];
				if ($results->num_rows === 1) {
					$row = $results->result ();
					foreach ( $results->result () as $row ) { //het is er maar 1!
						$_SESSION ['user_id'] = $row->GebruikerID; //was id;
						$_SESSION ['role'] = 'user'; 
						$_SESSION ['logged_in'] = TRUE;
						$_SESSION ['company'] = $row->company_name;
						$_SESSION ['isfactoring'] = $row->Factoring;
						$_SESSION ['deelnemer'] = $row->deelnemer;
					}
					$data ['user_logged_in_error'] = 0;
					$this->load->helper ( 'url' );
					
					$soortdeelnemer = $_SESSION ['deelnemer'];
					$this->users_model->update_formervisit ( $_SESSION ['user_id'], $soortdeelnemer );
					$this->users_model->insert_login_record( $_SESSION ['user_id'], $soortdeelnemer );
					
					if ($row->deelnemer == 'opd')   //opdrachtgever
					{
						redirect ( '/pdf/fiatteren' );
					}
					else
					{
						redirect ( '/users/index' );
					}
				}
			} else {
				$data ['user_logged_in_error'] = 1;
			}
			
		}
		$this->load->view ( 'loginfirsttime', $data );
	}
	
//mod_factoring	
	function getTokenUserID() {
		$werknemer_id = $_SESSION ['user_id'];
		$this->load->model ( 'pdf_model' );
		$result = $this->pdf_model->getTokenWithUserID( $werknemer_id );
		echo $result;
	}	
	function getWerknemerID() {
		$werknemer_id = $_SESSION ['user_id'];
		echo $werknemer_id;
	}	
////
			
	function loginsqltest() {

		$this->load->model ( 'users_model' );
		$query  = $this->users_model->check_user_exist ( "anouknijpels1989@gmail.com", "Nijpels2022#" );
		
		$responce = new stdClass;
		$i=0;
			
		if (($query !== null) &&  ($query->num_rows === 1)) {
			foreach ( $query->result () as $row ) { //het is er maar 1!
				$responce->rows[$i]['cell'] = array(
					$row->GebruikerID,
					$row->role,
					$row->company_name,
					$row->Factoring
				);

				$i++;
			}        
		}
		echo json_encode($responce);
		
	}
	
	function login() {
		
		$this->load->model ( 'users_model' );
		
		$data ['user_ww_vergeten_email_verzonden'] = 0; //niet tonen
		$data ['user_logged_in_error'] = 0;
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		
		/**/
		$pieces_username = explode(" ", $username);
		$username = $pieces_username[0];
		//
		$pieces_password = explode(" ", $password);
		$password =  $pieces_password[0];
		/**/
		
		/**/
		$username  = str_replace (  "'" ,  " " ,  $username );
		$password  = str_replace (  "'" ,  " " ,  $password );
		/**/
		
		$wwvergeten = $this->input->post ( 'submitwwvergeten' );
		if ($wwvergeten != false)
		{
			$email_verzonden = $this->users_model->wachtwoord_vergeten_email ($username);
			if ($email_verzonden ==true)
			{
				$data ['user_ww_vergeten_email_verzonden'] = 1;
			}
			$data ['username'] = $username;
			$data ['password'] = '';

			$this->load->view ( 'login', $data );	
		}
		else 
		{
			$_SESSION ['filter_jaartal'] = date("Y");  //zet maar weer terug filter_jaartal
			
			$data ['user_logged_in_error'] = 0; //niet tonen
			$data ['username'] = $username;
			$data ['password'] = $password;
			
			if ($username === FALSE) {
			} else {
				if ($password === FALSE) {
					$password = "none";
				}
				if ($username === FALSE) {
					$username = "none";
				}
				
				$data ['results'] = $this->users_model->check_user_exist ( $username, $password );
				$results = $data ['results'];
				if (($results !== null) &&  ($results->num_rows === 1)) {
//				if ($results->num_rows === 1) {
					$row = $results->result ();
					foreach ( $results->result () as $row ) { //het is er maar 1!
						$_SESSION ['user_id'] = $row->GebruikerID; //was id;
						$_SESSION ['role'] = $row->role;
						$_SESSION ['company'] = $row->company_name;
						$_SESSION ['isfactoring'] = $row->Factoring;
						$_SESSION ['deelnemer'] = $row->deelnemer;
						$_SESSION ['logged_in'] = TRUE;
					}
					$data ['user_logged_in_error'] = 0;
					$this->load->helper ( 'url' );

					if ($row->deelnemer == 'opd')   //opdrachtgever
					{
						redirect ( '/pdf/fiatteren' );
					}
					else
					{
						redirect ( '/users/index' ); 
					}
				} else {
					$data ['user_logged_in_error'] = 1;
					/**/
					$max_keren = 15;
					$te_vaak_fout_wachtwoord = $this->users_model->check_user_exist_fout_wachtwoord ( $username, $password);
					if ($te_vaak_fout_wachtwoord >=$max_keren){
						$data ['user_logged_in_error'] = 5;
					}
					/**/
				}
			}
			$this->load->view ( 'login', $data );
		}
	}

	function loginp() {
		
		$this->load->model ( 'users_model' );
		
		$userguid = $_GET['x'];
		
		$data ['user_ww_vergeten_email_verzonden'] = 0; //niet tonen
		$data ['user_logged_in_error'] = 0;
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		$username = "";
		$password = "";
		
		$_SESSION ['filter_jaartal'] = date("Y");  //zet maar weer terug filter_jaartal
		
		$data ['user_logged_in_error'] = 0; //niet tonen
		$data ['username'] = $username;
		$data ['password'] = $password;
		
		if ($userguid === FALSE) {
		} else {
			if ($password === FALSE) {
				$password = "none";
			}
			if ($username === FALSE) {
				$username = "none";
			}
			$data ['results'] = $this->users_model->check_user_exist_parameter ( $userguid );
			$results = $data ['results'];
			if ($results->num_rows === 1) {
				$row = $results->result ();
				foreach ( $results->result () as $row ) { //het is er maar 1!

					$_SESSION ['user_id'] = $row->GebruikerID; //was id;
					$_SESSION ['role'] = $row->role;
					$_SESSION ['company'] = $row->company_name;
					$_SESSION ['isfactoring'] = $row->Factoring;
					$_SESSION ['deelnemer'] = $row->deelnemer;
					$_SESSION ['logged_in'] = TRUE;
					
					$data ['username'] = $row->username;
					$data ['password'] = $row->pw_decrypted;


					$soortdeelnemer = $_SESSION ['deelnemer'];
					//$this->users_model->update_formervisit ( $_SESSION ['user_id'], $soortdeelnemer );
					//$this->users_model->insert_login_record( $_SESSION ['user_id'], $soortdeelnemer );
					
					///////////////////////////////////////////
					
				}
				$data ['user_logged_in_error'] = 0;
				$this->load->helper ( 'url' );

				
				$_SESSION['komtvanuren'] = "Yep";
				
				redirect ( '/users/index' ); 
				//}
			} else {
				$data ['user_logged_in_error'] = 1;
			}
		}
		$this->load->view ( 'login', $data );   //dit zou dan eigenlijk terug moeten gaan naar de uren app, dus als je hier binnenkomt via de parameter aanroep..

	}
	
	function profile() {
		echo "The restricted profile page<br />";
		echo "Your user id: " . $_SESSION ['user_id'];
	}
	
	function setsess() {
		$_SESSION ['user_id'] = 1;
	}
	
	function remsess() {
		unset ( $_SESSION ['user_id'] );
	}
	
	function indexcharts() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		
		$_SESSION ['adminusergrid'] = true;
		
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ["isfactoring"] = $_SESSION ['isfactoring'];
	
		$werknemer_id = $_SESSION ['user_id'];
		
		
		$this->load->model ( 'pdf_model' );
		
		$data['vlaaidiagram'] = $this->pdf_model->get_chart_pie2 ( $werknemer_id  );
		$data['staafdiagram'] = $this->pdf_model->get_chart_staaf2 ($werknemer_id );
		
		$this->load->view ( 'home_chart', $data );  //2inlog
		
	}
	
	function index() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		$_SESSION ['adminusergrid'] = false;
		
		//$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ["isfactoring"] = $_SESSION ['isfactoring'];
		
		$werknemer_id = $_SESSION ['user_id'];
		
		if ($_SESSION ["role"] === "administrator") {
			
			if (isset ( $_SESSION ["searchfor"] ) === false) {
				$_SESSION ["searchfor"] = "";
			}
			
			$searchfor = $this->input->post ( 'searchfor' );
			
			if ($searchfor !== false) {
				$_SESSION ["searchfor"] = $searchfor;
			}
			if ($searchfor === false) {
				$searchfor = $_SESSION ["searchfor"];
			}
			
			$data ["searchfor"] = $searchfor;
			
			//print_r("ZOEKINFO: " . $searchfor);
			
			/** /
			$this->load->model ( 'users_model' );
			$this->load->library ( 'pagination' );
			$config ['base_url'] = base_url () . 'index.php/users/index/';
			$config ['total_rows'] = $this->users_model->OUD_get_users_count ( $searchfor );
			$config ['per_page'] = '15'; //paginering instelling
			$config ['full_tag_open'] = '<p>';
			$config ['full_tag_close'] = '</p>';
			$config ['first_link'] = '&lsaquo; Eerste';
			$config ['last_link'] = 'Laatste &rsaquo;';
			$this->pagination->initialize ( $config );
			$data ['results'] = $this->users_model->OUD_get_zzp_er_list ( $config ['per_page'], $this->uri->segment ( 3 ), $werknemer_id, $searchfor );
			$data ['paginate_links'] = $this->pagination->create_links ();
			//$this->load->view ( 'home_admin', $data );
			/**/
			$this->load->view ( 'home_admin_grid', $data );
		} else {
			//$this->load->view ( 'home', $data );
			//hier de data ophalen voor de charts.
			//
			$this->load->model ( 'pdf_model' );
			
			$data['vlaaidiagram'] = $this->pdf_model->get_chart_pie2 ( $werknemer_id  );
			$data['staafdiagram'] = $this->pdf_model->get_chart_staaf2 ($werknemer_id );
			
			$this->load->view ( 'home_chart', $data );  
			
		}
	}


	function OUD_index() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		$_SESSION ['adminusergrid'] = false;
		
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ["isfactoring"] = $_SESSION ['isfactoring'];
		
		$werknemer_id = $_SESSION ['user_id'];
		
		if ($_SESSION ["role"] === "administrator") {
			if (isset ( $_SESSION ["searchfor"] ) === false) {
				$_SESSION ["searchfor"] = "";
			}
			$searchfor = $this->input->post ( 'searchfor' );
			if ($searchfor !== false) {
				$_SESSION ["searchfor"] = $searchfor;
			}
			if ($searchfor === false) {
				$searchfor = $_SESSION ["searchfor"];
			}
			$data ["searchfor"] = $searchfor;
			$this->load->model ( 'users_model' );
			$this->load->library ( 'pagination' );
			$config ['base_url'] = base_url () . 'index.php/users/index/';
			$config ['total_rows'] = $this->users_model->OUD_get_users_count ( $searchfor );
			$config ['per_page'] = '15'; //paginering instelling
			$config ['full_tag_open'] = '<p>';
			$config ['full_tag_close'] = '</p>';
			$config ['first_link'] = '&lsaquo; Eerste';
			$config ['last_link'] = 'Laatste &rsaquo;';
			$this->pagination->initialize ( $config );
			$data ['results'] = $this->users_model->OUD_get_zzp_er_list ( $config ['per_page'], $this->uri->segment ( 3 ), $werknemer_id, $searchfor );
			$data ['paginate_links'] = $this->pagination->create_links ();
			$this->load->view ( 'home_admin', $data );
		} else {
			//$this->load->view ( 'home', $data );
			//hier de data ophalen voor de charts.
			//
			$this->load->model ( 'pdf_model' );
			
			$data['vlaaidiagram'] = $this->pdf_model->get_chart_pie2 ( $werknemer_id  );
			$data['staafdiagram'] = $this->pdf_model->get_chart_staaf2 ($werknemer_id );
			
			$this->load->view ( 'home_chart', $data );  //2inlog
			
		}
	}

}

/* End of file users.php */
