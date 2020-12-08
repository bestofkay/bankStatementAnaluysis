<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'vendor/autoload.php';
//require_once 'vendor/autoload.php';
class Test extends CI_Controller
{

    public function __construct(){
       // $this->api = & get_instance();
        $this->api->load->helper('path');
       // $this->api->load->helper('simple_html_dom_helper');
    }

	public function index()
	{ 
		//$this->load->helper('path');
		$count=0;
		ini_set('memory_limit', '-1');

		$htm_pages='';
		$font =  '/var/www/html/app/arial.ttf';
		//$pd='/var/www/html/app/statement.pdf';
		$pd='/home/creditclan/analytics/public/upload/CC_statement_analytics1560862117758.pdf';
		$fontsize = 6;
		

		//$this->load->helper('simple_html_dom');
		// initiate
		$pdf = new Gufy\PdfToHtml\Pdf($pd);

		// check if your pdf has more than one pages
		$total_pages = $pdf->getPages();

		for ($page = 1; $page <= $total_pages; $page++)
		{
		echo $pdf->html($page);
		}


}

}
