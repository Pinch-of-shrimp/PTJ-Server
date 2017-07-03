<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 插入用户数据
	 * @param  string $name     
	 * @param  string $email    
	 * @param  string $password 
	 * @return boolean           
	 */
	public function insertData($name, $email, $password) {

		$unique_id = uniqid('', true);
		$hash = $this->getHash($password);
		$encrypted_password = $hash["encrypted"];
		$salt = $hash["salt"];

		$data = array('unique_id' => $unique_id, 
					  'name' => $name, 
					  'email' => $email,
					  'encrypted_password' => $encrypted_password, 
					  'salt' => $salt
					  'createtime' = NOW());
		$query = $this->db->insert('users', $data);

		if ($query) {
			$user["name"] = $name;
			$user["unique_id"] = $unique_id;
			$user["email"] = $email;
			return $user;
		}
		else {
			return false;
		}
	}

	/**
	 * 注册用户请求
	 * @param  string $email 
	 * @return boolean        
	 */
	public function registerRequest($email) {

		$random_string = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
		$hash = $this->getHash($random_string);
		$encrypted_temp_password = $hash["encrypted"];
		$salt = $hash["salt"];

		$this->db->select('*');
		$this->db->from('register_request');
		$this->db->where('email', $email);
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			if ($row_count == 0) {

				$insert_data = array('email' => $email,
									 'encrypted_temp_password' => $encrypted_temp_password, 
									 'salt' => $salt,
									 'cratetime' => date("Y-m-d H:i:s"));

				$insert_query = $this->db->insert('register_request', $insert_data);

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

				$update_data = array('email' => $email, 
					  				 'encrypted_temp_password' => $encrypted_temp_password,
					  				 'salt' => $salt,
					  				 'cratetime' => date("Y-m-d H:i:s"));
				$update_query = $this->db->update('register_request', $update_data);
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

	/**
	 * 注册用户
	 * @param  string $name     
	 * @param  string $email    
	 * @param  string $password 
	 * @param  string $code     邮箱收到的验证码
	 * @return boolean           
	 */
	public function registerUser($name, $email, $password, $code) {

		$this->db->select('*');
		$this->db->from('register_request');
		$this->db->where('email', $email);
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$salt = $row->salt;
			$db_encrypted_temp_password = $row->encrypted_temp_password;

			if ($this->verifyHash($code.$salt, $db_encrypted_temp_password)) {
				$old = new DateTime($row->cratetime);
				$now = new DateTime(date("Y-m-d H:i:s"));
				$diff = $now->getTimestamp() - $old->getTimestamp();

				// 需要在300s以内输入验证码
				if ($diff < 300000) {
					return $this->insertData($name, $email, $password);
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
	}

	/**
	 * 登录验证
	 * @param  string $email    
	 * @param  string $password 
	 * @return boolean           
	 */
	public function checkLogin($email, $password) {

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$query = $this->db->get();

		foreach ($query->result() as $row) {
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
	}

	/**
	 * 更新密码
	 * @param  string $email    
	 * @param  string $password 
	 * @return boolean           
	 */
	public function changePassword($email, $password) {

		$hash = $this->getHash($password);
		$encrypted_password = $hash["encrypted"];
		$salt = $hash["salt"];

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

	/**
	 * 找回密码请求
	 * @param  string $email 
	 * @return boolean       
	 */
	public function passwordResetRequest($email) {

		$random_string = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
		$hash = $this->getHash($random_string);
		$encrypted_temp_password = $hash["encrypted"];
		$salt = $hash["salt"];

		$this->db->select('*');
		$this->db->from('password_reset_request');
		$this->db->where('email', $email);
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			if ($row_count == 0) {

				$insert_data = array('email' => $email,
									 'encrypted_temp_password' => $encrypted_temp_password, 
									 'salt' => $salt,
									 'cratetime' => date("Y-m-d H:i:s"));

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

				$update_data = array('email' => $email, 
					  				 'encrypted_temp_password' => $encrypted_temp_password,
					  				 'salt' => $salt,
					  				 'cratetime' => date("Y-m-d H:i:s"));
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

	/**
	 * 找回密码
	 * @param  string $email    
	 * @param  string $password 
	 * @param  string $code     邮箱接收到的验证码
	 * @return boolean           
	 */
	public function resetPassword($email, $password, $code) {

		$this->db->select('*');
		$this->db->from('password_reset_request');
		$this->db->where('email', $email);
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$salt = $row->salt;
			$db_encrypted_temp_password = $row->encrypted_temp_password;

			if ($this->verifyHash($code.$salt, $db_encrypted_temp_password)) {
				$old = new DateTime($row->cratetime);
				$now = new DateTime(date("Y-m-d H:i:s"));
				$diff = $now->getTimestamp() - $old->getTimestamp();

				// 需要在300s以内输入验证码
				if ($diff < 300000) {
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
	}

	public function feedback($author, $content) {
		$data = array('author' => $author, 
					  'content' => $content);
		$query = $this->db->insert('feedback', $data);

		if ($query) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 查找邮箱来检查用户是否注册过
	 * @param  string $email 
	 * @return boolean       
	 */
	public function checkUserExist($email) {

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$query = $this->db->get();
		if ($query && $query->num_rows() > 0) {
			// 邮箱已经被注册
			return true;
		}
		else {
			return false;

		}
	}


	/**
	 * 返回哈希加密后的密码
	 * @param  string $password 
	 * @return string $hash        哈希加密后的密码
	 */
	public function getHash($password) {

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
		$hash = array('salt' => $salt, "encrypted" => $encrypted);

		return $hash;
	}

	/**
	 * 检查密码是否匹配，用于登录验证
	 * @param  string $password 
	 * @param  string $hash     
	 * @return boolean          
	 */
	public function verifyHash($password, $hash) {
		return password_verify($password, $hash);
	}
}
