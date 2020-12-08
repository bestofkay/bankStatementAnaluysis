<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bank_analysis extends CI_Controller {
  
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function analysis_pdf($id)
    {
        echo 'quick'; exit;
        $ext=$this->db->query("select analytics_data from extraction_transactions where unique_id='".$id."'")->row_array();
        $this->load->view("template", $ext);        
    }

    public function analysis_preview($id)
    {
        $ext=$this->db->query("select analytics_data from extraction_transactions where preview_unique_id='".$id."'")->row_array();
        $this->load->view("preview", $ext);        
    }
}

?>