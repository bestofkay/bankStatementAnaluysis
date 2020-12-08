<?php
//error_reporting(1);
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Welcome extends REST_Controller {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
      
		$this->methods['slip_post']['limit'] = 100; // 500 requests per hour per user/key
	    $this->load->model(array('bank_model'));
        $this->load->helper('path');
        $this->load->database();
		$this->load->library('bank_statement');
    }

    public function index_post()
    {
      
        $error=false;
        $url=$this->post('url');
        $transaction=$this->post('transaction_id');
      if(!isset($url) || empty($url)){
          $url_message='url not found';
          $error=true;
      }else{
        $url_message=$url;  
      }

      if(!isset($transaction) || empty($transaction)){
        $tran_message='transaction id not found';
        $error=true;
      }else{
        $tran_message=$transaction;
      }  

      if($error){
        $message = [
            'transaction_id' => $tran_message,
            'url'=> $url_message,
            'status'=> 'error',
        ];
        $this->response($message, REST_Controller::HTTP_BAD_REQUEST);  
      }else{
    
       $file_name = '/var/www/html/app/statement.pdf';
        
		file_put_contents($file_name, file_get_contents($url));
		
        $j=$this->bank_statement->get();
       
       //$json = json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
      // $json = str_replace('\\n', '', $json);
      // $json = str_replace('\\', '', $json);
       $json =json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
     
       $message = [
        'transaction_id' => $transaction,
        'action'=> 'alternate_extraction',
        'status'=> 'completed',
    ];

   $day=date('Y-m-d h:i:s');
  
    $this->db->query("update extraction_transactions set alternate_extraction_data='".$json."', status='extraction' where transaction_id='".$transaction."'");
    $this->db->query("update process_times set alternate_extraction_end='".$day."' where transaction_id='".$transaction."'");
   
   $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code    
}

    }

}