<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WxUser_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function registerWxUser($WxData) {
		
		$data = array('unique_id' => $unique_id, 
					  'name' => $name, 
					  'email' => $email,
					  'encrypted_password' => $encrypted_password, 
					  'salt' => $salt);
		$query = $this->db->insert('users', $data);

		if ($query) {
			return true;
		}
		else {
			return false;
		}
	}
}