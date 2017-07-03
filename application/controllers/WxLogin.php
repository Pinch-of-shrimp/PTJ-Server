<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WxLogin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// $this->load->model('User_model');
		$this->load->model('WxLogin_model');
		$this->load->library('wx_library', null, 'wx');
	}

	public function registerWxUser() {
		$WxUser = $this->wx->getWxUser();
		$WxData = array(
			'unique_id' => $WxUser->openid,
			'name' => $WxUser->nickname,
			'sex' => $WxUser->sex,
			'province' => $WxUser->province,
			'city' => $WxUser->city,
			'picture' => $WxUser->headimgurl
			);
		$result = $WxLogin_model->registerWxUser($WxData);
		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("微信登录成功");
			$response["wxUser"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("微信登录失败，请检查你的网络");
			return urldecode(json_encode($response));
		}
	}
}

$fun = new WxLogin();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// $data = json_decode(file_get_contents("php://input"));
	// if (isset($data->operation)) {
	// 	$operation = $data->operation;
	// 	if (!empty($operation)) {
	// 		if ($operation == 'registerWxUser') {
				$fun->registerWxUser();
	// 		}
	// 	}
	// }
}
// else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
// 	echo "微信登录";
// 	exit;
// }
