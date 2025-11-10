<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Pdf extends CI_Controller {
	
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
	
	function nextyear() {
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'] + 1;
		$_SESSION ['filter_jaartal'] = $filter_jaartal;
		redirect ( '/pdf/index' );
	}
	
	function prevyear() {
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'] -1;
		$_SESSION ['filter_jaartal'] = $filter_jaartal;
		redirect ( '/pdf/index' );
	}


	function nextyear_grid() {
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'] + 1;
		$_SESSION ['filter_jaartal'] = $filter_jaartal;
		redirect ( '/pdf/index_grid' );
	}
	
	function prevyear_grid() {
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'] -1;
		$_SESSION ['filter_jaartal'] = $filter_jaartal;
		redirect ( '/pdf/index_grid' );
	}

	//function ajaxsavefiatpopup_week()  //per week aanpassing 
	function ajaxsavefiatpopup_weekjson()  //per week aanpassing 
	{
		$this->load->model ( 'pdf_model' );
		$req_werkbriefje_id = $this->input->post ( 'req_werkbriefje_id' ); 
		$req_fiat_checkbox = $this->input->post ( 'req_fiat_checkbox' ); 
		$req_afgekeurd_checkbox = $this->input->post ( 'req_afgekeurd_checkbox' ); 
		$req_redenafgekeurd_text = $this->input->post ( 'req_redenafgekeurd_text' ); 
		$query = $this->pdf_model->set_fiat_afgekeurd_reden_perwerkbriefje($req_werkbriefje_id, $req_fiat_checkbox, $req_afgekeurd_checkbox, $req_redenafgekeurd_text);  //!important!
		if ($query==true) {
			$pdfx["errorr"] = "succes";
		} else {
			$pdfx["errorr"] = "no-data";
		}
		echo json_encode( $pdfx  );
	}
	
	function ajaxsavefiatpopup()
	{
		$p_checkbox = $this->input->post ( 'cbfiatteren' ); 
		$p_id = $this->input->post ( 'cbfiatteren_id' ); 
		//update uren checkbox als checked
	}
	
	function ajaxcheckboxfiatjson()
	{
		$this->load->model ( 'pdf_model' );	//hoeft niet
		$this->load->helper('url');   //hoeft niet
		$checkboxstate = $this->input->post ( 'req_cbstate' );
		$_SESSION ['filter_fiat'] = $checkboxstate;
		$data["errorr"] = "succes";
		echo json_encode( $data );
	}

	//mod_factoring	
	function getTokenUserID_KAN_WEG_NEE_ECHT_KAN_WEG() {
		$werknemer_id = $_SESSION ['user_id'];
		$this->load->model ( 'pdf_model' );
		$query = $this->pdf_model->getTokenWithUserID ( $werknemer_id );
		
		$respons = new stdClass;
		foreach ( $query->result() as $row ) {
			$respons->row = $row;
		}        
		echo json_encode($respons);

	}
	
	//mod_factoring	
	function getTokenUserID() {
		$werknemer_id = $_SESSION ['user_id'];
		$this->load->model ( 'pdf_model' );
		$result = $this->pdf_model->getTokenWithUserID( $werknemer_id );
		echo $result;
	}	
	
	/*
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
	*/
	function fiatteren() {
		$page_num = $this->uri->segment ( 3 );
		
		if ($page_num == null) {
			$page_num = 0;
		}
		
		if (! isset($_SESSION ['filter_fiat'])) {
			$_SESSION ['filter_fiat'] = 0;
		}
		$filteropfiat = $_SESSION ['filter_fiat'];
		$soortdeelnemer =  $_SESSION ['deelnemer'];

		$pagina_nummer = $this->uri->segment ( 3 );
		if ($pagina_nummer == "")
		{
			$pagina_nummer = 1;
			$pagina_nummer = 0;   //BUGFIX, met 0 starten slaat bij 1 starten de eerste over???
		}
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$deelnemer = $_SESSION ['deelnemer'];
		$soortdeelnemer = $_SESSION ['deelnemer'];
		
		$werknemer_id = $_SESSION ['user_id'];

		$data ["moderated"] = FALSE;  //moderated_list?? raar		
		$data ["page_number"] = $page_num;
		$this->load->model ( 'pdf_model' );
		$this->load->model ( 'users_model' );
		
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'index.php/pdf/fiatteren/';
				
		$config ['total_rows'] = $this->pdf_model->get_fiatering_count ($werknemer_id, $filteropfiat, 'zzp');  //filterzzp
		
		$config ['per_page'] = '15';  //paginering instelling
		//$config ['per_page'] = '5';  //paginering instelling
		//$config ['per_page'] = '3';  //paginering instelling TODO
		//$config ['per_page'] = '1';  
		
		$config ['full_tag_open'] = '<p>';
		$config ['full_tag_close'] = '</p>';
		$config ['first_link'] = '&lsaquo; Eerste';
		$config ['last_link'] = 'Laatste &rsaquo;';
		$this->pagination->initialize ( $config );

		$data ['kop'] = "Werkbriefjes Overzicht";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ['results'] = $this->pdf_model->get_fiatering_list ( $config ['per_page'], $pagina_nummer, $werknemer_id, $filteropfiat, 'zzp');  //filterzzp
		//echo $base_url;
		//echo $pagina_nummer;
		//echo $werknemer_id;
		//echo "<h1>TEST: </h1>";
		//echo $filteropfiat;	
		$data["filterfiat"] = $filteropfiat;
		$data ['paginate_links'] = $this->pagination->create_links ();
		$this->load->helper ( 'uren' );
		$this->load->view ( 'opd_list_week', $data );   //nieuwe view voor per week.
	}

	function index_grid() {
		$page_num = $this->uri->segment ( 3 );
		if ($page_num == null) {
			$page_num = 0;
		}
		
		//kan wel weg?
		$moderated_list = $this->uri->segment ( 4 );

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$deelnemer = $_SESSION ['deelnemer'];

		$werknemer_id = $_SESSION ['user_id'];
		
		
		//$werknemer_id = "2315";
		//$filter_jaartal = "2025";
		
		$data ["moderated"] = FALSE;  //moderated_list?? raar		
		$data ["page_number"] = $page_num;
		$this->load->model ( 'pdf_model' );
		$this->load->model ( 'users_model' );
		
		//per 20130807  Luuk zegt, doe conditie maar weg.
		$data["btw_tonen"] = true;
		
		$data ['jaartal_filtered'] = $filter_jaartal;
		$data ['kop'] = "Factuur Overzicht";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ["is_factoring"] = $_SESSION ['isfactoring'];
		$data ["werknemer_id"] = $werknemer_id;
		//$data ['results'] = $this->pdf_model->get_pdf ( $config ['per_page'], $this->uri->segment ( 3 ), $werknemer_id, $filter_jaartal);
		//error_log('Sessie --> : ' . json_encode($werknemer_id  . "---" . $filter_jaartal));
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_list2', $data );
	}

	function index_grid2() {
		$page_num = $this->uri->segment ( 3 );
		if ($page_num == null) {
			$page_num = 0;
		}
		
		//kan wel weg?
		$moderated_list = $this->uri->segment ( 4 );

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$deelnemer = $_SESSION ['deelnemer'];

		$werknemer_id = $_SESSION ['user_id'];
		
		$data ["moderated"] = FALSE;  //moderated_list?? raar		
		$data ["page_number"] = $page_num;
		$this->load->model ( 'pdf_model' );
		$this->load->model ( 'users_model' );
		
		//per 20130807  Luuk zegt, doe conditie maar weg.
		//$data["btw_tonen"] = false;
		//$data["btw_tonen"] = $this->users_model->get_btw_welofniet($werknemer_id);
		$data["btw_tonen"] = true;
		
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'index.php/pdf/index/';
		//?$config ['total_rows'] = $this->pdf_model->get_pdf_count ($werknemer_id, $filter_jaartal);
		$config ['per_page'] = '15';  //paginering instelling
		$config ['full_tag_open'] = '<p>';
		$config ['full_tag_close'] = '</p>';
		$config ['first_link'] = '&lsaquo; Eerste';
		$config ['last_link'] = 'Laatste &rsaquo;';
		//?$this->pagination->initialize ( $config );
		$data ['jaartal_filtered'] = $filter_jaartal;
		$data ['kop'] = "Factuur Overzicht";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		//?	$data ['results'] = $this->pdf_model->get_pdf ( $config ['per_page'], $this->uri->segment ( 3 ), $werknemer_id, $filter_jaartal);
		//?	$data ['uren_in_minuten_totaal'] = $this->pdf_model->get_uren_totaal_in_minuten($werknemer_id, $filter_jaartal);
		//?	$data ['result_total'] = $this->pdf_model->get_pdf_totaal ($werknemer_id, $filter_jaartal);		
		//?	$data ['paginate_links'] = $this->pagination->create_links ();
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_list2', $data );
	}

	function getfacturenjson() {

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$werknemer_id = $_SESSION ['user_id'];

		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		//if(!$sidx) $sidx =1;
		if(!$sidx) $sidx ="FactuurDatumDATEONLY";
		
		if (($sidx=="week") || ($sidx=="") || ($sidx==1)) {
			//$sidx = " WerknemerID, Factuurnr  ";
		}
		
		$this->load->model ( 'pdf_model' );

		$count = $this->pdf_model->grid_get_pdf_count2 ($werknemer_id, $filter_jaartal);
		
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)

		$uren_in_minuten_totaal = 0.00;
		$totaal_factuur = 0.00;
		$totaal_voorschot = 0.00;
		$totaal_borgzzp = 0.00;
		$totaal_resteert = 0.00;
		$totaal_kosten = 0.00;
		$totaal_btw = 0.00;

		$query = $this->pdf_model->grid_get_pdf2 ( $limit, $start, $sidx, $sord, $werknemer_id, $filter_jaartal);
		
		$responce = new stdClass;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		$this->load->helper ( 'uren' );
		
		$i=0;
			
		foreach ( $query->result() as $row ) {			
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell'] = array(
				'periode' => $row->periode,
				'week' =>$row->Week,
				'opdrachtgever' => $row->Opdrachtgever,
				'uren' => formaturen($row->uren),
				'Factuurnr' => $row->Factuurnr, 
				'Factuurnr' =>$row->Factuurnr,
				'FactuurDatumDATEONLY' => $row->FactuurDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->FactuurDatumDATEONLY)),
				'VervalDatumDATEONLY' => $row->VervalDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->VervalDatumDATEONLY)),
				'BetaalddatumDATEONLY' => $row->BetaalddatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->BetaalddatumDATEONLY)),
				'FactuurTotaal' => mssql_number_format($row->FactuurTotaal, 2, ',', '.') ,
				'Voorschot'=>mssql_number_format($row->Voorschot, 2, ',', '.') ,
				'BorgZZP'=>mssql_number_format($row->cooperatie, 2, ',', '.'),
				'kosten'=>mssql_number_format($row->kosten, 2, ',', '.')  ,
				'Resteert'=>number_format($row->Resteert, 2, ',', '.'),
				'Resteert'=>number_format($row->Resteert, 2, ',', '.'),
				'Btw' => mssql_number_format($row->BtwSaldo, 2, ',', '.') ,
				'FactuurADZ' => $row->FactuurADZ, 
				'FactuurADZ' =>$row->FactuurADZ,
				'ContactMoment' => $row->contactmoment,
				'ContactMoment2' => 123456789,
				$row->HeeftFactoring,
				$row->FactoringLink,
				$row->id,
				'filter_jaartal' => $filter_jaartal,
                'werknemer_id' => $werknemer_id
				);
				//row id erbij voor de contactmomenten opmerkingen popup 20171012
				/*was kol-11 mssql_number_format($row->contributie, 2, ',', '.'),   //    dit moet cooperatie worden???  */			
			$uren_in_minuten_totaal += mssql_number_bereken($row->minuten);
			$totaal_factuur += mssql_number_bereken($row->FactuurTotaal);
			$totaal_voorschot += mssql_number_bereken($row->Voorschot);
			$totaal_btw += mssql_number_bereken($row->BtwSaldo);
		
			//11 was $totaal_borgzzp += mssql_number_bereken($row->contributie);
			$totaal_borgzzp += mssql_number_bereken($row->cooperatie);
			
			$totaal_kosten  += mssql_number_bereken($row->kosten);
			$totaal_resteert+= mssql_number_bereken($row->Resteert);

			$i++;
		}        

		$responce->userdata['week'] = '';
		$responce->userdata['uren'] = formaturen_uit_minuten_totaal($uren_in_minuten_totaal);
		$responce->userdata['FactuurTotaal'] =  mssql_number_format($totaal_factuur, 2, ',', '.');
		$responce->userdata['Voorschot'] =  mssql_number_format($totaal_voorschot, 2, ',', '.');
		$responce->userdata['BorgZZP'] =  mssql_number_format($totaal_borgzzp, 2, ',', '.');
		$responce->userdata['Resteert'] =  mssql_number_format($totaal_resteert, 2, ',', '.');
		$responce->userdata['Btw'] =  mssql_number_format($totaal_btw, 2, ',', '.');
		$responce->userdata['kosten'] =  mssql_number_format($totaal_kosten, 2, ',', '.');
		//nieuwpdf
		//$responce->userdata['het_jaar'] = $filter_jaartal;
	
		echo json_encode($responce);
		
	}

	function getadministratorjson() {

		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		$searchfor = $_GET['FilterValue']; // zoeken met
		if(!$sidx) $sidx ="Bedrijfsnaam";
		
		if ($searchfor == false) {
			$searchfor = '';
		}

		$this->load->model ( 'users_model' );

		$count = $this->users_model->get_users_count ( $searchfor );
		
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)

		$query = $this->users_model->get_zzp_er_list ( $limit, $start, $sidx, $sord, $searchfor );
		
		$responce = new stdClass;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		$i=0;
		foreach ( $query->result() as $row ) {
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell'] = array(
				$row->GebruikerID,
				$row->username,								
				$row->last_name,
				$row->email, 
				$row->company_name				
				);
			$i++;
		}        
		
		echo json_encode($responce);
		
	}



	function getfacturenjson_oud() {

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$werknemer_id = $_SESSION ['user_id'];
		
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;
		
		if (($sidx=="week") || ($sidx=="") || ($sidx==1)) {
			$sidx = " WerknemerID, Factuurnr  ";
		}
		
		$this->load->model ( 'pdf_model' );
		
		$count = $this->pdf_model->grid_get_pdf_count ($werknemer_id, $filter_jaartal);
		
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		$query = $this->pdf_model->grid_get_pdf ( $limit, $start, $sidx, $sord, $werknemer_id, $filter_jaartal);

		$responce = new stdClass;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		$this->load->helper ( 'uren' );
		
		$i=0;

		foreach ( $query ->result() as $row ) {			
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell'] = array(
				//$row->id,
				$row->Week,
				$row->Opdrachtgever,
				formaturen($row->uren),
				$row->Factuurnr, 
				$row->Factuurnr,
				$row->FactuurDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->FactuurDatumDATEONLY)),
				$row->VervalDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->VervalDatumDATEONLY)),
				$row->BetaalddatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->BetaalddatumDATEONLY)),
				number_format($row->FactuurTotaal, 2, ',', '.'),
				number_format($row->Voorschot, 2, ',', '.'),
				number_format($row->BorgZZP, 2, ',', '.'),
				number_format($row->kosten, 2, ',', '.'),
				number_format($row->Resteert, 2, ',', '.'),
				number_format($row->Resteert, 2, ',', '.'),
				$row->FactuurADZ, 
				$row->FactuurADZ
				);
			$i++;
			break; //even maar 1 record voor de test!! !!!! !!!! !!!!
		}        

		$responce->userdata['week'] = '';				
		$uren_in_minuten_totaal = $this->pdf_model->get_uren_totaal_in_minuten($werknemer_id, $filter_jaartal);
		$responce->userdata['uren'] = formaturen_uit_minuten_totaal($uren_in_minuten_totaal);
		$result_total = $this->pdf_model->get_pdf_totaal ($werknemer_id, $filter_jaartal);
		foreach ( $result_total->result () as $row_total ) {
			$responce->userdata['FactuurTotaal'] =  number_format($row_total->SUMFactuurTotaal, 2, ',', '.');
			$responce->userdata['Voorschot'] =  number_format($row_total->SUMVoorschot, 2, ',', '.');
			$responce->userdata['BorgZZP'] =  number_format($row_total->SUMBorgZZP, 2, ',', '.');
			$responce->userdata['Resteert'] =  number_format($row_total->SUMResteert, 2, ',', '.');
			$responce->userdata['kosten'] =  number_format($row_total->totaal_kosten, 2, ',', '.');
			break;  //kan maar 1 record zijn
		}
		
		echo json_encode($responce);
		
	}


	function index() {
		$page_num = $this->uri->segment ( 3 );
		if ($page_num == null) {
			$page_num = 0;
		}
		
		//kan wel weg?
		$moderated_list = $this->uri->segment ( 4 );

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$deelnemer = $_SESSION ['deelnemer'];

		$werknemer_id = $_SESSION ['user_id'];
		
		
		echo "1" + $filter_jaartal;
		echo "2" + $deelnemer;
		echo "3" + $werknemer_id;
		
		
		$data ["moderated"] = FALSE;  //moderated_list?? raar		
		$data ["page_number"] = $page_num;
		$this->load->model ( 'pdf_model' );
		$this->load->model ( 'users_model' );
		
		//per 20130807  Luuk zegt, doe conditie maar weg.
		//$data["btw_tonen"] = false;
		//$data["btw_tonen"] = $this->users_model->get_btw_welofniet($werknemer_id);
		$data["btw_tonen"] = true;
		
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'index.php/pdf/index/';
		$config ['total_rows'] = $this->pdf_model->get_pdf_count ($werknemer_id, $filter_jaartal);
		$config ['per_page'] = '15';  //paginering instelling
		$config ['full_tag_open'] = '<p>';
		$config ['full_tag_close'] = '</p>';
		$config ['first_link'] = '&lsaquo; Eerste';
		$config ['last_link'] = 'Laatste &rsaquo;';
		$this->pagination->initialize ( $config );
		$data ['jaartal_filtered'] = $filter_jaartal;
		$data ['kop'] = "Factuur Overzicht";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_index" );
		$data ["company_name"] = $_SESSION ['company'];
		$data ['results'] = $this->pdf_model->get_pdf ( $config ['per_page'], $page_num /*$this->uri->segment ( 3 )*/, $werknemer_id, $filter_jaartal);
		$data ['uren_in_minuten_totaal'] = $this->pdf_model->get_uren_totaal_in_minuten($werknemer_id, $filter_jaartal);
		$data ['result_total'] = $this->pdf_model->get_pdf_totaal ($werknemer_id, $filter_jaartal);		
		$data ['paginate_links'] = $this->pagination->create_links ();
		//erbij voor nieuwe download uit c:/pdf pdf's
		$data ['werknemer_id'] = $werknemer_id;
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_list', $data );
	}

	function pdfpopupweek() {
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];

		$this->load->model ( 'pdf_model' );
		$data ['kop'] = "Factuur Overzicht Week";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_pdfpopupweek" );
		$data ["company_name"] = $_SESSION ['company'];
		//$data ["result"] = $this->pdf_model->get_week_totaal_uren($werknemer_id, $filter_jaartal);
		//$data ['uren_in_minuten_totaal'] = $this->pdf_model->get_uren_totaal_in_minuten($werknemer_id, $filter_jaartal);		

		$data ["result"] = $this->pdf_model->get_week_totaal_uren2($werknemer_id, $filter_jaartal);
		$data ['uren_in_minuten_totaal'] = $this->pdf_model->get_uren_totaal_in_minuten2($werknemer_id, $filter_jaartal);		
		
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_popup_week', $data );
	}

	
	function getpdf() {
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$this->load->helper('download');
		$requested_pdf_id = $this->uri->segment ( 3 );
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		$data["pdf_id"] = $requested_pdf_id;
		$data["deelnemer"] = $deelnemer;
		$data["werknemer_id"] = $werknemer_id;		 
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_getpdf" );
		$data ["company_name"] = $_SESSION ['company'];
		$pdf_content_temp = $this->pdf_model->get_pdf_blob_filename($werknemer_id, $requested_pdf_id, $filter_jaartal);
		$data["pdf_temp"] = $pdf_content_temp;
		//!!! NEE!!  dat is een oude link.		redirect ( "http://adz.iq-zzp.nl/pdftemp/$pdf_content_temp" , "refresh");
	}

	function getpdfjson() {  
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');

		$requested_pdf_id = $this->input->post ( 'req_pdf_id' ); 
		$requested_factuurADZ = $this->input->post ( 'req_factuurADZ' );
		$requested_pdf_soort  = $this->input->post ( 'req_adz' );

		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];
		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		if ($requested_pdf_soort==0) {
			$pdf_content_temp = $this->pdf_model->get_pdf_blob_filename($werknemer_id, $requested_pdf_id, $filter_jaartal);
		} else {
			$pdf_content_temp = $this->pdf_model->get_pdf_blob_filename_adz($werknemer_id, $requested_factuurADZ, $filter_jaartal);			
		}
		$pdfx["pdf"] = $pdf_content_temp;
		$pdfx["errorr"] = "succes";
		echo json_encode( $pdfx  );
	}
	


	function getpdfdownloadjson() {  
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');

		$werknemer_id = $this->input->post ( 'req_werknemer_id' );
		$filter_jaartal = $this->input->post ( 'req_jaartal' );

		$pdf_content_temp = $this->pdf_model->get_pdf_blob_download_filename($werknemer_id, $filter_jaartal);

		//$pdf_content_temp = "test";
		
		$pdfx["pdf"] = $pdf_content_temp;
		$pdfx["errorr"] = "succes";
		echo json_encode( $pdfx  );
	}

	
	function pdf_factuur_d() {
		header('Content-Type: application/json');
		echo json_encode(array('pdf_filename' => $tmp_pdf_filename));
	}

	function pdf_factuurlijst_d() {
		header('Content-Type: application/json');
		echo json_encode(array('pdf_filename' => $tmp_pdf_filename));
	}

	
	function pdfloadtest()
	{
		$path = "dit is een test";
		header('Content-Type: application/json');
		echo $path;
	}
	//
	//FONSCL  NEWLOADPDF
	function file_bestaat() {
		header('Content-Type: application/json');
		$file = isset($_GET['file']) ? $_GET['file'] : '';
		echo json_encode(array('exists' => file_exists($file), 'file' => $file));
	}
	function pdfloadnew()
	{
		$pdf_filename = $_GET["pdf"];
       	$werknemer_id = $_GET["werknemer_id"];
        $jaar = $_GET["jaar"];
		$path = "C:/pdf/" . $jaar . "//" . $werknemer_id . "//" . $pdf_filename;
		header('Content-Length: '.filesize($path)); 
		header("Content-type: application/pdf");
//		header("Content-Disposition: attachment; filename=factuur_" . date("Ymd-His") . ".pdf");
		header("Content-Disposition: attachment; filename=" . $pdf_filename);
		readfile($path);
	}
	
	function pdfloadnewlijst()
	{
		$pdf_filename = $_GET["pdf"];
       	$werknemer_id = $_GET["werknemer_id"];
        $jaar = $_GET["jaar"];
		$pdf_filename = "Facturen " . $jaar . ".pdf";
		$path = "C:/pdf/" . $jaar . "//" . $werknemer_id . "//" . $pdf_filename;
		header('Content-Length: '.filesize($path)); 
		header("Content-type: application/pdf");
		//header("Content-Disposition: attachment; filename=factuur_" . date("Ymd-His") . ".pdf");
		header("Content-Disposition: attachment; filename=" .$pdf_filename);
		readfile($path);
	}
	
	function pdfload() {
		defined('PDFTEMP_PATH') || define('PDFTEMP_PATH', realpath(dirname(__FILE__) . '/../../pdftemp'));		
		$pdf_filename = $_GET["pdf"];
		$path = PDFTEMP_PATH . "/" . $pdf_filename;
		header('Content-Length: '.filesize($path)); 
		header("Content-type: application/pdf");
		header("Content-Disposition: attachment; filename=factuur_" . date("Ymd-His") . ".pdf");
		readfile($path); 
		//unlink ($path);  //delete...  
	}
	

	
	function fiatpopup_week()
	{
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$this->load->helper('uren');
		
		$requested_werkbrief_id = $this->uri->segment ( 3 );
		if ($requested_werkbrief_id === false) {
			$requested_werkbrief_id = "-1";
		}

		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];		

		$pdf_factuur_details_week = $this->pdf_model->get_fiat_details_week($werknemer_id, $requested_werkbrief_id, 'zzp');  //filterzzp
		
		$rows = $pdf_factuur_details_week->result ();
		$aantal_rows = $pdf_factuur_details_week->num_rows();				
		$data['aantal_rows'] = $aantal_rows;
		$pdf_factuur_details_week_dagen = $this->pdf_model->get_fiat_details_week_dagen($werknemer_id, $requested_werkbrief_id, 'zzp');  //filterzzp

		//kmdeclareren
		//$data["toon_km_declareren"] = false;
		//$data["toon_km_declareren"] = true;
		
		$data["factuur_details_week"] = $pdf_factuur_details_week;
		$data["factuur_details_week_dagen"] = $pdf_factuur_details_week_dagen;
		$this->load->view ( 'fiat_popup_week', $data );

	}
	function fiatpopup()
	{
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$this->load->helper('uren');
		
		$requested_uren_id = $this->uri->segment ( 3 );
		if ($requested_uren_id === false) {
			$requested_uren_id = "-1";
		}
		$uren_id = $requested_uren_id;
		
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];		

		$pdf_factuur_details_week = $this->pdf_model->get_fiat_details_week($werknemer_id, $uren_id, 'zzp');  //filterzzp

		$data["factuur_details_week"] = $pdf_factuur_details_week;
		$this->load->view ( 'fiat_popup', $data );

	}

	function oud_isperweek_fiatpopup()
	{
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$this->load->helper('uren');
		
		$requested_uren_id = $this->uri->segment ( 3 );
		if ($requested_uren_id === false) {
			$requested_uren_id = "-1";
		}
		$uren_id = $requested_uren_id;
		
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];		

		$pdf_factuur_details = $this->pdf_model->get_fiat_details($werknemer_id, $uren_id);
		//$pdf_factuur_details = $this->pdf_model->get_fiat_details($werknemer_id, -1); //test  - geen gegevens gevonden melding in de popup.
		$data["factuur_details"] = $pdf_factuur_details;
		$this->load->view ( 'fiat_popup', $data );

	}
	
	function pdfpopup() {  
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$requested_factuurnr = $this->uri->segment ( 3 );
		if ($requested_factuurnr === false) {
			$requested_factuurnr = "-1";
		}
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];		
		$factuurnr = $requested_factuurnr;  //test!

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
	
		
		$pdf_factuur_details2 = $this->pdf_model->get_pdf_details2($werknemer_id, $factuurnr, $filter_jaartal);
		$data["factuur_details2"] = $pdf_factuur_details2;
		
		$data["totaal"] = 4771.45;
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_popup', $data );
	}

	
	function pdfpopupfactuuropmerkingen() {  
		$this->load->model ( 'pdf_model' );	
		$this->load->helper('url');
		$requested_factuurnr = $this->uri->segment ( 3 );
		$requested_contactmoment_id = $this->uri->segment ( 4 );
		if ($requested_factuurnr === false) {
			$requested_factuurnr = "-1";
		}
		$deelnemer = $_SESSION ['deelnemer'];
		$werknemer_id = $_SESSION ['user_id'];		
		$factuurid = $requested_factuurnr;  //test!

		if (! isset($_SESSION ['filter_jaartal'])) {
			$_SESSION ['filter_jaartal'] = date("Y");
		}
	
		$filter_jaartal = $_SESSION ['filter_jaartal'];
		
		$factuur_opmerking_resultaat = $this->pdf_model->get_factuur_opmerkingen($factuurid, $requested_contactmoment_id);
		$data["factuur_opmerkingen"] = $factuur_opmerking_resultaat;
	
		$this->load->helper ( 'uren' );
		$this->load->view ( 'pdf_popup_factuur_opmerkingen', $data );
	}

	function factuuropmerking_post()
	{
		$this->load->model ( 'zzp_model' );	
		$this->load->helper('url');
		$req_data=json_decode(file_get_contents('php://input'));
		$factuurid = $req_data->factuurid;
		$factuur_opmerking_resultaat = $this->zzp_model->get_factuur_opmerkingen($factuurid);
		$response = array('success' => true, 'rows' => $factuur_opmerking_resultaat);
		$this->response($response);	
	}

	function helppage() {
		$data["help"] = "Help pagina";
		$data ["user_role_pdf"] = $this->zacl->check_acl ( "pdf_getpdf" );		
		$this->load->view ( 'pdf_helppage', $data );
	}
	
}