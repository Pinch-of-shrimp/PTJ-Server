<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infor_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 全部兼职
	 *
	 * @return     array|boolean  
	 */
	public function allJob() {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$allJob["job_id"] = $row->lv_id;
				$allJob["job"] = $row->lv_title;
				$allJob["province"] = $row->lv_province;
				$allJob["city"] = $row->lv_city;
				$allJob["startdate"] = $row->lv_startdate;
				$allJob["enddate"] = $row->lv_enddate;
				$allJob["worktime"] = $row->lv_time;
				$allJob["salary"] = $row->lv_salary;
				$allJob["salarytype"] = $row->lv_salarytype;
				$allJob["worktype"] = $row->lv_worktype;
				$allJob["peoplenumb"] = $row->lv_peoplenumb;
				$allJob["description"] = $row->lv_description;
				$allJob["require"] = $row->lv_require;
				$allJob["workcontent"] = $row->lv_workcontent;
				array_push($result, $allJob);
			}
			return $result;
		}
		else {
			return false;
		}	
	}
	
	/**
	 * 热门兼职
	 *
	 * @param      <string>         $city   所在城市
	 *
	 * @return     array|boolean  
	 */
	public function hotJob($city) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$hotJob["job_id"] = $row->lv_id;
				$hotJob["job"] = $row->lv_title;
				$hotJob["province"] = $row->lv_province;
				$hotJob["city"] = $row->lv_city;
				$hotJob["startdate"] = $row->lv_startdate;
				$hotJob["enddate"] = $row->lv_enddate;
				$hotJob["worktime"] = $row->lv_time;
				$hotJob["salary"] = $row->lv_salary;
				$hotJob["salarytype"] = $row->lv_salarytype;
				$hotJob["worktype"] = $row->lv_worktype;
				$hotJob["peoplenumb"] = $row->lv_peoplenumb;
				$hotJob["description"] = $row->lv_description;
				$hotJob["require"] = $row->lv_require;
				$hotJob["workcontent"] = $row->lv_workcontent;
				array_push($result, $hotJob);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	
	/**
	 * 附近兼职
	 *
	 * @param      <string>         $province  所在省份
	 * @param      <string>         $city      所在城市
	 *
	 * @return     array|boolean  
	 */
	public function nearJob($province, $city) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$nearJob["job_id"] = $row->lv_id;
				$nearJob["job"] = $row->lv_title;
				$nearJob["province"] = $row->lv_province;
				$nearJob["city"] = $row->lv_city;
				$nearJob["startdate"] = $row->lv_startdate;
				$nearJob["enddate"] = $row->lv_enddate;
				$nearJob["worktime"] = $row->lv_time;
				$nearJob["salary"] = $row->lv_salary;
				$nearJob["salarytype"] = $row->lv_salarytype;
				$nearJob["worktype"] = $row->lv_worktype;
				$nearJob["peoplenumb"] = $row->lv_peoplenumb;
				$nearJob["description"] = $row->lv_description;
				$nearJob["require"] = $row->lv_require;
				$nearJob["workcontent"] = $row->lv_workcontent;
				array_push($result, $nearJob);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	
	/**
	 * 兼职推荐
	 *
	 * @param      <string>         $province  所在省份
	 * @param      <string>         $city      所在城市
	 * @param      <string>         $worktype  兼职类型
	 *
	 * @return     array|boolean  
	 */
	public function recommendJob($province, $city, $worktype) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city, 'lv_worktype' => $worktype));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$recommendJob["job_id"] = $row->lv_id;
				$recommendJob["job"] = $row->lv_title;
				$recommendJob["province"] = $row->lv_province;
				$recommendJob["city"] = $row->lv_city;
				$recommendJob["startdate"] = $row->lv_startdate;
				$recommendJob["enddate"] = $row->lv_enddate;
				$recommendJob["worktime"] = $row->lv_time;
				$recommendJob["salary"] = $row->lv_salary;
				$recommendJob["salarytype"] = $row->lv_salarytype;
				$recommendJob["worktype"] = $row->lv_worktype;
				$recommendJob["peoplenumb"] = $row->lv_peoplenumb;
				$recommendJob["description"] = $row->lv_description;
				$recommendJob["require"] = $row->lv_require;
				$recommendJob["workcontent"] = $row->lv_workcontent;
				array_push($result, $recommendJob);
			}
			return $result;
		}
		else {
			return false;
		}

	}
	
	/**
	 * 周末兼职
	 *
	 * @param      <string>         $province  所在省份
	 * @param      <string>         $city      所在城市
	 *
	 * @return     array|boolean
	 */
	public function weekendJob($province, $city) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				// 如果是周末兼职
				if ($this->isWeekend($row->lv_startdate)) {
					$weekendJob["job_id"] = $row->lv_id;
					$weekendJob["job"] = $row->lv_title;
					$weekendJob["province"] = $row->lv_province;
					$weekendJob["city"] = $row->lv_city;
					$weekendJob["startdate"] = $row->lv_startdate;
					$weekendJob["enddate"] = $row->lv_enddate;
					$weekendJob["worktime"] = $row->lv_time;
					$weekendJob["salary"] = $row->lv_salary;
					$weekendJob["salarytype"] = $row->lv_salarytype;
					$weekendJob["worktype"] = $row->lv_worktype;
					$weekendJob["peoplenumb"] = $row->lv_peoplenumb;
					$weekendJob["description"] = $row->lv_description;
					$weekendJob["require"] = $row->lv_require;
					$weekendJob["workcontent"] = $row->lv_workcontent;
					array_push($result, $weekendJob);
				}
			}
			return $result;
		}
		else {
			return false;
		}
	}

	/**
	 * 模糊查询职位
	 *
	 * @param      <string>         $jobname  The jobname
	 *
	 * @return     array|boolean  
	 */
	public function searchJob($jobname) {
		$result = array();
		$this->db->select('*');
		$this->db->like('lv_title', $jobname);
		$this->db->from('jobinformation');
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				$searchJob["job_id"] = $row->lv_id;
				$searchJob["job"] = $row->lv_title;
				$searchJob["province"] = $row->lv_province;
				$searchJob["city"] = $row->lv_city;
				$searchJob["startdate"] = $row->lv_startdate;
				$searchJob["enddate"] = $row->lv_enddate;
				$searchJob["worktime"] = $row->lv_time;
				$searchJob["salary"] = $row->lv_salary;
				$searchJob["salarytype"] = $row->lv_salarytype;
				$searchJob["worktype"] = $row->lv_worktype;
				$searchJob["peoplenumb"] = $row->lv_peoplenumb;
				$searchJob["description"] = $row->lv_description;
				$searchJob["require"] = $row->lv_require;
				$searchJob["workcontent"] = $row->lv_workcontent;
				array_push($result, $searchJob);
			}
			return $result;
		}
		else {
			return false;
		}	
	}
	
	/**
	 * 根据日期判断一天是不是周末
	 *
	 * @param      <string>   $day    日期
	 *
	 * @return     boolean  True if weekend, False otherwise.
	 */
	public function isWeekend($day) {
		$week = date("w", strtotime($day));
		if($week == "0" || $week == "6") {
			return true;
		} 
		else {
			return false;
		}
	}
	
	/**
	 * 检查该省是否有兼职信息
	 *
	 * @param      <string>   $province  
	 *
	 * @return     boolean  
	 */
	public function checkProvince($province) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_province', $province);
		$query = $this->db->get();
		if ($query && $query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 检查该城市是否有兼职信息
	 *
	 * @param      <string>   $city   
	 *
	 * @return     boolean  
	 */
	public function checkCity($city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
		$query = $this->db->get();
		if ($query && $query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
}
