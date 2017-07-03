<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Infor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Infor_model');
	}

	/**
	 * 查询全部兼职
	 * @return string(json)
	 */
	public function allJob() {
		$result = $this->Infor_model->allJob();

		if ($result) {
			$resonse["result"] = "success";
			$resonse["message"] = urlencode("查询全部职位成功");
			$resonse["allJob"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$resonse["result"] = "failure";
			$resonse["message"] = urlencode("请检查你的网络连接");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 热门兼职
	 * @param  string $city 所在城市
	 * @return string(json)       
	 */
	public function hotJob($city) {
		if ($this->Infor_model->checkCity($city)) {

			$result = $this->Infor_model->hotJob($city);

			if ($result) {
				$response["result"] = "success";
				$response["message"] = urlencode("查询热门职位成功");
				$response["hotJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$response["result"] = "failure";
				$response["message"] = urlencode("查询热门职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("该城市暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 附近兼职
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @return string(json)           
	 */
	public function nearJob($province, $city) {
		if ($this->Infor_model->checkCity($city) && $this->Infor_model->checkCity($city)){

			$result = $this->Infor_model->nearJob($province, $city);

			if ($result) {
				$response["result"] = "success";
				$response["message"] = urlencode("查询附近职位成功");
				$response["nearbyJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$response["result"] = "failure";
				$response["message"] = urlencode("查询附近职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 兼职推荐
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @param  string $worktype 兼职类型
	 * @return string(json)           
	 */
	public function weekendJob($province, $city) {
		if ($this->Infor_model->checkProvince($province) && $this->Infor_model->checkCity($city)){

			$result = $this->Infor_model->weekendJob($province, $city);

			if ($result) {
				$response["result"] = "success";
				$response["message"] = urlencode("查询周末兼职成功");
				$response["weekendJob"] = $result;
				return urldecode(json_encode($response));
			}
			else {
				$response["result"] = "failure";
				$response["message"] = urlencode("查询周末兼职失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 周末兼职
	 * @param  string $province 所在省份
	 * @param  string $city     所在城市
	 * @return string(json)           
	 */
	public function recommendJob($province, $city, $worktype) {
		if ($this->Infor_model->checkProvince($province) && $this->Infor_model->checkCity($city)) {
			$result = $this->Infor_model->recommendJob($province, $city, $worktype);

			if ($result) {
				$response["result"] = "success";
				$response["message"] = urlencode("推荐职位成功");
				shuffle($result);
				$response["recommendJob"] = array_slice($result, 0, 3);
				return urldecode(json_encode($response));
			}
			else {
				$response["result"] = "failure";
				$response["message"] = urlencode("推荐职位失败");
				return urldecode(json_encode($response));
			}
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("该地区暂时没有兼职信息");
			return urldecode(json_encode($response));
		}
	}
}

$fun = new Infor();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {

			if ($operation == 'allJob') {
					echo $fun->allJob();
					exit;
				}

			else if ($operation == 'hotJob') {
				if(isset($data->city) && !empty($data->city)) {
					$city = $data->city;
					echo $fun->hotJob($city);
					exit;
				}
			}

			else if ($operation == 'nearJob') {
				if(isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city)) {
					$province = $data->province;
					$city = $data->city;
					echo $fun->nearJob($province, $city);
					exit;
				}
			}

			else if ($operation == 'weekendJob') {
				if(isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city)) {
					$province = $data->province;
					$city = $data->city;
					echo $fun->weekendJob($province, $city);
					exit;
				}
			}

			else if ($operation == 'recommendJob') {
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
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "撮虾子主页";
	exit;
}