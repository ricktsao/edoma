<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Send extends IT_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {
		$apiKey = "AIzaSyAZ-RUOi_DZ9B-Xw_n95CvWXDOWCrXhQmM";
		$gcm_url = 'https://android.googleapis.com/gcm/send';
		//get android id
		$query = "SELECT token_id FROM sys_user WHERE token_type='1' AND token_id !=''";
		$android_ids = $this->it_model->runSql($query)['data'];
		$android_tokens = [];
		foreach ($android_ids as $value) {
			array_push($android_tokens, $value['token_id']);
		}

		//get unfire msg
		$d = date("Y-m-d H:i:s");
		$query = "SELECT sn,message FROM app_push WHERE flag_push=0 AND push_time <='${d}'";
		$msg = $this->it_model->runSql($query)['data'];

		$headers = array('Content-Type: application/json',
			'Authorization: key=' . $apiKey,
		);

		$msg_sn = [];

		foreach ($msg as $value) {
			array_push($msg_sn, $value['sn']);
			//gcm
			$fields = array('registration_ids' => $android_tokens,
				'data' => array(
					'message' => $value['message'],
				),
			);

			$this->curl($gcm_url, $headers, $fields);

		}
		//echo implode(",", $msg_sn);
		if (count($msg_sn) > 0) {
			$query = "UPDATE app_push SET flag_push=1 WHERE sn in (" . implode(",", $msg_sn) . ")";
			$this->it_model->runSqlCmd($query);
		}
		echo "success";
	}

	private function curl($url, $headers, $fields) {

		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

		// 送出 post, 並接收回應, 存入 $result
		$result = curl_exec($ch);

		print_r($result);

	}
}
