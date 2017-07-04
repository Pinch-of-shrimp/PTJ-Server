<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 查询我的收藏
	 *
	 * @param      <string>  $user_id  The user identifier
	 *
	 * @return     array   返回一个三维数组，其中每个元素都是包含职位全部信息的二维数组
	 */
	public function searchCollection($user_id) {
		$result = array();
		$job_ids = $this->getUserCollections($user_id);
		for($i = 0; $i < count($job_ids); $i++) {
			// jobInfor是一个二维数组，包含职位所有信息
			$jobInfor = $this-> getJobInfor($job_ids[$i]);
			array_push($result, $jobInfor);
		}
		return $result;
	}

	public function updateCollection($user_id, $job_id) {
		$this->db->select('*');
		$this->db->from('collection');
		$this->db->where(array('unique_id' => $user_id,
							   'job_id' => $job_id));
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			// 新的信息
			if ($row_count == 0) {
				$insert_data = array('unique_id' => $user_id,
									 'job_id' => $job_id);

				$insert_query = $this->db->insert('collection', $insert_data);

				if ($insert_query) {
					$collection["unique_id"] = $user_id;
					$collection["job_id"] = $job_id;
					return $collection;
				}
				else {
					return false;
				}
			}
			// 信息已经存在
			else {
				$update_data = array('unique_id' => $user_id,
							   		 'job_id' => $job_id);
				$update_query = $this->db->update('collection', $update_data);
				if ($update_query) {
					$collection["unique_id"] = $user_id;
					$collection["job_id"] = $job_id;
					return $collection;
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
	 * 删除我的收藏
	 *
	 * @param      <string>   $user_id  The user identifier
	 * @param      <int>   $job_id   The job identifier
	 *
	 * @return     boolean  
	 */
	public function deleteCollection($user_id, $job_id) {
		$query = $this->db->delete('collection', array('unique_id' => $user_id,
													   'job_id' => $job_id));
		if ($query) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 根据user的唯一id获取其收藏的职位job_id
	 *
	 * @param      <string>   $user_id  The user identifier
	 *
	 * @return     boolean  The user collection.
	 */
	public function getUserCollections($user_id) {
		$this->db->select('*');
		$this->db->from('collection');
		$this->db->where('unique_id', $user_id);
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				$job_ids["job_id"] = $row->job_id;
			}
			return $job_ids;
		}
		else {
			return false;
		}
	}	

	/**
	 * 根据job_id查询到职位所有信息
	 *
	 * @param      <int>         $job_id  The job identifier
	 *
	 * @return     array|boolean  The job infor.
	 */
	public function getJobInfor($job_id) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_id', $job_id);
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				$jobInfor["job_id"] = $row->lv_id;
				$jobInfor["job"] = $row->lv_title;
				$jobInfor["province"] = $row->lv_province;
				$jobInfor["city"] = $row->lv_city;
				$jobInfor["startdate"] = $row->lv_startdate;
				$jobInfor["enddate"] = $row->lv_enddate;
				$jobInfor["worktime"] = $row->lv_time;
				$jobInfor["salary"] = $row->lv_salary;
				$jobInfor["salarytype"] = $row->lv_salarytype;
				$jobInfor["worktype"] = $row->lv_worktype;
				$jobInfor["peoplenumb"] = $row->lv_peoplenumb;
				$jobInfor["description"] = $row->lv_description;
				$jobInfor["require"] = $row->lv_require;
				$jobInfor["workcontent"] = $row->lv_workcontent;
				array_push($result, $jobInfor);
			}
			return $result;
		}
		else {
			return false;
		}
	}

}
