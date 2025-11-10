<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Offline extends CI_Controller {
	
	public $resource = "";
	
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'zacl' );
		$this->resource = $this->uri->segment ( 1 ) . '_' . $this->uri->segment ( 2 );
		if (! $this->zacl->check_acl ( $this->resource )) {
			//
		}
	}
	
	function index() {
		$data ['test'] = "Test";
		$this->load->view ( 'offline', $data );
	}

}

/* End of file users.php */
