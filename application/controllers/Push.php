<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('JPush');
	}

	public function single_notification() {

		$this->JPush->single_push('you are the one', 1141, 3, 1);
		// $this->JPush->single_push($content, $uid, $type, $id);
	}

	public function group_notification() {

		$this->JPush->group_push('hello world', 1, '10000');
	}

$fun = new Push();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {
			if ($operation == 'SingleNoti') {
				// if(isset($data->user) && !empty($data->user)) {
				// 	$user = $data->user;
					echo $fun->single_notification($user);
				}
			}
		}
	}
				// }

}