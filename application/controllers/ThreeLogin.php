<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ThreeLogin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model',);
		$this->load->model('ThreeLogin_model');
		$this->load->library('Wx_library', null, 'wx');
	}

	public function registerWxUser() {
		// $WxUser = $this->wx->getWxUser();
		$name = $ThreeLogin_model->registerWxUser();

	}
}

$fun = new ThreeLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->search)) {
		$search = $data->search;
		if (!empty($search)) {
			if ($search == 'registerWxUser') {
				if(isset($data->city) && !empty($data->city)) {
					$city = $data->city;
					echo $fun->hotJob($city);
					exit;
				}
			}

			else if ($search == 'nearJob') {
				if(isset($data->city) && !empty($data->city) && isset($data->district) && !empty($data->district)) {
					$city = $data->city;
					$district = $data->district;
					echo $fun->nearbyJob($city, $district);
					exit;
				}
			}

			else if ($search == 'weekendJob') {
				if(isset($data->city) && !empty($data->city) && isset($data->district) && !empty($data->district)) {
					$city = $data->city;
					$city = $data->district;
					echo $fun->weekendJob($city, $district);
					exit;
			}

			else if ($search == 'recommendJob') {
				if(isset($data->city) && !empty($data->city) && isset($data->district) && !empty($data->district)) {
					$city = $data->city;
					$city = $data->district;
					echo $fun->recommendJob($city, $district);
					exit;	
			}
		}
	}
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "主页";
	exit;
}