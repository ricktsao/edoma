<?php

//fuly decode a particular string
function full_decode($string)
{
	return html_entity_decode($string, ENT_QUOTES);
}

//decode anything we throw at it
function form_decode(&$x)
{
	//loop through objects or arrays
	if(is_array($x) || is_object($x))
	{
		foreach($x as &$y)
		{
			$y = form_decode($y);
		}
	}
	
	if(is_string($x))
	{
		$x	= full_decode($x);
	}
	
	return $x;
}

function my_character_limiter($str, $n = 500, $end_char = '&#8230;')
{
	$output = substr($str, 0, $n);
	if(strlen($str)>$n){
		$output.=$end_char;
	}

	return $output;
}