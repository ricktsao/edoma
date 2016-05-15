<?php

class Auction {

	public function __construct()
	{
		$this->CI =& get_instance();

	}


	public function check_bid_deal($product_sn, $bid_sn) {

		$product_info = array();

		if (isNotNull($product_sn) ) {
			$arr_data = $this->CI->product_model->getProduct($product_sn);
			if ($arr_data['count'] > 0) {
				$product_info = $arr_data['data'];
			//	var_dump($product_info);

echo '<hr>';

			$bid_data = $this->CI->product_model->listDB('bid', 'sn='.$bid_sn);

				$bid_info = $bid_data['data'][0];
				//var_dump($bid_info);

			}
		}

		//return $this->transmit($payload);
		return true;
	}




}