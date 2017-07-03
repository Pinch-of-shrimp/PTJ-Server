<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WxUser_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function registerWxUser($WxData) {
		$result = array();
		$query = $this->db->insert('users', $WxData);
		if ($query) {
			foreach ($query->result() as $row) {
				$wxUser["id"] = $row->unique_id;
				$wxUser["name"] = $row->name;
				array_push($result, $wxUser);
			}
			return $result;
		}
		else {
			return false;
		}
	}
}