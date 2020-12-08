<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Bcrypt $bcrypt The Bcrypt library
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Bank_model extends CI_Model
{
	public function update_extraction($data, $id){
        $this->db->where('transaction_id', $id);
        $this->db->update('extraction_transactions', $data);
	}
	
	public function update_time_extraction($data, $id){
        $this->db->where('transaction_id', $id);
        $this->db->update('process_times', $data);
    }
   

}