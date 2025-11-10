<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prices extends CI_Controller {
	
	public $resource = "";

	function __construct()
    {
        parent::__construct();
        $this->load->library('zacl');
        $this->resource = $this->uri->segment(1) . '_' . $this->uri->segment(2);
        if (!$this->zacl->check_acl($this->resource))
        {
            $this->load->helper('url');
            redirect('/users/index');
        }
    }
    
	function save() {
/*	    if (!$this->zacl->check_acl($this->resource))
        {
           $this->load->helper('url');
           redirect('/users/login');
        }*/
		$deelnemer = $_SESSION['deelnemer'];        
        $ar = $_POST;
        $edit_id = $this->input->post('edit_id_hidden');
        $page_num = $this->input->post('edit_page_hidden');
        $edit_tekst = $this->input->post('price_text');
        $edit_omschrijving = $this->input->post('price_omschrijving');
        $edit_price_actief = $this->input->post('price_actief');
        if (strtolower($edit_price_actief) == "on") {
        	$edit_price_actief = "A";
        } 
        else {
        	$edit_price_actief = "N";
        }
        $edit_trigger_counter = $this->input->post('edit_trigger_counter');
        $this->load->model('prices_model');
		$result = $this->prices_model->update_price_edit($edit_id, $deelnemer, $edit_tekst, $edit_omschrijving,$edit_trigger_counter, $edit_price_actief );
		redirect('/prices/index');
	}
	
	function edit() {
/*	    if (!$this->zacl->check_acl($this->resource))
        {
           $this->load->helper('url');
           redirect('/users/login');
        }*/
        $edit_id = $this->uri->segment(3);
		$page_num = $this->uri->segment(4);
		$error_upload = $this->uri->segment(5);
		if ($error_upload === FALSE) {
			$error_upload = 0;
		} 
		$deelnemer = $_SESSION['deelnemer'];
		$this->load->model('prices_model');
		$result = $this->prices_model->get_price_edit($edit_id, $deelnemer);
		$data["deelnemer"] = $deelnemer;
		$data["edit_tekst"] = $result->tekst;
		$data["edit_omschrijving"] = $result->omschrijving;
		$data["edit_image"] = $result->image;
		$data["edit_id"] = $result->id;
		$data["page_number"] = $page_num;
		$data["edit_trigger_counter"] = $result->trigger_counter;
		$data["edit_winning_counter"] = $result->winning_counter;
		$data["edit_created_at"] = $result->created_at;
		$data["edit_actief"] = $result->actief;		
        $data["user_role_texts"] = $this->zacl->check_acl("texts_index");
        $data["user_role_prices"] = $this->zacl->check_acl("prices_index");
		$data["company_name"] = $_SESSION['company'];
		if ($error_upload == 1) {
			$data["error"] = "Image niet gevonden, controleer de upload.";			
		} else {
			$data["error"] = "Upload geslaagd.";	
		}
				
		$this->load->view('price_edit', $data);
	}
		
	function do_upload()
	{
/*	    if (!$this->zacl->check_acl($this->resource))
        {
           $this->load->helper('url');
           redirect('/users/login');
        }*/
        $edit_id = $this->input->post('edit_id_hidden');
        $page_num = $this->input->post('edit_page_hidden');
		$error_upload = 0;
		echo $edit_id . " - " . $page_num;
		$deelnemer = $_SESSION['deelnemer'];
		$this->load->model('prices_model');
		$result = $this->prices_model->get_price_edit($edit_id, $deelnemer);
		$data["deelnemer"] = $deelnemer;
		$data["edit_tekst"] = $result->tekst;
		$data["edit_omschrijving"] = $result->omschrijving;
		$data["edit_image"] = $result->image;
		$data["edit_id"] = $result->id;
		$data["page_number"] = $page_num;
		$data["edit_trigger_counter"] = $result->trigger_counter;
		$data["edit_winning_counter"] = $result->winning_counter;
		$data["edit_created_at"] = $result->created_at;
		$data["edit_actief"] = $result->actief;		
        $data["user_role_texts"] = $this->zacl->check_acl("texts_index");
        $data["user_role_prices"] = $this->zacl->check_acl("prices_index");
		$data["company_name"] = $_SESSION['company'];		
		$config['upload_path'] = './image_price/' . $deelnemer;		
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['max_filename']  = '120';		
		$config['encrypt_name']  = TRUE;
		$config['remove_spaces']  = TRUE;
		$this->load->library('upload', $config);
		$error_upload = 0;
		$pffff = "geen gfouten!";;
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$pffff = $this->upload->display_errors();
			$error_upload = 1;
		}
		else
		{
			$data['upload_data_x'] = array('upload_data' => $this->upload->data());
			$data['upload_data'] = $this->upload->data();
			$price_image_filename = $data['upload_data']['file_name']; 
			$this->load->model('prices_model');
			$result = $this->prices_model->update_price_image($edit_id, $deelnemer, $price_image_filename );
		}
		$data["error"] = $error_upload;
		//echo "foiut!" . $error_upload . $pffff ; 
		redirect("/prices/edit/$edit_id/$page_num/$error_upload");		
	}
	
	function index()
	{
/*		if (!$this->zacl->check_acl($this->resource))
        {
           $this->load->helper('url');
           redirect('/users/login');
        }*/
		$data['kop'] = "Prijzen beheer"; 
        $data["user_role_texts"] = $this->zacl->check_acl("texts_index");
        $data["user_role_prices"] = $this->zacl->check_acl("prices_index");
		$data["company_name"] = $_SESSION['company'];
		$deelnemer = $_SESSION['deelnemer'];
		$data["deelnemer"] = $deelnemer;
		$page_num = $this->uri->segment(3);
        if ($page_num == null) {
        	$page_num = 0;
        }
		$data["page_number"] = $page_num;
		$this->load->model('prices_model');
		
		$data['kop'] = "Tekst beheer";
        $data["user_role_texts"] = $this->zacl->check_acl("texts_index");
        $data["user_role_prices"] = $this->zacl->check_acl("prices_index");
		$data["company_name"] = $_SESSION['company'];

        $data['results'] = $this->prices_model->get_prices_all($deelnemer);        
		$data['paginate_links'] = "";
		$this->load->view('prices_list', $data);
	}

}

//end