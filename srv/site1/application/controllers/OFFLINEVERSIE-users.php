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
		session_destroy ();
		redirect ( '/users/login' );
	}
	
	function adminselect() {
		$gebruikers_id = $this->uri->segment ( 3 );
		$_SESSION ['user_id'] = $gebruikers_id; //overruled dus de admin user_id, maar die werd verder niet gebruikt.
		//effectief geef je hiermee het programme een andere id, waar verder mee gefiltered wordt op o.a. lijsten. 
		redirect ( '/pdf/index' );
	}
	
	function firstlogin() {
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
				$password1 = "none";
			}
			if ($password2 === FALSE) {
				$password2 = "none";
			}
			$password1 = trim ( $password1 );
			$password2 = trim ( $password2 );
			if ($password1 === $password2) {
				
				$this->load->model ( 'users_model' );
			
				$gebruikerid = $_SESSION ['user_id'];
				$this->users_model->update_password ( $gebruikerid, $password1 );
				$data ['results'] = $this->users_model->check_user_exist_id ( $gebruikerid );
				$results = $data ['results'];
				if ($results->num_rows === 1) {
					$row = $results->result ();
					foreach ( $results->result () as $row ) { //het is er maar 1!
						$_SESSION ['user_id'] = $row->GebruikerID; //was id;
						$_SESSION ['role'] = $row->role;
						$_SESSION ['logged_in'] = TRUE;
						$_SESSION ['company'] = $row->company_name;
						$_SESSION ['deelnemer'] = $row->deelnemer;
					}
					$data ['user_logged_in_error'] = 0;
					$this->load->helper ( 'url' );
					redirect ( '/users/index' );
				}
			} else {
				$data ['user_logged_in_error'] = 1;
			}
		
		}
		$this->load->view ( 'loginfirsttime', $data );
	}
	
	function login() {
		
		redirect ( '/offline/index' );
		return;
		
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		
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
			$this->load->model ( 'users_model' );
			$data ['results'] = $this->users_model->check_user_exist ( $username, $password );
			$results = $data ['results'];
			if ($results->num_rows === 1) {
				$row = $results->result ();
				foreach ( $results->result () as $row ) { //het is er maar 1!
					$_SESSION ['user_id'] = $row->GebruikerID; //was id;
					$_SESSION ['role'] = $row->role;
					$_SESSION ['company'] = $row->company_name;
					$_SESSION ['deelnemer'] = $row->deelnemer;
					$_SESSION ['logged_in'] = TRUE;

					///////////////////////////////////////////					
					if ($row->formervisit_at == null) {
						$eerste_keer_inlog = true;
					}
					if ($eerste_keer_inlog === true) {
						redirect ( '/users/firstlogin' );
					}

					$soortdeelnemer = $_SESSION ['deelnemer'];

					$this->users_model->update_formervisit ( $_SESSION ['user_id'], $soortdeelnemer );
					///////////////////////////////////////////

				}
				$data ['user_logged_in_error'] = 0;
				$this->load->helper ( 'url' );
				redirect ( '/users/index' );
			} else {
				$data ['user_logged_in_error'] = 1;
			}
		}
		$this->load->view ( 'login', $data );
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
	
	function index() {
		if (! $this->zacl->check_acl ( $this->resource )) {
			$this->load->helper ( 'url' );
			redirect ( '/users/login' );
		}
		$data ["user_role_texts"] = $this->zacl->check_acl ( "texts_index" );
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$werknemer_id = $_SESSION ['user_id'];
		if ($_SESSION ["role"] === "administrator") {
			if (isset ( $_SESSION ["searchfor"] ) === false) {
				$_SESSION ["searchfor"] = "";
			}
			$searchfor = $this->input->post ( 'searchfor' );
			//if ($searchfor != "") {
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
			$config ['total_rows'] = $this->users_model->get_users_count ( $searchfor );
			$config ['per_page'] = '15'; //paginering instelling
			$config ['full_tag_open'] = '<p>';
			$config ['full_tag_close'] = '</p>';
			$config ['first_link'] = '&lsaquo; Eerste';
			$config ['last_link'] = 'Laatste &rsaquo;';
			$this->pagination->initialize ( $config );
			$data ['results'] = $this->users_model->get_zzp_er_list ( $config ['per_page'], $this->uri->segment ( 3 ), $werknemer_id, $searchfor );
			$data ['paginate_links'] = $this->pagination->create_links ();
			$this->load->view ( 'home_admin', $data );
		} else {
			$this->load->view ( 'home', $data );
		}
	}

}

/* End of file users.php */
