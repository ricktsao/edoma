// JavaScript Document
//同一名稱的checkbox全選
function SelectAll( str_checkbox_name )
{
	var checkboxs = document.getElementsByName( str_checkbox_name );
	
	for( i = 0 ; i < checkboxs.length ; i++ )
	{
		if( !checkboxs[i].disabled )
			checkboxs[i].checked = true;
	}
}

//同一名稱的checkbox全不選
function UnselectAll( str_checkbox_name )
{
	var checkboxs = document.getElementsByName( str_checkbox_name );
	
	for( i = 0 ; i < checkboxs.length ; i++ )
	{
		if( !checkboxs[i].disabled )
			checkboxs[i].checked = false;
	}
}

//對同一名稱的checkbox做反向選擇
function ReverseSelect( str_checkbox_name )
{
	var checkboxs = document.getElementsByName( str_checkbox_name );
	
	for( i = 0 ; i < checkboxs.length ; i++ )
	{
		if( !checkboxs[i].disabled )
		{
			if( checkboxs[i].checked == false )
				checkboxs[i].checked = true;
			else
				checkboxs[i].checked = false;
		}
	}
}

/*
20090429 
判斷格式
YES, return true
No, return false
*/
function CheckFormat( str_obj_id , str_re , str_err_msg )
{	
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );

	if ( obj_name.value.match( str_re ) ) 
	{
		obj_alert.innerHTML = '';
		obj_alert.style.visibility = "hidden";
		return true;
	}
	else
	{
		obj_alert.innerHTML = str_err_msg;
		obj_alert.style.visibility = "visible";
		obj_name.focus();
		return false;
	}
}

/*
20090413 
判斷傳入的欄位長度是否超過限制, 
YES, return true
No, return false
*/
function CheckLength( str_obj_id , int_max )
{	
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );

	if( obj_name.value.length <= int_max )
	{	
		obj_alert.innerHTML = '';
		return true;	
	}
	else
	{
		obj_alert.innerHTML = "最大長度為" + int_max + "個字元";
		obj_alert.style.visibility = "visible";
		obj_name.focus();
		return false;
	}
}

/*
20090413 
判斷傳入的欄位長度是否超過限制, 
YES, return true
No, return false
*/
function CheckMinLength( str_obj_id , int_min )
{	
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );

	if( obj_name.value.length >= int_min )
	{	
		obj_alert.innerHTML = '';
		return true;	
	}
	else
	{
		obj_alert.innerHTML = "最短長度為" + int_min + "個字元";
		obj_alert.style.visibility = "visible";
		obj_name.focus();
		return false;
	}
}

/*
20090413 
判斷傳入的欄位有沒有字元, 
YES, return true
No, return false
*/
function CheckEmpty( str_obj_id )
{	
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );
	if( trim( obj_name.value ).length > 0 )
	{	
		obj_alert.innerHTML = '';
		return true;	
	}
	else
	{
		obj_alert.innerHTML = "必要輸入欄位";
		obj_alert.style.visibility="visible";
		obj_name.focus();
		return false;
	}
}
/*
判斷傳入的 file 欄位值, 其有沒有符合允許可上傳的檔案型態 
YES, return true
No, return false
*/
function CheckFileExt( str_obj_id , str_valid_ext )
{
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );
	
	var int_pos = obj_name.value.lastIndexOf( '.' ) + 1;
	var str_upload_ext = obj_name.value.substring( int_pos , obj_name.value.length );
	
	if( str_valid_ext.indexOf( str_upload_ext.toLowerCase() ) > -1 )
	{
		obj_alert.innerHTML = '';
		return true;
	}
	else
	{
		str_valid_ext = str_valid_ext.replace( new RegExp( ',' , "gm" ) , '、' );
		obj_alert.innerHTML = "不接受此類型檔案格式。僅接受" + str_valid_ext + "等檔案格式";
		obj_alert.style.visibility="visible";
		return false;
	}
}

/*
判斷傳入的欄位值是否為 int_limit 位數的數字型態 
YES, return true
No, return false
*/
function CheckInt( str_obj_id , int_min , int_max )
{	
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );

	//if ( obj_name.value.match( /^[0-9]+$/ ) && parseInt( obj_name.value ) <= int_limit ) 
	if( CheckFormat( str_obj_id , /^[0-9]+$/ , "" ) && CheckIntRange( str_obj_id , int_min , int_max ) )
	{
		obj_alert.innerHTML = '';
		obj_alert.style.visibility = "hidden";
		return true;
	}
	else
	{
		obj_alert.innerHTML = "請輸入最小值"+ int_min +"，最大值" + int_max + "的數字";
		obj_alert.style.visibility="visible";
		obj_name.focus();
		return false;
	}
}

/*
判斷傳入的欄位值是否為 int_limit 位數的數字型態 
YES, return true
No, return false
*/
function CheckIntRange( str_obj_id , int_min , int_max )
{
	var obj_name = document.getElementById( str_obj_id );
	var obj_alert = document.getElementById( str_obj_id + '_alert' );

	//if ( obj_name.value.match( /^[0-9]+$/ ) && parseInt( obj_name.value ) <= int_limit ) 
	if( parseInt( obj_name.value ) >= int_min && parseInt( obj_name.value ) <= int_max )
	{
		obj_alert.innerHTML = '';
		obj_alert.style.visibility = "hidden";
		return true;
	}
	else
	{
		obj_alert.innerHTML = "請輸入最小值"+ int_min +"，最大值" + int_max + "的數字";
		obj_alert.style.visibility="visible";
		obj_name.focus();
		return false;
	}
}

/*
20090429 
判斷傳入的欄位值是否為大寫英文
YES, return true
No, return false
*/
function CheckEmail( str_obj_id )
{	
	return CheckFormat( str_obj_id , /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$/ , "請輸入正確的E-Mail格式" );
}


/*
20090429 
判斷傳入的欄位值是否為大寫英文
YES, return true
No, return false
*/
function CheckUpperCaseChar( str_obj_id )
{	
	return CheckFormat( str_obj_id , /^[A-Z]+$/ , "請輸入大寫英文" );
}

/*
20090429 
判斷傳入的欄位值是否為小寫英文
YES, return true
No, return false
*/
function CheckLowerCaseChar( str_obj_id )
{	
	return CheckFormat( str_obj_id , /^[a-z]+$/ , "請輸入小寫英文" );
}

/*
20090429 
判斷傳入的欄位值是否為英文
YES, return true
No, return false
*/
function CheckEnChar( str_obj_id )
{	
	return CheckFormat( str_obj_id , /^[a-zA-Z]+$/ , "請輸入英文" );
}

/*
20090429 
判斷傳入的欄位值是否為英文+數字
YES, return true
No, return false
*/
function CheckEnCharAndInt( str_obj_id )
{	
	return CheckFormat( str_obj_id , /^[a-zA-Z0-9]+$/ , "請輸入英文及數字" );
}

function CheckDomain( string )
{
	str_re = /^((([a-zA-Z].)[a-zA-Z0-9\-\.]+\.(com|edu|gov|mil|net|org|biz|info|name|museum)(\.([a-zA-Z]{1,}))*)|((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])+\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)+\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)+\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])))(:\d+)?$/;
	
	if ( string.match( str_re ) ) 
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
20090416  
Input: 
		fid: 結束日期 text 的 id
		btnid: 結束日期 call calandar 的按鈕 id
		cbkid: 勾選永久有效的 check box id
用途:
		當選擇永久有效時, 結束日期 text 及 按鈕 設定為無法使用,
		反之, 復原.
*/
function switch_enddate(fid, btnid , cbkid)
{	
	if(document.getElementById(cbkid).checked==true)
	{
		document.getElementById(fid).value='';
		document.getElementById(fid).style.backgroundColor = '#cccccc';
		document.getElementById(btnid).disabled=true;
	}
	else
	{
		document.getElementById(fid).style.backgroundColor = '#FFFFFF';
		document.getElementById(btnid).disabled=false;
	}
}

/*
20090429 
判斷Forever及結束時間的狀態
YES, return true
No, return false
*/
function CheckForever( str_forever_cbx , str_enddate_text_id , str_startdate_text_id )
{
	var obj_name = document.getElementById( str_enddate_text_id );
	var obj_alert = document.getElementById( str_enddate_text_id + '_alert' );
	
	if( document.getElementById( str_forever_cbx ).checked )
	{
		obj_alert.innerHTML = '';
		obj_alert.style.visibility = "hidden";
		return true;
	}
	else
	{
		if( CheckEmpty( 'end_date' ) == false )
		{
			return false;
		}
		else
		{
			if( !CheckStartAndEndDate( str_startdate_text_id , str_enddate_text_id ) )
			{
				return false;
			}
			else
			{
				obj_alert.innerHTML = '';
				obj_alert.style.visibility = "hidden";
				return true;
			}
		}
	}
}

/*
20090429 
判斷Forever及結束時間的狀態
YES, return true
No, return false
*/
function CheckStartAndEndDate( str_startdate_text_id , str_enddate_text_id )
{
	var obj_start = document.getElementById( str_startdate_text_id );
	var obj_end = document.getElementById( str_enddate_text_id );
	var obj_alert = document.getElementById( str_enddate_text_id + '_alert' );
	
	if( obj_start.value < obj_end.value )
	{
		obj_alert.innerHTML = '';
		obj_alert.style.visibility = "hidden";
		return true;
	}
	else
	{
		obj_alert.innerHTML = '結束時間需大於啟始時間';
		obj_alert.style.visibility = "visible";
		return false;
	}
}

/*
20090910 
檔案選擇方塊鍵盤事件
*/
function CheckFileKeydown( str_keycode )
{
	switch( str_keycode )
	{
		case 46:
		case 8:	
				break;
				
		default:
				return false;
				break;
	}
}

function ResetValue( obj_id )
{
	document.getElementById( obj_id ).value = "";
}

function CheckDelete( cbk , file )
{
	if( cbk.checked )
	{
		document.getElementById( file ).disabled = true;
		document.getElementById( file ).className = 'text_disabled';
	}
	else
	{
		document.getElementById( file ).disabled = false;
		document.getElementById( file ).className = 'text_enabled';
	}
}
/*20120928 list_view 權限增加 ，集體刪除
 targetFrom : 目標的form
 targetAction : 目的網址
 message:顯示訊息*/
 
$(function(){
	$('table th input:checkbox').on('click' , function(){						

		var that = this;
		$(this).closest('table').find("input[name='del[]']")
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});						
	});
})
function listViewAction(targetFrom,targetAction,message){
	var comf=false
	if(message!=null){
		 if (confirm(message)){
		 	comf=true;
		 }		
	}else{
		comf=true;	
	}
	
	if(comf){
		$(targetFrom).attr('action',targetAction).submit();		
	}	

}

//省略打slef

function jUrl(url){
		location.href=url;
}

function Delete(url) 
{
	if (confirm("是否確定刪除?"))
	{
		var query_string = url;	
		document.getElementById( "update_form" ).action = query_string;	
		document.getElementById( "update_form" ).submit();     
	}
}


function Delete2(form_id, url) 
{
	if (confirm("是否確定刪除?? "+ form_id))
	{
		var query_string = url;	
		document.getElementById( form_id ).action = query_string;	
		document.getElementById( form_id ).submit();     
	}
}

