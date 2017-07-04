<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resume_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 获取简历信息
	 *
	 * @param      <type>         $user_id  The user identifier
	 *
	 * @return     array|boolean  The resume.
	 */
	public function getResume($user_id) {
		$resume = array();
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('unique_id', $user_id);
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				$resume["user_id"] = $row->unique_id;
				$resume["name"] = $row->name;
				$resume["sex"] = $row->sex;
				$resume["birthday"] = $row->birthday;
				$resume["school"] = $row->school;
				$resume["major"] = $row->major;
				$resume["eduStartDate"] = $row->eduStartDate;
				$resume["tag"] = $row->tag;
				$resume["statement"] = $row->statement;
			}
			return $resume;
		}
		else {
			return false;
		}
	}

	/**
	 * 简历数据
	 *
	 * @param      <string>   $user_id       The user identifier
	 * @param      <string>   $name          用户名
	 * @param      <boolean>  $sex           性别 *必填
	 * @param      <string>   $birthday      出生年月*必填
	 * @param      <boolean>  $isStudent     是否是学生*必填
	 * @param      <string>   $realname      真实姓名*必填
	 * @param      <string>   $school        学校
	 * @param      <string>   $major         主修专业
	 * @param      <string>   $eduStartDate  入学时间
	 * @param      <string>   $tag           个人标签
	 * @param      <string>   $statement     个人宣言
	 *
	 * @return     boolean  
	 */
	public function updateResume($user_id, $name, $sex, $birthday, $isStudent, $realname, $school, $major, $eduStartDate, $tag, $statement) {
		$this->db->select('*');
		$this->db->from('collection');
		$this->db->where('unique_id', $user_id);
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			// 新的信息
			if ($row_count == 0) {
				$insert_data = array('unique_id' => $user_id,
									 'name' => $name,
									 'sex' => $sex,
									 'birthday' => $birthday,
									 'isStudent' => $isStudent,
									 'realname' => $realname,
									 'school' => $school,
									 'major' => $major,
									 'eduStartDate' => $eduStartDate,
									 'tag' => $tag,
									 'statement' => $statement);

				$insert_query = $this->db->insert('users', $insert_data);

				if ($insert_query) {
					$resume["user_id"] = $user_id;
					$resume["name"] = $name;
					$resume["sex"] = $sex;
					$resume["birthday"] = $birthday;
					$resume["school"] = $school;
					$resume["major"] = $major;
					$resume["eduStartDate"] = $eduStartDate;
					$resume["tag"] = $tag;
					$resume["statement"] = $statement;
					return $resume;
				}
				else {
					return false;
				}
			}
			// 信息已经存在
			else {
				$update_data = array('unique_id' => $user_id,
									 'name' => $name,
									 'sex' => $sex,
									 'birthday' => $birthday,
									 'isStudent' => $isStudent,
									 'realname' => $realname,
									 'school' => $school,
									 'major' => $major,
									 'eduStartDate' => $eduStartDate,
									 'tag' => $tag,
									 'statement' => $statement);
				$update_query = $this->db->update('collection', $update_data);
				if ($update_query) {
					$resume["user_id"] = $user_id;
					$resume["name"] = $name;
					$resume["sex"] = $sex;
					$resume["birthday"] = $birthday;
					$resume["school"] = $school;
					$resume["major"] = $major;
					$resume["eduStartDate"] = $eduStartDate;
					$resume["tag"] = $tag;
					$resume["statement"] = $statement;
					return $resume;
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
}