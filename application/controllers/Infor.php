<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Infor_model');
	}

	public funciton allJob() {
		$result = $this->Infor_model->allJob();

		if ($result) {
			$resonse["result"] = "success";
			$resonse["message"] = urlencode("查询热门职位成功");
			$resonse["allJob"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("请检查你的网络连接");
			return urldecode(json_encode($response));
		}
	}

	public function hotJob($city) {
		if ($this->Infor_model->checkCity($city)) {

			$result = $this->Infor_model->hotJob($city);

			if ($result) {
				$resonse["result"] = "success";
				$resonse["message"] = urlencode("查询热门职位成功");
				$resonse["hotJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$resonse["result"] = "failure";
				$resonse["message"] = urlencode("查询热门职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("该城市暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	public function nearbyJob($province, $city) {
		if ($this->Infor_model->checkCity($city) && $this->Infor_model->checkCity($city)){

			$result = $this->Infor_model->nearbyJob($province, $city);

			if ($result) {
				$resonse["result"] = "success";
				$resonse["message"] = urlencode("查询附近职位成功");
				$resonse["nearbyJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$resonse["result"] = "failure";
				$resonse["message"] = urlencode("查询附近职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	public function weekendJob($province, $city, $worktype) {
		if ($this->Infor_model->checkProvince($province) && $this->Infor_model->checkCity($city)){

			$result = $this->Infor_model->weekendJob($province, $city, $worktype);

			if ($result) {
				$resonse["result"] = "success";
				$resonse["message"] = urlencode("查询附近职位成功");
				$resonse["weekendJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$resonse["result"] = "failure";
				$resonse["message"] = urlencode("查询附近职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	public function recommendJob($province, $city) {
		if ($this->Infor_model->checkProvince($province) && $this->Infor_model->checkCity($city)) {
			$result = $this->Infor_model->recommendJob($province, $city);

			if ($result) {
				$resonse["result"] = "success";
				$resonse["message"] = urlencode("推荐职位成功");
				$resonse["recommendJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$resonse["result"] = "failure";
				$resonse["message"] = urlencode("推荐职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}

			}
		}
	}
}

$fun = new Infor();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->search)) {
		$search = $data->search;
		if (!empty($search)) {
			if ($search == 'hotJob') {
				if(isset($data->city) && !empty($data->city)) {
					$city = $data->city;
					echo $fun->hotJob($city);
					exit;
				}
			}

			else if ($search == 'nearJob') {
				if(isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city)) {
					$province = $data->province;
					$city = $data->city;
					echo $fun->nearbyJob($province, $city);
					exit;
				}
			}

			else if ($search == 'weekendJob') {
				if(isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city)) {
					$province = $data->province;
					$city = $data->city;
					echo $fun->weekendJob($province, $city);
					exit;
			}

			else if ($search == 'recommendJob') {
				if(isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city) && isset($data->worktype) && !empty($data->worktype)) {
					$province = $data->province;
					$city = $data->city;
					$worktype = $data->worktype;
					echo $fun->recommendJob($province, $city, $worktype);
					exit;	
			}
		}
	}
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "撮虾子主页";
	exit;
}
