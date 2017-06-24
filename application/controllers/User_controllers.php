<?php
class User extends CI_Controller {

// private $db;
// private $mail;

public function __construct() {
	parent::__construct();
	$this->load->model('User_model');
	$this->load->library('email');
}

public function regiserUser($name, $email, $password) {
	$db = $this->User_model->db;

	if (!empty($name) && !empty($email) && !empty(password)) {
		if ($db->checkUserExist($email)) {
			$response["result"] = "failure";
			$response["message"] = "邮箱已被注册！";
			return json_encode($response);
		}
		else {
			$result = $db->insertData($name, $email, $password);

			if ($result) {
				$response["result"] = "success"
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
	$db = $this->User_model->db;

	if (!empty($email) && !empty($password)) {
		if ($db->checkUserExist($email)) {
			$result = $db->checkLogin($email, $password);

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
	$db = $this->User_model->db;

	if (!empty($email) && !empty($old_password) && !empty($new_password) && $new_password_verify == new_password) {
		if (!$db->checkLogin($email, $old_password)) {
			$response["result"] = "failure";
			$response["message"] = "用户名或密码不正确";
			return json_encode($response);
		}
		else {
			$result = $db->changePassword($email, $new_password);

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
	$db = $this->User_model->db;

	if ($db->checkUserExist($email)) {
		$result = $db->passwordResetRequest($email);

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
	$db = $this->User_model->db;

	if ($db->checkUserExist($email)) {
		$result = $db->resetPassword($email, $password, $code);

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
	$mail = $this->email;

	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'smtp.163.com';
	$config['smtp_user'] = 'ptj_server@163.com';
	$config['smtp_pass'] = 'ptj_server67';
	$config['smtp_crypto'] = 'ssl';
	$config['smtp_port'] = 456;
	$config['wrapchars'] = 50;
	$config['mailtype'] = 'html';

	mail->initialize($config);


	$mail->from('ptj_server@163.com', 'ptj_server');
	$mail->reply_to('ptj_server@163.com', 'ptj_server');
	$mail->to($emial);

	$mail->subject('Password Reset Request');
	$mail->message('Hi,<br><br> Your password reset code is <b>'.$temp_password.'</b> . This code expires in 120 seconds. Enter this code within 120 seconds to reset your password.')

	if (!mail->send()) {
		return $mail->errno;
	}
	else {
		return true;
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
}