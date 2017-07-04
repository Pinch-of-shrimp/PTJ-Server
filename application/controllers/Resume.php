<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resume extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Resume_model');
	}

	public function getResume($user_id) {
		$result = $this->Resume_model->getResume($user_id);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("查询我的简历成功");
			$response["searchCollection"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("查询我的简历失败");
			return urldecode(json_encode($response));
		}
	}

	public function updateResume($user_id, $name, $sex, $birthday, $isStudent, $realname, $school, $major, $eduStartDate, $tag, $statement) {
		$result = $this->Resume_model->updateResume($name, $sex, $birthday, $isStudent, $realname, $school, $major, $eduStartDate, $tag, $statement);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("更新我的简历成功");
			$response["updateCollection"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("更新我的简历失败");
			return urldecode(json_encode($response));
		}
	}

	// public function deleteCollection($user_id, $job_id) {
	// 	$result = $this->Collect_model->deleteCollection($user_id, $job_id);

	// 	if ($result) {
	// 		$response["result"] = "success";
	// 		$response["message"] = urlencode("删除我的收藏成功");
	// 		$response["deleteCollection"] = $result;
	// 		return urldecode(json_encode($response));
	// 	}
	// 	else {
	// 		$response["result"] = "failure";
	// 		$response["message"] = urlencode("删除我的收藏失败");
	// 		return urldecode(json_encode($response));
	// 	}
	// }

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

$fun = new Resume();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {

			if ($operation == 'getResume') {
				if(isset($data->user_id) && !empty($data->user_id)) {
					$user_id = $data->user_id;
					echo $fun->getResume($user_id);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'updateResume') {
				if(isset($data->user_id) && !empty($data->user_id) && isset($data->sex) && !empty($data->sex) && isset($data->birthday) && !empty($data->birthday) && isset($data->isStudent) && !empty($data->isStudent) && isset($data->realname) && !empty($data->realname)) {
					$user_id = $data->user_id;
					$sex = $data->sex;
					$birthday = $data->birthday;
					$isStudent = $data->isStudent;
					$realname = $data->realname;

					$school = $data->school;
					$major = $data->major;
					$eduStartDate = $data->eduStartDate;
					$tag = $data->tag;
					$statement = $data->statement;
					echo $fun->updateResume($user_id, $name, $sex, $birthday, $isStudent, $realname, $school, $major, $eduStartDate, $tag, $statement);
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
	echo "我的简历";
	exit;
}