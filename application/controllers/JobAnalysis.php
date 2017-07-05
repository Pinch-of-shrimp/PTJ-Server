<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobAnalysis extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('JobAnalysis_model');
	}

	public function getAnalysis() {
		$result = $this->JobAnalysis_model->getAnalysis();
		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("获取职位分析成功");
			$response["getAnalysis"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("获取职位分析失败");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * Gets the message parameter not empty.
	 *
	 * @return     <json>  The message parameter not empty.
	 */
	public function getMsgParamNotEmpty() {
		$response["result"] = "failure";
		$response["message"] = urlencode("输入不能为空");
		return urldecode(json_encode($response));
	}

	/**
	 * Gets the message invalid parameter.
	 *
	 * @return     <json>  The message invalid parameter.
	 */
	public function getMsgInvalidParam() {
		$response["result"] = "failure";
		$response["message"] = urlencode("格式不正确");
		return urldecode(json_encode($response));
	}
}

$fun = new JobAnalysis();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {
			if ($operation == 'getAnalysis') {
					echo $fun->getAnalysis();
					exit;	
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}
		else {		
			echo $fun->getMsgParamNotEmpty();
			exit;
		}
	} 
	else {
		echo $fun->getMsgInvalidParam();
		exit;
	}
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo "职位分析";
	exit;
}