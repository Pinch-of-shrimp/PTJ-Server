<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WxUser extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model',);
		$this->load->model('WxUser_model');
		$this->load->library('Wx_library');
	}

	public function registerWxUser() {
		$WxUser = $this->Wx_library->getWxUser();
		$WxData = array(
			'unique_id' => $WxUser->openid,
			'name' => $WxUser->nickname,
			'sex' => $WxUser->sex,
			'province' => $WxUser->province,
			'city' = $WxUser->city,
			'avatar' = $WxUser->headimgurl
			);
		$result = $ThreeLogin_model->registerWxUser($WxData);
		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("微信登录成功");
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("微信登录失败，请检查你的网络");
			return urldecode(json_encode($response));
		}
	}
}
