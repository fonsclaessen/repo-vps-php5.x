<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Zacl
{
    function __construct()
    {
        session_start();
        //Append Zend's folder in PHP's include path
//fons!!! was zo        set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . "application/Custom");
 //echo "  Path: " . get_include_path();
// echo "  Path: " . PATH_SEPARATOR . BASEPATH;
        set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . "/libraries/Custom");        
   //      echo "  Path: " . get_include_path();
     //    die();
         
         //Load the Acl class
        require_once 'Zend/Acl.php';
        require_once 'Zend/Acl/Role.php';
        require_once 'Zend/Acl/Resource.php';
 
        //Create a new Acl object
        $this->acl = new Zend_Acl();
 
        /**
         * Add roles and resources. Check Zend's documentation for excellent
         * information on all these.
         * http://framework.zend.com/manual/en/zend.acl.html
         */
        $this->acl->addRole(new Zend_Acl_Role('guest'));
        $this->acl->addRole(new Zend_Acl_Role('user'),array('guest'));
        $this->acl->addRole(new Zend_Acl_Role('editor'),array('user'));
        
        $this->acl->addRole(new Zend_Acl_Role('administrator'),array('user', 'editor')); //was user
 
        /**
         * Add some resources
         */
        $this->acl->addResource(new Zend_Acl_Resource('users_login'));
        $this->acl->addResource(new Zend_Acl_Resource('users_logout'));        
        $this->acl->addResource(new Zend_Acl_Resource('users_profile'));
        $this->acl->addResource(new Zend_Acl_Resource('users_index'));
		$this->acl->addResource(new Zend_Acl_Resource('users_indexcharts'));		
        $this->acl->addResource(new Zend_Acl_Resource('users_adminselect'));
		$this->acl->addResource(new Zend_Acl_Resource('users_terugnaarkeuzemenu'));
                
        $this->acl->addResource(new Zend_Acl_Resource('prices_index'));
        $this->acl->addResource(new Zend_Acl_Resource('prices_edit'));        
        $this->acl->addResource(new Zend_Acl_Resource('prices_save'));
        $this->acl->addResource(new Zend_Acl_Resource('prices_do_upload'));
        
        $this->acl->addResource(new Zend_Acl_Resource('texts_index'));
        $this->acl->addResource(new Zend_Acl_Resource('texts_moderate'));
        $this->acl->addResource(new Zend_Acl_Resource('texts_edit'));
        $this->acl->addResource(new Zend_Acl_Resource('texts_add'));
		$this->acl->addResource(new Zend_Acl_Resource('texts_save'));
		$this->acl->addResource(new Zend_Acl_Resource('texts_savenew'));
		$this->acl->addResource(new Zend_Acl_Resource('texts_delete'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_index'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_newtext'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_winner'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_scan_qr'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_scan'));        
        $this->acl->addResource(new Zend_Acl_Resource('cms_pricecollect'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_dank'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_message'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_avfooter'));

        $this->acl->addResource(new Zend_Acl_Resource('cms_newtext_2'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_scan_2'));        
        $this->acl->addResource(new Zend_Acl_Resource('cms_scan_qr_2'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_dank_2'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_message_2'));
        $this->acl->addResource(new Zend_Acl_Resource('cms_landingspage'));
        
        $this->acl->addResource(new Zend_Acl_Resource('pdf_index'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_index_grid'));  //om een andere grid in te kunnen bouwen
        $this->acl->addResource(new Zend_Acl_Resource('pdf_getpdf'));        
        $this->acl->addResource(new Zend_Acl_Resource('pdf_getpdfjson'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_getpdfdownloadjson'));

		$this->acl->addResource(new Zend_Acl_Resource('pdf_getadministratorjson'));

		$this->acl->addResource(new Zend_Acl_Resource('pdf_getfacturenjson'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_getfacturenjson_oud'));
        $this->acl->addResource(new Zend_Acl_Resource('pdf_pdfload'));        

        $this->acl->addResource(new Zend_Acl_Resource('pdf_pdfloadnew'));
        $this->acl->addResource(new Zend_Acl_Resource('pdf_pdfloadnewlijst'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_file_bestaat'));

		$this->acl->addResource(new Zend_Acl_Resource('pdf_factuur_d'));        
		$this->acl->addResource(new Zend_Acl_Resource('pdf_factuurlijst_d'));        
		
        $this->acl->addResource(new Zend_Acl_Resource('pdf_pdfpopup'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_helppage'));        
		$this->acl->addResource(new Zend_Acl_Resource('pdf_pdfpopupweek'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_prevyear'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_nextyear'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_prevyear_grid'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_nextyear_grid'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_fiatteren'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_fiatpopup'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_fiatpopup_week'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_ajaxsavefiatpopup'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_ajaxsavefiatpopup_week'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_ajaxsavefiatpopup_weekjson'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_ajaxcheckboxfiatjson'));
		$this->acl->addResource(new Zend_Acl_Resource('pdf_pdfpopupfactuuropmerkingen'));
		

				
		/**
         * Set rules for Acl
         */
		
        $this->acl->deny(); //Deny everything, so as to follow a whitelist approach.
        $this->acl->allow('guest','users_login');
        $this->acl->allow('guest','users_logout');
        $this->acl->allow('guest','cms_index');  //home page deelnemer
        $this->acl->allow('guest','cms_newtext');
        $this->acl->allow('guest','cms_winner');
        $this->acl->allow('guest','cms_scan_qr');
        $this->acl->allow('guest','cms_scan');        
		$this->acl->allow('guest','cms_pricecollect');        
        $this->acl->allow('guest','cms_dank');        
        $this->acl->allow('guest','cms_message');        
        $this->acl->allow('guest','cms_avfooter');        
        
        $this->acl->allow('guest','cms_scan_2');        
        $this->acl->allow('guest','cms_scan_qr_2');        
        $this->acl->allow('guest','cms_message_2');        
        $this->acl->allow('guest','cms_dank_2');        
        $this->acl->allow('guest','cms_newtext_2');
        $this->acl->allow('guest','cms_landingspage');
        
        
        $this->acl->allow('user','users_profile');
        $this->acl->allow('user','users_index');
		$this->acl->allow('user','users_indexcharts');
        $this->acl->allow('user','prices_index');
		$this->acl->allow('user','users_terugnaarkeuzemenu');
        
       	$this->acl->allow('user','prices_edit');
		$this->acl->allow('user','prices_save');
		$this->acl->allow('user','prices_do_upload');
		
		$this->acl->allow('user','pdf_index');
		$this->acl->allow('user','pdf_index_grid');  //om een andere grid in te kunnen bouwen.
		$this->acl->allow('user','pdf_getpdf');
		$this->acl->allow('user','pdf_getpdfjson');
		$this->acl->allow('user','pdf_getpdfdownloadjson');
		
		
		$this->acl->allow('user','pdf_getadministratorjson');
		
		$this->acl->allow('user','pdf_getfacturenjson');
		$this->acl->allow('user','pdf_getfacturenjson_oud');
		$this->acl->allow('user','pdf_pdfload');

		$this->acl->allow('user','pdf_pdfloadnew');
		$this->acl->allow('user','pdf_pdfloadnewlijst');
		$this->acl->allow('user','pdf_file_bestaat');
		
		$this->acl->allow('user','pdf_factuur_d');
		$this->acl->allow('user','pdf_factuurlijst_d');

		$this->acl->allow('user','pdf_pdfpopup');
		$this->acl->allow('user','pdf_helppage');		
		$this->acl->allow('user','pdf_pdfpopupweek');
		$this->acl->allow('user','pdf_nextyear');
		$this->acl->allow('user','pdf_prevyear');
		$this->acl->allow('user','pdf_nextyear_grid');
		$this->acl->allow('user','pdf_prevyear_grid');
		$this->acl->allow('user','pdf_fiatteren');
		$this->acl->allow('user','pdf_fiatpopup');
		$this->acl->allow('user','pdf_fiatpopup_week');
		$this->acl->allow('user','pdf_ajaxsavefiatpopup');
		$this->acl->allow('user','pdf_ajaxsavefiatpopup_week');
		$this->acl->allow('user','pdf_ajaxsavefiatpopup_weekjson');
		$this->acl->allow('user','pdf_ajaxcheckboxfiatjson');
		$this->acl->allow('user','pdf_pdfpopupfactuuropmerkingen');
		
		
		
		//toegevoegd vanwege menu optie Facturen.
       	$this->acl->allow('user','texts_index');
       	$this->acl->allow('user','texts_moderate');
       	$this->acl->allow('user','texts_edit');
       	$this->acl->allow('user','texts_add');
       	$this->acl->allow('user','texts_save');
       	$this->acl->allow('user','texts_savenew');
       	$this->acl->allow('user','texts_delete');       	
		
       	$this->acl->allow('editor','texts_index');
       	$this->acl->allow('editor','texts_moderate');
       	$this->acl->allow('editor','texts_edit');
       	$this->acl->allow('editor','texts_add');
       	$this->acl->allow('editor','texts_save');
       	$this->acl->allow('editor','texts_savenew');
       	$this->acl->allow('editor','texts_delete');       	
       	
       	$this->acl->allow('editor','pdf_index');       	
		$this->acl->allow('editor','pdf_index_grid');       //om een andere grid in te kunnen bouwen.
       	$this->acl->allow('editor','pdf_getpdf');
       	$this->acl->allow('editor','pdf_getpdfjson');
       	$this->acl->allow('editor','pdf_pdfload');

		$this->acl->allow('editor','pdf_pdfloadnew');
		$this->acl->allow('editor','pdf_pdfloadnewlijst');
		$this->acl->allow('editor','pdf_file_bestaat');
		
		$this->acl->allow('editor','pdf_factuur_d');
		$this->acl->allow('editor','pdf_factuurlijst_d');

		$this->acl->allow('editor','pdf_pdfpopup');
		$this->acl->allow('editor','pdf_helppage');
		$this->acl->allow('editor','pdf_pdfpopupweek');
		$this->acl->allow('editor','users_adminselect');
		
		
       	
//        $this->acl->allow('administrator','users_index');
//        $this->acl->allow('administrator','prices_index');
//        $this->acl->allow('administrator','texts_index');
                
    }
 
    function check_acl($resource)
    {
        if (!$this->acl->has($resource))
        {
			return 0;  //fons was 1            
//testje			return 0;  //fons was 1			
        }
        if (isset($_SESSION['logged_in'])) {
			$role = $_SESSION['role'] ;
	    }
	    else {
    		$role = 'guest';	    	
	    }
        return $this->acl->isAllowed($role,$resource);
    }
 
}

//end of file