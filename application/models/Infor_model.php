<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infor_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 查询全部兼职
	 * @return string
	 */
	public function allJob() {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$hotJob["province"] = $row->lv_province;
				$hotJob["city"] = $row->lv_city;
				$hotJob["job"] = $row->lv_title;
				array_push($result, $hotJob);
			}
			return $result;
		}
		else {
			return false;
		}	
	}

	/**
	 * 热门兼职
	 * @param  string $city 所在城市
	 * @return string       
	 */
	public function hotJob($city) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$hotJob["city"] = $row->lv_city;
				$hotJob["job"] = $row->lv_title;
<<<<<<< HEAD
				array_push($result, $hotJob);
=======
				//return $hotJob;
>>>>>>> a210618dee26a23c9dff3a3b063be0ab341066bc
			}
			return $result;
		}
		else {
			return false;
		}
	}

	/**
	 * 附近兼职
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @return string           
	 */
	public function nearJob($province, $city) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$nearJob["province"] = $row->lv_province;
				$nearJob["city"] = $row->lv_city;
				$nearJob["job"] = $row->lv_title;
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
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @param  string $worktype 兼职类型
	 * @return string           
	 */
	public function recommendJob($province, $city, $worktype) {
		$result = array();
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city, 'lv_worktype' => $worktype));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$recommendJob["province"] = $row->lv_province;
				$recommendJob["city"] = $row->lv_city;
				$recommendJob["worktype"] = $row->lv_worktype;
				$recommendJob["job"] = $row->lv_title;
				array_push($result, $recommendJob);
			}
<<<<<<< HEAD
			return $result;
=======
			return array_rand($recommendJob, 2);
>>>>>>> a210618dee26a23c9dff3a3b063be0ab341066bc
		}
		else {
			return false;
		}

	}

	/**
	 * 周末兼职
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @return string           
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
					$weekendJob["province"] = $row->lv_province;
					$weekendJob["city"] = $row->lv_city;
					$weekendJob["startdate"] = $row->lv_startdate;
					$weekendJob["enddate"] = $row->lv_enddate;
					$weekendJob["job"] = $row->lv_title;
					array_push($result, $weekendJob);
				}
				return $result;
			}
		}
		else {
			return false;
		}
	}

	/**
	 * 根据日期判断一天是不是周末
	 * @param  string  $day 日期
	 * @return boolean      
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
<<<<<<< HEAD

	/**
	 * 检查该省是否有兼职信息
	 * @param  string $province 
	 * @return boolean           
	 */
	public function checkProvince($province) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_province', $province);
=======
	public function checkCity($city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
>>>>>>> a210618dee26a23c9dff3a3b063be0ab341066bc
		$query = $this->db->get();
		if ($query && $query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
<<<<<<< HEAD

		}
	}

	/**
     * 检查该城市是否有兼职信息
     * @param  string $city 
     * @return boolean       
     */
	public function checkCity($city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
=======
		}
	}
	public function checkProvince($province) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_province', $province);
>>>>>>> a210618dee26a23c9dff3a3b063be0ab341066bc
		$query = $this->db->get();
		if ($query && $query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
<<<<<<< HEAD

=======
>>>>>>> a210618dee26a23c9dff3a3b063be0ab341066bc
		}
	}
}
