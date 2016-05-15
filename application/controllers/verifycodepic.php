<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifycodepic extends CI_Controller {


	public function index()
	{
		
		/*
		1.$length:要取得的字串長度(可不傳，預設為1)
		2.$complexity:圖檔干擾像素複雜度(可不傳，越高越複雜，預設為0)
		3.$str_font_color:文字顏色(可不傳，預設為#FFFFFF，#可有可無)
		4.$str_bg_color:背景顏色(可不傳，預設為#000000，#可有可無)
		5.$show:是否用圖檔(可不傳，預設為true)
		6.$char_set:字元集，亂數取得將會自字元集中取出(可不傳，預設為大小寫英文)
		*/

		$this->load->library('verifycode');	
		
		$this->verifycode->initVerifyCode(100);
		$this->verifycode->set_char_set( "ABCDEFGHIJKLMNPQRSTVWXY" );
		$this->verifycode->img_veri_code( 6 );
		
		if ($this->session->userdata('veri_code') !== FALSE)
		{
			$this->session->unset_userdata('veri_code');
	    }

		$this->session->set_userdata('veri_code', $this->verifycode->get_veri_code());

	}

}
