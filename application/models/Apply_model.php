<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function applyEmployment($jobtitle, $province, $city, $startdate, $enddate, $worktime, $salary, $salarytype, $worktype, $peoplenumb, $description, $require, $workcontent) {
		$jobData = array('er_jobtitle' => $jobtitle,
						 'er_province' => $province,
						 'er_city' => $city,
						 'er_startdate' => $startdate,
						 'er_enddate' => $enddate,
						 'er_worktime' => $worktime,
						 'er_salary' => $salary,
						 'er_salarytype' => $salarytype,
						 'er_worktype' => $worktype,
						 'er_peoplenumb' => $peoplenumb,
						 'er_description' => $description,
						 'er_require' => $require,
						 'er_workcontent' => $workcontent);
		$this->db->select('*');
		$this->db->from('employment_request');
		$this->db->where('er_jobtitle', $jobtitle);
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			// 新的信息
			if ($row_count == 0) {

				$insert_query = $this->db->insert('employment_request', $jobData);

				if ($insert_query) {
					return true;
				}
				else {
					return false;
				}
			}
			// 信息已经存在
			else {

				$update_query = $this->db->update('employment_request', $jobData);
				if ($update_query) {
					return true;
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
	 * 申请职位请求
	 *
	 * @param      <string>   $user_id  The user identifier
	 * @param      <int>   $job_id   职位编号
	 *
	 * @return     boolean  
	 */
	public function applyEmploy($user_id, $job_id) {
		$this->db->select('*');
		$this->db->from('employ_request');
		$this->db->where(array('unique_id' => $user_id,
							   'job_id' => $job_id));
		$query = $this->db->get();

		if ($query) {
			$row_count = $query->num_rows();
			// 新的信息
			if ($row_count == 0) {
				$insert_data = array('unique_id' => $user_id,
									 'job_id' => $job_id,
									 'applystate' => 2);

				$insert_query = $this->db->insert('employ_request', $insert_data);

				if ($insert_query) {
					$employApply["unique_id"] = $user_id;
					$employApply["job_id"] = $job_id;
					$employApply["applystate"] = 2;
					return $employApply;
				}
				else {
					return false;
				}
			}
			// 信息已经存在
			else {
				$update_data = array('unique_id' => $user_id,
							   		 'job_id' => $job_id,
							   		 'applystate' => 2);
				$update_query = $this->db->update('employ_request', $update_data);
				if ($update_query) {
					$employApply["unique_id"] = $user_id;
					$employApply["job_id"] = $job_id;
					$employApply["applystate"] = 2;
					return $employApply;
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
	 * 查询申请的职位状态
	 *
	 * @param      <string>         $user_id     The user identifier
	 * @param      <string>         $applyState 职位申请状态，2，1，-1分别代表申请中，申请成功，申请失败
	 *
	 * @return     array|boolean  The apply jobs.
	 */
	public function getJobState($user_id, $applyState) {
		$result = array();
		$job_ids = $this->getApplyJobs($user_id, $applyState);
		for ($i = 0; $i < count($job_ids); $i++) {
			// jobInfor是一个二维数组，包含职位所有信息
			$jobInfor = $this->getJobInfor($job_ids[$i]);
			array_push($result, $jobInfor);
		}
		return $result;
	}
	
	/**
	 * 根据user的唯一id获取其申请的职位job_id集合
	 *
	 * @param      <string>         $user_id     The user identifier
	 * @param      <string>         $applyState 职位申请状态，0，2，-1分别代表申请中，申请成功，申请失败
	 *
	 * @return     array|boolean  The apply jobs.
	 */
	public function getApplyJobs($user_id, $applyState) {
		$job_ids = array();
		$this->db->select('*');
		$this->db->from('employ_request');
		$this->db->where(array('unique_id' => $user_id,
							   'applystate' => $applyState));
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				array_push($job_ids, $row->job_id);
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
			}
			return $jobInfor;
		}
		else {
			return false;
		}
	}
}