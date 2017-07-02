<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infor_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 热门职业
	 * @param  string $city 所在城市
	 * @return boolean       
	 */
	public function hotJob($city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where('lv_city', $city);
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$hotJob["city"] = $row->lv_city;
				$hotJob["job"] = $row->lv_title;
				//return $hotJob;
			}
			return $hotJob;
		}
		else {
			return false;
		}
	}

	/**
	 * 附近职业
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @return boolean           
	 */
	public function nearJob($province, $city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				$nearJob["province"] = $row->lv_province;
				$nearJob["city"] = $row->lv_city;
				$nearJob["job"] = $row->lv_title;
			}
			return $nearJob;
		}
		else {
			return false;
		}
	}

	public function recommendJob($province, $city, $worktype) {
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
			}
			return array_rand($recommendJob, 2);
		}
		else {
			return false;
		}

	}

	public function weekendJob($province, $city) {
		$this->db->select('*');
		$this->db->from('jobinformation');
		$this->db->where(array('lv_province' => $province, 'lv_city' => $city));
		$query = $this->db->get();

		if ($query) {
			foreach ($query->result() as $row) {
				if (isWeekend($row->sartdate)) {
					$weekendJob["province"] = $row->lv_province;
					$weekendJob["city"] = $row->lv_city;
					$weekendJob["startdate"] = $row->lv_startdate;
					$weekendJob["enddate"] = $row->lv_enddate;
					$weekendJob["job"] = $row->lv_title;
				}
				return $weekendJob;
			}
		}
		else {
			return false;
		}
	}

	public function isWeekend($date) {
		$week = date("w",strtotime(date));
		if($week == "0" || $week == "6") {
			return true;
		} 
		else {
			return false;
		}
	}
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
}
