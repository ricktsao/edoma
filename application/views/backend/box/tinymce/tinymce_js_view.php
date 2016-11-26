<?php
$selector_string = 'textarea#content1';
if($elements == 'do_content')
{
	$selector_string = 'textarea#content';
}
/*
if ( empty($elements) === TRUE )
{
	$selector_string = 'textarea#content';
}
else 
{
	$element_ary = explode(",", $elements);
	foreach ($element_ary as $key => $value) 
	{
		$selector_string .= ',textarea#'.$value;
	}
	
	if($selector_string != '')
	{
		$selector_string = substr($selector_string, 1);
	}
}
*/

?>

<script>
tinymce.init({
    selector: "<?php echo $selector_string;?>",
    theme: "modern",
    width: 800,
    height: 400,
	 plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],

   toolbar: "insertfile undo redo | styleselect | fontselect fontsizeselect bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage code | forecolor backcolor emoticons",
  
    add_unload_trigger: false,
    remove_linebreaks: false,

    force_p_newlines: false,
    force_br_newlines: false,
    forced_root_block: false,
    apply_source_formatting: false,
    
	//relative_urls : false,
	//remove_script_host : false,
	//convert_urls : true,
    //document_base_url: "http://192.168.20.28:8001/jx3",
    
	fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",

    font_formats:
     	"新細明體=新細明體;"+
     	"標楷體=標楷體;"+
    	"微軟正黑體=微軟正黑體;"+
    	"Andale Mono=andale mono,times;"+
        "Arial=arial,helvetica,sans-serif;"+
        "Arial Black=arial black,avant garde;"+
        "Book Antiqua=book antiqua,palatino;"+
        "Comic Sans MS=comic sans ms,sans-serif;"+
        "Courier New=courier new,courier;"+
        "Georgia=georgia,palatino;"+
        "Helvetica=helvetica;"+
        "Impact=impact,chicago;"+
        "Symbol=symbol;"+
        "Tahoma=tahoma,arial,helvetica,sans-serif;"+
        "Terminal=terminal,monaco;"+
        "Times New Roman=times new roman,times;"+
        "Trebuchet MS=trebuchet ms,geneva;"+
        "Verdana=verdana,geneva;"+
        "Webdings=webdings;"+
        "Wingdings=wingdings,zapf dingbats"
	,
   
   valid_children : "+body[style]",
   file_browser_callback : elFinderBrowser, 
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
 
 
function elFinderBrowser (field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: '<?php echo backendUrl("elfinderpop");?>',// use an absolute path!
    title: 'elFinder 2.0',
    width: 900,  
    height: 450,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
      win.document.getElementById(field_name).value = url.url;
    }
  });
  return false;
}
 

 
</script>
