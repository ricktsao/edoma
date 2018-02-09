<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Send_Api extends IT_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {

		$type = $this->input->post("type");
		$token = $this->input->post("token");
		$msg = $this->input->post("msg");

		if($type == "" || $token == "" || $msg ==""){
			echo "少了必要欄位";
			die();
		}

		switch ($type) {
			case '2':
				include_once APPPATH.'/third_party/ApnsPHP/Autoload.php';
				$push = new ApnsPHP_Push(
					ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
					'cert.pem'
				);
				$push->connect();
				$iosUser = new ApnsPHP_Message($token);
				$iosUser->setBadge(1);
				$iosUser->setSound();
				$iosUser->setExpiry(30);
				$iosUser->setText($msg);
				$push->add($iosUser);
				$push->send();

				// Disconnect from the Apple Push Notification Service
				$push->disconnect();
				// Examine the error message container
				$aErrorQueue = $push->getErrors();
				if (!empty($aErrorQueue)) {
					var_dump($aErrorQueue);
				}
				break;

			case '1':
				$apiKey = "AIzaSyAZ-RUOi_DZ9B-Xw_n95CvWXDOWCrXhQmM";
				$gcm_url = 'https://android.googleapis.com/gcm/send';
				$android_tokens = [];
				array_push($android_tokens, $token);
				$headers = array('Content-Type: application/json',
					'Authorization: key=' . $apiKey,
				);
				$fields = array('registration_ids' => $android_tokens,
					'data' => array(
						'message' => $msg,
					),
				);
				$this->curl($gcm_url, $headers, $fields);
				break;
		}

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
