// filepath: application/controllers/test.php
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Laad de database library
        $this->load->database();
    }

    public function index() {
        // Probeer een simpele query uit te voeren
        try {
            $query = $this->db->query("SELECT naam FROM users");
            
            // Haal alle resultaten op
            $results = $query->result();
            
            // Geef data door aan de view
            $data['results'] = $results;
            $data['error'] = null;
            
        } catch (Exception $e) {
            // Als er een fout optreedt, geef die door aan de view
            $data['results'] = array();
            $data['error'] = $e->getMessage();
        }
        
        // Laad de view met de data
        $this->load->view('test_view', $data);
    }
}
