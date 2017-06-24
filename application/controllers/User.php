<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('email');
	}

	public function regiserUser($name, $email, $password) {

		if (!empty($name) && !empty($email) && !empty(password)) {
			if ($this->User_model->checkUserExist($email)) {
				$response["result"] = "failure";
				$response["message"] = "邮箱已被注册！";
				return json_encode($response);
			}
			else {
				$result = $this->User_model->insertData($name, $email, $password);

				if ($result) {
					$response["result"] = "success";
					$response["message"] = "用户注册成功！";
					return json_encode($response);
				}
				else {
				$response["result"] = "failure";
				$response["message"] = "注册失败，请检查你的网络";
				return json_encode($response);
				}
			}
		}
		else {
			return $this->getMsgParamNotEmpty();
		}
	}

	public function loginUser($email, $password) {

		if (!empty($email) && !empty($password)) {
			if ($this->User_model->checkUserExist($email)) {
				$result = $this->User_model->checkLogin($email, $password);

				if ($result) {
					$response["result"] = "failure";
					$response["message"] = "用户名或密码不正确";
					return json_encode($response);
				}
				else {
					$response["result"] = "success";
					$response["message"] = "登录成功";
					$response["user"] = $result;
					return json_encode($response);
				}
			}
			else {
				$response["result"] = "failure";
				$response["message"] = "用户不存在";
				return json_encode($response);
			}
		}
		else {
			return $this->getMsgParamNotEmpty();
		}
	}

	public function changePassword($email, $old_password, $new_password, $new_password_verify) {

		if (!empty($email) && !empty($old_password) && !empty($new_password) && $new_password_verify == new_password) {
			if (!$this->User_model->checkLogin($email, $old_password)) {
				$response["result"] = "failure";
				$response["message"] = "用户名或密码不正确";
				return json_encode($response);
			}
			else {
				$result = $this->User_model->changePassword($email, $new_password);

				if ($result) {
					$response["reslut"] = "success";
					$response["message"] = "修改密码成功";
					return json_encode($response);
				}
				else {
					$response["result"];
					$response["message"] = "修改密码失败，请检查你的网络";
					return json_encode($response);
				}
			}
		}
		else {
			return $this->getMsgParamNotEmpty();
		}
	}

	public function resetPasswordRequest($email) {

		if ($this->User_model->checkUserExist($email)) {
			$result = $this->User_model->passwordResetRequest($email);

			if (!$result) {
				$response["result"] = "failure";
				$response["message"] = "找回密码失败";
				return json_encode($response);
			}
			else {
				$mail_result = $this->sendEmail($result["email"], $result["temp_password"]);

				if ($mail_result) {
					$response["result"] = "success";
					$response["message"] = "请检查你的邮箱验证码是否正确";
					return json_encode($response);
				}
				else {
					$response["result"] = "failure";
					$response["message"] = "找回密码失败，请检查你的网络";
					return json_encode($response);
				}
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = "邮箱不存在";
			return json_encode($response);
		}
	}

	public function resetPassword($email, $password, $code) {

		if ($this->User_model->checkUserExist($email)) {
			$result = $this->User_model->resetPassword($email, $password, $code);

			if (!result) {
				$response["result"] = "failure";
				$response["message"] = "找回密码失败";
				return json_encode($response);
			}
			else {
				$response["result"] = "success";
				$response["message"] = "修改密码失败，请检查你的网络";
				return json_encode($response);
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = "邮箱不存在";
			return json_encode($response);
		}
	}

	public function sendEmail($email, $temp_password) {

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.163.com',
			'smtp_user' => 'ptj_server@163.com',
			'smtp_pass' =>'kelele67',
			'smtp_crypto' => 'ssl',
			'smtp_port' => 456,
			'wrapchars' => 50,
			'mailtype' => 'html'
			);

		$this->load->library('email', $config);

		$this->email->from('ptj_server@163.com', 'ptj_server');
		$this->email->reply_to('ptj_server@163.com', 'ptj_server');
		$this->email->to($emial);

		$this->email->subject('Password Reset Request');
		$this->email->message('Hi,<br><br> Your password reset code is <b>'.$temp_password.'</b> . This code expires in 120 seconds. Enter this code within 120 seconds to reset your password.');

		if ($this->email->send()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isEmailValid($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function getMsgParamNotEmpty() {
		$response["result"] = "failure";
		$response["message"] = "输入不能为空！";
		return json_encode($response);
	}

	public function getMsgInvalidParam() {
		$response["result"] = "failure";
		$response["message"] = "格式不正确！";
		return json_encode($response);
	}

	public function getMsgInvalidEmail() {
		$response["result"] = "failure";
		$response["message"] = "邮箱不存在！";
		return json_encode($response);
	}

// public function user_management() {
// 	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// 		$data = json_decode(file_get_contents("php://input"));
// 		if (isset($data->operation)) {
// 			$operation = $data->operation;
// 			if (!empty($operation)) {
// 				if($operation == 'register') {
// 					if(isset($data->user) && !empty($data->user) && isset($data->user->name) && isset($data->user->email) && isset($data->user->password)) {
// 						$user = $data->user;
// 						$name = $user->name;
// 						$email = $user->email;
// 						$password = $user->password;
// 						if ($this->User_model->isEmailValid($email)) {
// 							echo $this->User_model->registerUser($name, $email, $password);
// 						} 
// 						else {
// 							echo $this->User_model->getMsgInvalidEmail();
// 						}
// 					} 
// 					else {
// 						echo $this->User_model->getMsgInvalidParam();
// 					}
// 				}
// 				else if ($operation == 'login') {
// 					if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password)) {
// 						$user = $data->user;
// 						$email = $user->email;
// 						$password = $user->password;
// 						echo $this->User_model->loginUser($email, $password);
// 					} 
// 					else {
// 						echo $this->User_model->getMsgInvalidParam();
// 					}
// 				} 
// 				else if ($operation == 'chgPass') {
// 					if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->old_password) && isset($data->user->new_password)) {
// 						$user = $data->user;
// 						$email = $user->email;
// 						$old_password = $user->old_password;
// 						$new_password = $user->new_password;
// 						echo $this->User_model->changePassword($email, $old_password, $new_password);
// 					} 
// 					else {
// 						echo $this->User_model->getMsgInvalidParam();
// 					}
// 				}
// 				else if ($operation == 'resPassReq') {
// 					if(isset($data->user) && !empty($data->user) &&isset($data->user->email)){
// 						$user = $data->user;
// 						$email = $user->email;
// 						echo $this->User_model->resetPasswordRequest($email);
// 					} 
// 					else {
// 						echo $this->User_model->getMsgInvalidParam();
// 					}
// 				}
// 				else if ($operation == 'resPass') {
// 					if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password) && isset($data->user->code)) {
// 						$user = $data->user;
// 						$email = $user->email;
// 						$code = $user->code;
// 						$password = $user->password;
// 						echo $this->User_model->resetPassword($email,$code,$password);
// 					} 
// 					else {
// 						echo $this->User_model->getMsgInvalidParam();
// 					}
// 				}
// 			}
// 			else {		
// 				echo $this->User_model->getMsgParamNotEmpty();
// 			}
// 		} 
// 		else {
// 			echo $this->User_model->getMsgInvalidParam();
// 		}
// 	} 
// 	else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
// 		echo "撮虾子";
// 	}
// }
}
