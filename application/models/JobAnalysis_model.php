<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobAnalysis_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Gets the analysis.
	 *
	 * @return     array|boolean  The analysis.
	 */
	public function getAnalysis() {
		$jobAnalysis = array();
		$this->db->select('*');
		$this->db->from('lagou');
		$query = $this->db->get();
		if ($query) {
			foreach ($query->result() as $row) {
				$jobAnalysis["job_id"] = $row->url_object_id;
				$jobAnalysis["url"] = $row->url;
				$jobAnalysis["title"] = $row->title;
				$jobAnalysis["salary"] = $row->slary;
				$jobAnalysis["city"] = $row->job_city;
				$jobAnalysis["require"] = $row->degree_need;
				$jobAnalysis["type"] = $row->job_type;
				$jobAnalysis["tags"] = $row->tags;
				$jobAnalysis["place"] = $row->job_addr;
				$jobAnalysis["companyname"] = $row->company_name;
				array_push($result, $jobAnalysis);
			}
			return $jobAnalysis;
		}
		else {
			return false;
		}
	}
}