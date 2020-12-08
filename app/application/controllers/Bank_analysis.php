<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bank_analysis extends CI_Controller {
  
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index($id)
    {
        $ext=$this->db->query("select analytics_data from extraction_transactions where transaction_id='".$id."'")->row_result();
        $this->load->view("template", $ext);        
    }
}

?>