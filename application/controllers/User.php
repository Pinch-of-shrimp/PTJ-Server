<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('Mailer');
	}

	/**
	 * 注册请求响应
	 * @param  string $email 
	 * @return string(json) 注: HTTP通信中并不存在所谓的json，而是将string转成json罢了        
	 */
	public function registerRequest($email) {

		if ($this->User_model->checkUserExist($email)) {
			$response["result"] = "failure";
			$response["message"] = urlencode("邮箱已被注册");
			return urldecode(json_encode($response));
		}
		else {
			$result = $this->User_model->registerRequest($email);

			if (!$result) {
				$response["result"] = "failure";
				$response["message"] = urlencode("注册用户失败");
				return urldecode(json_encode($response));
			}
			else {
				$mail_result = $this->mailer->sendMail($result["email"], $result["temp_password"], 'Register');

				if ($mail_result) {
					$response["result"] = "success";
					$response["message"] = urlencode("请检查你的邮箱验证码");
					return urldecode(json_encode($response));
				}
				else {
					$response["result"] = "failure";
					$response["message"] = urlencode("注册用户失败，请检查你的网络");
					return urldecode(json_encode($response));
				}
			}
		}
	}

	/**
	 * 注册用户响应
	 * @param  string $name     
	 * @param  string $email    
	 * @param  string $password 
	 * @param  string $code     邮箱收到的验证码
	 * @return string(json)          
	 */
	public function registerUser($name, $email, $password, $code) {

		if (!empty($name) && !empty($email) && !empty($password) && !empty($code)) {
			if ($this->User_model->checkUserExist($email)) {
				$response["result"] = "failure";
				$response["message"] = urlencode("邮箱已被注册");
				return urldecode(json_encode($response));
			}
			else {
				$result = $this->User_model->registerUser($name, $email, $password, $code);

				if (!$result) {
					$response["result"] = "failure";
					$response["message"] = urlencode("注册用户失败");
					return urldecode(json_encode($response));

				}
				else {
				$response["result"] = "success";
				$response["message"] = urlencode("注册成功");
				return urldecode(json_encode($response));
				}
			}
		}
		else {
			return $this->getMsgParamNotEmpty();
		}
	}

	/**
	 * 用户登录响应
	 * @param  string $email    
	 * @param  string $password 
	 * @return string(json)           
	 */
	public function loginUser($email, $password) {

		if (!empty($email) && !empty($password)) {
			if ($this->User_model->checkUserExist($email)) {
				$result = $this->User_model->checkLogin($email, $password);

				if (!$result) {
					$response["result"] = "failure";
					$response["message"] = urlencode("用户名或密码不正确");
					return urldecode(json_encode($response));
				}
				else {
					$response["result"] = "success";
					$response["message"] = urlencode("登录成功");
					$response["user"] = $result;
					return urldecode(json_encode($response));
				}
			}
			else {
				$response["result"] = "failure";
				$response["message"] = urlencode("用户不存在");
				return urldecode(json_encode($response));
			}
		}
		else {
			return $this->getMsgParamNotEmpty();
		}
	}

	/**
	 * 修改密码响应
	 * @param  string $email               
	 * @param  string $old_password        
	 * @param  string $new_password        
	 * @param  string $new_password_verify 
	 * @return string(json)                      
	 */
	public function changePassword($email, $old_password, $new_password, $new_password_verify) {
		if ($new_password_verify == $new_password) {
			if (!empty($email) && !empty($old_password) && !empty($new_password)) {
				if (!$this->User_model->checkLogin($email, $old_password)) {
					$response["result"] = "failure";
					$response["message"] = urlencode("用户名或密码不正确");
					return urldecode(json_encode($response));
				}
				else {
					$result = $this->User_model->changePassword($email, $new_password);

					if ($result) {
						$response["reslut"] = "success";
						$response["message"] = urlencode("修改密码成功");
						return urldecode(json_encode($response));
					}
					else {
						$response["result"] = "failure";
						$response["message"] = urlencode("修改密码失败，请检查你的网络");
						return urldecode(json_encode($response));
					}
				}
			}
			else {
				return $this->getMsgParamNotEmpty();
			}
		}
		else {
			$response["result"] = "failure";;
			$response["message"] = urlencode("两次密码输入不一致");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 重设密码请求响应
	 * @param  string $email 
	 * @return string(json)        
	 */
	public function resetPasswordRequest($email) {

		if ($this->User_model->checkUserExist($email)) {
			$result = $this->User_model->passwordResetRequest($email);

			if (!$result) {
				$response["result"] = "failure";
				$response["message"] = urlencode("找回密码失败");
				return urldecode(json_encode($response));
			}
			else {
				$mail_result = $this->mailer->sendMail($result["email"], $result["temp_password"], 'ResetPas');

				if ($mail_result) {
					$response["result"] = "success";
					$response["message"] = urlencode("请检查你的邮箱验证码");
					return urldecode(json_encode($response));
				}
				else {
					$response["result"] = "failure";
					$response["message"] = urlencode("找回密码失败，请检查你的网络");
					return urldecode(json_encode($response));
				}
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("邮箱不存在");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 重设密码响应
	 * @param  string $email    
	 * @param  string $password 
	 * @param  string $code     邮箱收到的验证码
	 * @return string(json)
	 */
	public function resetPassword($email, $password, $code) {

		if ($this->User_model->checkUserExist($email)) {
			$result = $this->User_model->resetPassword($email, $password, $code);

			if (!$result) {
				$response["result"] = "failure";
				$response["message"] = urlencode("找回密码失败");
				return urldecode(json_encode($response));
			}
			else {
				$response["result"] = "success";
				$response["message"] = urlencode("找回密码成功，请记住你的新密码");
				return urldecode(json_encode($response));
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("邮箱不存在");
			return urldecode(json_encode($response));
		}
	}

	public function feedback($author, $content) {
		$result = $this->User_model->feedback($author, $content);

		if (!$result) {
			$response["result"] = "failure";
			$response["message"] = urlencode("反馈失败");
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "success";
			$response["message"] = urlencode("反馈成功");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 验证邮箱是否合法
	 * @param  string  $email 
	 * @return boolean        
	 */
	public function isEmailValid($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * 输入为空
	 * @return string(json) 
	 */
	public function getMsgParamNotEmpty() {
		$response["result"] = "failure";
		$response["message"] = urlencode("输入不能为空");
		return urldecode(json_encode($response));
	}

	/**
	 * 输入格式错误
	 * @return string(json)
	 */
	public function getMsgInvalidParam() {
		$response["result"] = "failure";
		$response["message"] = urlencode("格式不正确");
		return urldecode(json_encode($response));
	}

	/**
	 * 邮箱格式错误
	 * @return string(json)
	 */
	public function getMsgInvalidEmail() {
		$response["result"] = "failure";
		$response["message"] = urlencode("邮箱不存在");
		return urldecode(json_encode($response));
	}
}

/**
 * 因为是之前写的，所以用到PHP原生的input数据流
 */
$fun = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {
			if ($operation == 'registerReq') {
				if(isset($data->user) && !empty($data->user) &&isset($data->user->email)){
					$user = $data->user;
					$email = $user->email;

					if ($fun->isEmailValid($email)) {
						echo $fun->registerRequest($email);
						exit;
					}
					else {
						echo $fun->getMsgInvalidEmail();
						exit;
					}
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'registerUser') {
				if(isset($data->user) && !empty($data->user) && isset($data->user->name) && !empty($data->user->name) && isset($data->user->email) && isset($data->user->password) && isset($data->user->code)) {
					$user = $data->user;
					$name = $user->name;
					$email = $user->email;
					$code = $user->code;
					$password = $user->password;
					echo $fun->registerUser($name, $email, $password, $code);
					exit;				
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'login') {
				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password)) {
					$user = $data->user;
					$email = $user->email;
					$password = $user->password;
					echo $fun->loginUser($email, $password);
					exit;
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'chgPass') {
				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->old_password) && isset($data->user->new_password)) {
					$user = $data->user;
					$email = $user->email;
					$old_password = $user->old_password;
					$new_password = $user->new_password;
					$new_password_verify = $user->new_password_verify;

					echo $fun->changePassword($email, $old_password, $new_password, $new_password_verify);
					exit;
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'resPassReq') {
				if(isset($data->user) && !empty($data->user) &&isset($data->user->email)){
					$user = $data->user;
					$email = $user->email;
					echo $fun->resetPasswordRequest($email);
					exit;
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'resPass') {
				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password) && isset($data->user->code)) {
					$user = $data->user;
					$email = $user->email;
					$code = $user->code;
					$password = $user->password;
					echo $fun->resetPassword($email, $password, $code);
					exit;
				} 
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'feedback') {
				if (isset($data->author) && !empty($data->author) && isset($data->content) && !empty($data->content)) {
					$author = $data->author;
					$content = $data->content;
					echo $fun->feedback($author, $content);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}
		}	
		else {		
			echo $fun->getMsgParamNotEmpty();
			exit;
		}
	} 
	else {
		echo $fun->getMsgInvalidParam();
		exit;
	}
} 
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "撮虾子";
	exit;
}