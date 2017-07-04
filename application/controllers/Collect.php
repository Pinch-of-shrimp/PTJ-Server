<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Collect_model');
	}

	public function searchCollection($user_id) {
		$result = $this->Collect_model->searchCollection($user_id);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("查询我的收藏成功");
			$response["searchCollection"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("查询我的收藏失败");
			return urldecode(json_encode($response));
		}
	}

	public function updateCollection($user_id, $job_id) {
		$result = $this->Collect_model->updateCollection($user_id, $job_id);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("更新我的收藏成功");
			$response["updateCollection"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("更新我的收藏失败");
			return urldecode(json_encode($response));
		}
	}

	public function deleteCollection($user_id, $job_id) {
		$result = $this->Collect_model->deleteCollection($user_id, $job_id);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("删除我的收藏成功");
			$response["deleteCollection"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("删除我的收藏失败");
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

$fun = new Collect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {

			if ($operation == 'searchCollection') {
				if(isset($data->user_id) && !empty($data->user_id)) {
					$user_id = $data->user_id;
					echo $fun->searchCollection($user_id);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'updateCollection') {
				if(isset($data->user_id) && !empty($data->user_id) && isset($data->job_id) && !empty($data->job_id)) {
					$user_id = $data->user_id;
					$job_id = $data->job_id;
					echo $fun->updateCollection($user_id, $job_id);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'deleteCollection') {
				if(isset($data->user_id) && !empty($data->user_id) && isset($data->job_id) && !empty($data->job_id)) {
					$user_id = $data->user_id;
					$job_id = $data->job_id;
					echo $fun->deleteCollection($user_id, $job_id);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
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
	echo "我的收藏";
	exit;
}