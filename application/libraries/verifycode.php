<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/*
名稱：驗證碼
*/
class VerifyCode
{	
	private $char_set;		//亂數字元集
	private $veri_code = "";																	//驗證碼
	private $complexity = 0;																	//干擾像素複雜度
	private $im;
	private $int_font = 5;			//預設字型
	private $arr_font = array();
	
	private $font_color = array
	(
		"r" => 255 , 
		"g" => 255 , 
		"b" => 255
	);
	
	private $bg_color = array
	(
		"r" => 0 , 
		"g" => 0 , 
		"b" => 0
	);

	//預設狀態
	/*
	$length:要取得的字串長度
	$complexity:圖檔干擾像素複雜度
	$str_font_color:文字顏色
	$str_bg_color:背景顏色
	$show:是否用圖檔
	$char_set:字元集，亂數取得將會自字元集中取出
	*/
	function initVerifyCode( $complexity = 0 , $str_font_color = "#FFFFFF" , $str_bg_color = "#000000" , $char_set = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" )
	{
		$this->set_char_set( $char_set );
		$this->complexity = $complexity;
		$this->SetFontColor( $str_font_color );
		$this->SetBackgroundColor( $str_bg_color );
		/*
		if( $show )
			$this->img_veri_code();
		*/
		/*
		echo "<pre>";
		print_r( $this->font_color );
		print_r( $this->bg_color );
		echo "</pre>";
		*/
	}
	
	function test()
	{
		echo 'tset';
	}
	
	
	//由字元集中亂數取出length個字，length預設為1
	function rand_veri_code( $length )
	{
		mt_srand( (double) microtime() * 1000000 );
	
		for( $i = 0 ; $i < $length ; $i++ )
		{
			$this->veri_code .= $this->char_set[ mt_rand( 0 , strlen( $this->char_set ) - 1 ) ];
		}
	}
	
	//設定亂數字元集
	function set_char_set( $char_set )
	{
		$this->char_set = $char_set;
	}
	
	//取得亂數字串
	function get_veri_code()
	{
		return $this->veri_code;
	}

	//將字串丟入，產生圖檔
	function img_veri_code( $length = 1 )
	{
		$single_char_width = 15;
		
		$this->rand_veri_code( $length );
		
		$img_width = $single_char_width * strlen( $this->veri_code );
		$img_height = 20;
	
		$this->im = imagecreatetruecolor( $img_width , $img_height );
		
		$fontcolor = ImageColorAllocate( $this->im , $this->font_color["r"] , $this->font_color["g"] , $this->font_color["b"] );
		
		$bgcolor = ImageColorAllocate( $this->im , $this->bg_color["r"] , $this->bg_color["g"] , $this->bg_color["b"] );
		
		imagefilledrectangle( $this->im , 0 , 0 , ImageSX( $this->im ) , ImageSY( $this->im ) , $bgcolor );
		
		for( $i = 0 ; $i < strlen( $this->veri_code ) ; $i++ )
		{
			imagestring( $this->im , $this->int_font , ( $single_char_width * $i ) + 3 , 2 , $this->veri_code[$i] , $fontcolor );
		}	
		
		//$this->img_noise_pixel();
		
		imagepng( $this->im );	
	}
	
	//加入干擾像素
	function img_noise_pixel()
	{
		srand( (double)microtime() * 1000000 );
	
		for( $i = 0 ; $i < $this->complexity ; $i++ ) 
		{ 
			$randcolor = ImageColorallocate( $this->im , rand( 0 , 255 ) , rand( 0 , 255 ) , rand( 0 , 255 ) );
			imagesetpixel( $this->im, rand() % imagesx( $this->im ) , rand() % imagesy( $this->im ) , $randcolor ); 
		}
	}
	
	//設定文字顏色
	function SetFontColor( $str_color_code )
	{
		$str_color_code = str_replace( "#" , "" , $str_color_code );
		
		if( preg_match("/^[0-9A-Fa-f]{6}$/" , $str_color_code ) )
		{
			$this->font_color["r"] = hexdec( substr( $str_color_code , 0 , 2 ) );
			$this->font_color["g"] = hexdec( substr( $str_color_code , 2 , 2 ) );
			$this->font_color["b"] = hexdec( substr( $str_color_code , 4 , 2 ) );
		}
		/*
		if( ereg( "^[0-9A-Fa-f]{6}$" , $str_color_code ) )
		{
			$this->font_color["r"] = hexdec( substr( $str_color_code , 0 , 2 ) );
			$this->font_color["g"] = hexdec( substr( $str_color_code , 2 , 2 ) );
			$this->font_color["b"] = hexdec( substr( $str_color_code , 4 , 2 ) );
		}
		*/
	}
	
	//設定背景顏色
	function SetBackgroundColor( $str_color_code )
	{
		$str_color_code = str_replace( "#" , "" , $str_color_code );
		
		if( preg_match( "/^[0-9A-Fa-f]{6}$/" , $str_color_code ) )
		{
			$this->bg_color["r"] = hexdec( substr( $str_color_code , 0 , 2 ) );
			$this->bg_color["g"] = hexdec( substr( $str_color_code , 2 , 2 ) );
			$this->bg_color["b"] = hexdec( substr( $str_color_code , 4 , 2 ) );
		}
		/*
		if( ereg( "^[0-9A-Fa-f]{6}$" , $str_color_code ) )
		{
			$this->bg_color["r"] = hexdec( substr( $str_color_code , 0 , 2 ) );
			$this->bg_color["g"] = hexdec( substr( $str_color_code , 2 , 2 ) );
			$this->bg_color["b"] = hexdec( substr( $str_color_code , 4 , 2 ) );
		}		 
		*/
	}
	
	//讀取外部字型
	function LoadFont( $str_filename )
	{
		if( imageloadfont( $str_filename ) )
		{
			$int_font = imageloadfont( $str_filename );
			array_push( $this->arr_font , $int_font );
			SetFont( $int_font );
			return $int_font;
		}
		else
			return false;
	}
	
	//設定使用字型
	function SetFont( $int_font )
	{
		if( ( $int_font >= 1 && $int_font <= 5 ) 
		 || ( in_array( $int_font , $this->arr_font ) )
		)
		{
			$this->int_font = $int_font;
		}
	}
}
?>