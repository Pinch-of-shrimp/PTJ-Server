<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Apply_model');
	}

	/**
	 * 发布职位请求
	 *
	 * @param      <string>  $jobtitle     招聘标题
	 * @param      <string>  $province     招聘省份
	 * @param      <string>  $city         招聘城市
	 * @param      <string>  $startdate    开始时间
	 * @param      <string>  $enddate      截止时间
	 * @param      <string>  $worktime     工作时间/天
	 * @param      <string>  $salary       工作薪水/小时
	 * @param      <string>  $salarytype   薪水结算方式/日/月
	 * @param      <string>  $worktype     工作类型
	 * @param      <string>  $peoplenumb   招聘人数
	 * @param      <string>  $description  职位描述
	 * @param      <string>  $require      职位要求
	 * @param      <string>  $workcontent  工作内容
	 *
	 * @return     <json>  ( 注: HTTP通信中并不存在所谓的json，而是将string转成json罢了 )
	 */
	public function applyEmployment($jobtitle, $province, $city, $startdate, $enddate, $worktime, $salary, $salarytype, $worktype, $peoplenumb, $description, $require, $workcontent) {
		$result = $this->Apply_model->applyEmployment($jobtitle, $province, $city, $startdate, $enddate, $worktime, $salary, $salarytype, $worktype, $peoplenumb, $description, $require, $workcontent);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("申请发布职位成功");
			$response["applyEmployment"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("申请发布职位失败");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 申请职位请求
	 *
	 * @param      <string>   $user_id  The user identifier
	 * @param      <int>   $job_id   职位编号
	 *
	 * @return     <json>  
	 */
	public function applyEmploy($user_id, $job_id) {
		$result = $this->Apply_model->applyEmploy($user_id, $job_id);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("申请职位成功");
			$response["applyEmploy"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("申请职位失败");
			return urldecode(json_encode($response));
		}
	}

	/**
	 * 查询申请的职位状态
	 *
	 * @param      <string>         $user_id     The user identifier
	 * @param      <string>         $applyState 职位申请状态，0，1，-1分别代表申请中，申请成功，申请失败
	 *
	 * @return     <json>  			The apply jobs state.
	 */
	public function getJobState($user_id, $applyState) {
		$result = $this->Apply_model->getJobState($user_id, $applyState);

		if ($result) {
			$response["result"] = "success";
			$response["message"] = urlencode("查询申请的职位状态成功");
			$response["getJobState"] = $result;
			return urldecode(json_encode($response));
		}
		else {
			$response["result"] = "failure";
			$response["message"] = urlencode("查询申请的职位状态失败");
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

$fun = new Apply();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents("php://input"));
	if (isset($data->operation)) {
		$operation = $data->operation;
		if (!empty($operation)) {
			//终于知道CI推荐使用它封装函数是为啥了 T T
			if ($operation == 'applyEmployment') {
				if(isset($data->jobtitle) && !empty($data->jobtitle) && isset($data->province) && !empty($data->province) && isset($data->city) && !empty($data->city) && isset($data->startdate) && !empty($data->startdate) && isset($data->enddate) && !empty($data->enddate) && isset($data->worktime) && !empty($data->worktime) && isset($data->salary) && !empty($data->salary) && isset($data->salarytype) && !empty($data->salarytype) && isset($data->worktype) && !empty($data->worktype) && isset($data->peoplenumb) && !empty($data->peoplenumb) && isset($data->description) && !empty($data->description) && isset($data->require) && !empty($data->require) && isset($data->workcontent) && !empty($data->workcontent)) {
					$jobtitle = $data->jobtitle;
					$province = $data->province;
					$city = $data->city;
					$startdate = $data->startdate;
					$enddate = $data->enddate;
					$worktime = $data->worktime;
					$salary = $data->salary;
					$salarytype = $data->salarytype;
					$worktype = $data->worktype;
					$peoplenumb = $data->peoplenumb;
					$description = $data->description;
					$require = $data->require;
					$workcontent = $data->workcontent;
					echo $fun->applyEmployment($jobtitle, $province, $city, $startdate, $enddate, $worktime, $salary, $salarytype, $worktype, $peoplenumb, $description, $require, $workcontent);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'applyEmploy') {
				if(isset($data->user_id) && !empty($data->user_id) && isset($data->job_id) && !empty($data->job_id)) {
					$user_id = $data->user_id;
					$job_id = $data->job_id;
					echo $fun->applyEmploy($user_id, $job_id);
					exit;
				}
				else {
					echo $fun->getMsgInvalidParam();
					exit;
				}
			}

			else if ($operation == 'getJobState') {
				if(isset($data->user_id) && !empty($data->user_id) && isset($data->state) && !empty($data->state)) {
					$user_id = $data->user_id;
					$applyState = $data->state;
					echo $fun->getJobState($user_id, $applyState);
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
	echo "我的申请";
	exit;
}