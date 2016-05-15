
var arr_img_type=['png','jpg','jpeg','bmp','gif'];


$(document).ready( function() {
	
	// Hide all subfolders at startup
	$(".php-file-tree").find("UL").hide();
	
	// Expand/collapse on click
	$(".pft-directory A").click( function() {
		$(this).parent().find("UL:first").slideToggle("medium");
		if( $(this).parent().attr('className') == "pft-directory" ) return false;
	});
});

var temp_img;

function show_img(img,path,url_root){

	temp_img="http://"+url_root+img.replace(path,'');
	console.log(temp_img);
	var isImg=false;
	var file_type=img.split(".");
	file_type = file_type[file_type.length-1].toLowerCase();

	for(var i=0; i<arr_img_type.length;i++){
		if(file_type==arr_img_type[i]){
			isImg=true;
			break;
		}
	}
	
	if(isImg){

		//img="http://"+url_root+img.replace(path,'');

		$('#img_view').html("<img src='"+temp_img+"'/>");

		$('.select_img').click(function(){
			parent.getFilename(temp_img);
			//console.log(temp_img);
		})

	}else{

		parent.getFilename(temp_img);
	}

	


}