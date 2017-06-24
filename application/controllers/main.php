<?php
class Main extends CI_Controllers {

public function __construct() {
	parent::__construct();
	$this->load->model('User_controllers');
}

$user_func = $this->User_model;

// $jsonStr = $this->input->raw_input_stream;
// $data = json_decode($jsonStr);
// $operation = $data->input->post('operation');



// 注册登录操作
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));

	if(isset($data->operation)) {
		$operation = $data->operation;

		if(!empty($operation)) {

			if($operation == 'register') {

				if(isset($data->user) && !empty($data->user) && isset($data->user->name) && isset($data->user->email) && isset($data->user->password)) {
					$user = $data->user;
					$name = $user->name;
					$email = $user->email;
					$password = $user->password;

					if ($user_func->isEmailValid($email)) {
						echo $user_func->registerUser($name, $email, $password);
					} 
					else {
						echo $user_func->getMsgInvalidEmail();
					}
				} 
				else {
					echo $user_func->getMsgInvalidParam();
				}
			}
			else if ($operation == 'login') {

				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password)) {
					$user = $data->user;
					$email = $user->email;
					$password = $user->password;

					echo $user_func->loginUser($email, $password);
				} 
				else {
					echo $user_func->getMsgInvalidParam();
				}
			} 
			else if ($operation == 'chgPass') {

				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->old_password) && isset($data->user->new_password)) {
					$user = $data->user;
					$email = $user->email;
					$old_password = $user->old_password;
					$new_password = $user->new_password;

					echo $user_func->changePassword($email, $old_password, $new_password);
				} 
				else {
					echo $user_func->getMsgInvalidParam();
				}
			}
			else if ($operation == 'resPassReq') {
				if(isset($data->user) && !empty($data->user) &&isset($data->user->email)){
					$user = $data->user;
					$email = $user->email;

					echo $user_func->resetPasswordRequest($email);
				} 
				else {
					echo $user_func->getMsgInvalidParam();
				}
			}
			else if ($operation == 'resPass') {
				if(isset($data->user) && !empty($data->user) && isset($data->user->email) && isset($data->user->password) && isset($data->user->code)) {
					$user = $data->user;
					$email = $user->email;
					$code = $user->code;
					$password = $user->password;

					echo $user_func->resetPassword($email,$code,$password);
				} 
				else {
					echo $user_func->getMsgInvalidParam();
				}
			}
		}
		else {		
			echo $user_func->getMsgParamNotEmpty();
		}
	} 
	else {
		echo $user_func->getMsgInvalidParam();
	}
} 
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "撮虾子";
}
}