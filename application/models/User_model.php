<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

// 构造函数连接数据库
public function __construct() {
	parent::__construct();
	$this->load->database();
}

// 注册用户，并且把password用hash编码加密后插入数据库->成功
public function insertData($name, $email, $password) {

	$unique_id = uniqid('', true);
	$hash = $this->getHash($password);
	$encrypted_password = $hash["encrypted"];
	$salt = $hash["salt"];

	// $sql = 'INSERT INTO users SET unique_id =:unique_id, name =:name,
	// email =:email, encrypted_password =: encrypted_password, salt =:salt, create_at = NOW()';
	// $query = $this->db->prepare($sql);
	// $query->execute(array('unique_id' => $unique_id, ':name' => $name, ':email' => $email,
	// 	':encrypted_password' => $encrypted_password, ':salt' => $salt));

	// if ($query) {
	// 	return true;
	// }
	// else {
	// 	return false;
	// }
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

// 登录验证->成功
public function checkLogin($email, $password) {

	// $sql = 'SELECT * FROM users WHERE email =:email';
	$this->db->select('*');
	$this->db->from('users');
	$this->db->where('email', $email);
	$query = $this->db->get();

	// $data = $query->result();
	// $salt = $data->salt;
	// $db_encrypted_password = $data->encrypted_password;
	// $query->execute(array(':email' => $email));
	// $data = $query->fetchObject();
	foreach ($query->result() as $row) {
		// echo $row->salt;
		// echo $row->encrypted_password;
		$salt = $row->salt;
		$db_encrypted_password = $row->encrypted_password;
		if ($this->verifyHash($password.$salt, $db_encrypted_password)) {
			$user["name"] = $row->name;
			$user["email"] = $row->email;
			$user["unique_id"] = $row->unique_id;
			return $user;
		}
		else {
			return false;
		}
	}
	// $salt = $data->salt;
	// $db_encrypted_password = $data->encrypted_password;

	// if ($this->verifyHash($password.$salt, $db_encrypted_password)) {
	// 	$user["name"] = $row->name;
	// 	$user["email"] = $row->email;
	// 	$user["unique_id"] = $row->unique_id;
	// 	return $user;
	// }
	// else {
	// 	return false;
	// }
}

// 更新密码->成功
public function changePassword($email, $password) {

	$hash = $this->getHash($password);
	$encrypted_password = $hash["encrypted"];
	$salt = $hash["salt"];

	// $sql = 'UPDATE users SET encrypted_password = :encrypted_password, salt = :salt WHERE email = :email;';
	// $query = $this->db->prepare($sql);
	// $query->execute(array(':email' => $email, ':encrypted_password' => $encrypted_password, ':salt' => $salt));
	$data = array('encrypted_password' => $encrypted_password, 
				  'salt' => $salt);
	$this->db->where('email', $email);
	$query = $this->db->update('users', $data);

	if ($query) {
		return true;
	}
	else {
		return false;
	}
}

// 找回密码请求
public function passwordResetRequest($email) {

	$random_string = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
	$hash = $this->getHash($random_string);
	$encrypted_temp_password = $hash["encrypted"];
	$salt = $hash["salt"];

	// $sql = 'SELECT COUNT(*) from password_reset_request WHERE email =:email';
	// $query = $this->db->prepare($sql);
	// $query->execute(array('eamil' => $email));
	$this->db->select('*');
	$this->db->from('password_reset_request');
	$this->db->where('email', $email);
	$query = $this->db->get();

	if ($query) {
		$row_count = $query->num_rows();
		if ($row_count == 0) {

			// $insert_sql = 'INSERT INTO password_reset_request SET email =:email, encrypted_temp_password =:encrypted_password, salt =:salt, create_at =:create_at';
			// $insert_query = $this->db->prepare($insert_sql);
			// $insert_query->execute(array(':email' => $email, ':encrypted_temp_password' => $encrypted_temp_password, ':salt' => $salt, ':create_at' => date("Y-m-d H:i:s")));
			$insert_data = array('email' => $email,
								 'encrypted_temp_password' => $encrypted_temp_password, 
								 'salt' => $salt,
								 'created_at' => date("Y-m-d H:i:s"));

			$insert_query = $this->db->insert('password_reset_request', $insert_data);

			if ($insert_query) {
				$user["email"] = $email;
				$user["temp_password"] = $random_string;
				return $user;
			}
			else {
				return false;
			}
		}
		else {
			// $update_sql = 'UPDATE INTO password_reset_request SET email =:email, encrypted_temp_password =:encrypted_password, salt =:salt, create_at =:create_at';
			// $update_query = $this->db->prepare($insert_sql);
			// $update_query->execute(array(':email' => $email, ':encrypted_temp_password' => $encrypted_temp_password, ':salt' => $salt, ':create_at' => date("Y-m-d H:i:s")));

			$update_data = array('email' => $email, 
				  				 'encrypted_temp_password' => $encrypted_temp_password,
				  				 'salt' => $salt,
				  				 'created_at' => date("Y-m-d H:i:s"));
			$update_query = $this->db->update('password_reset_request', $update_data);
			if ($update_query) {
				$user["email"] = $email;
				$user["temp_password"] = $random_string;
				return $user;
			}
			else {
				return false;
			}
		}
	}
	else {
		return false;
	}
}

// 找回密码
public function resetPassword($email, $password, $code) {

	// $sql = 'SELECT * FROM password_reset_request WHERE email =' . $email;
	// $query = $this->db->query($sql);
	// $query->execute(array(':email' => $eamil));
	$this->db->select('*');
	$this->db->from('password_reset_request');
	$this->db->where('email', $email);
	$query = $this->db->get();

	foreach ($query->result() as $row) {
		$salt = $row->salt;
		$db_encrypted_temp_password = $row->encrypted_temp_password;

		if ($this->verifyHash($code.$salt, $db_encrypted_temp_password)) {
			$old = new DateTime($row->created_at);
			$now = new DateTime(date("Y-m-d H:i:s"));
			$diff = $now->getTimestamp() - $old->getTimestamp();

			// 需要在300s以内输入验证码
			if ($diff < 300) {
				return $this->changePassword($email, $password);
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	// $data = $query->result();
	// $salt = $data->salt;
	// $db_encrypted_password = $data->encrypted_temp_password;

	// if ($this->verifyHash($code . $salt, $db_encrypted_temp_password)) {
	// 	$old = new DateTime($data->create_at);
	// 	$now = new DateTime(date("Y-m-d H:i:s"));
	// 	$diff = $now->getTimestamp() - $old->getTimestamp();

	// 	// 需要在120s以内输入验证码
	// 	if ($diff < 120) {
	// 		return $this->changePassword($email, $password);
	// 	}
	// 	else {
	// 		false;
	// 	}
	// }
	// else {
	// 	return false;
	// }
}

// 通过查找邮箱来检查用户是否注册过->成功
public function checkUserExist($email) {

	// $sql = 'SELECT COUNT(*) from users WHERE email =: email';
	// $query = $this->db->prepare($sql);
	// $query->execute(array('email' => $email));

	// if ($query) {
	// 	$row_count = $query->fetchColumn();
	// 	if (row_count == 0) {
	// 		return false;
	// 	}
	// 	else {
	// 		return true;
	// 	}
	// }
	// else {
	// 	return false;
	// }
	$this->db->select('*');
	$this->db->from('users');
	$this->db->where('email', $email);
	$query = $this->db->get();
	if ($query && $query->num_rows() > 0) {
		return true;
	}
	else {
		return false;
	}
}

// 返回哈希加密后的密码
public function getHash($password) {

	$salt = sha1(rand());
	$salt = substr($salt, 0, 10);
	$encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
	$hash = array('salt' => $salt, "encrypted" => $encrypted);

	return $hash;
}

// 用PHP的password_verify()方法检查密码是否匹配，用于登录验证
public function verifyHash($password, $hash) {
	return password_verify($password, $hash);
}
}
