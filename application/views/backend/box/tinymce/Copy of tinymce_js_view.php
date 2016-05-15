<?php
if ( empty($elements) === TRUE )
{
	$elements = 'content';
}

?>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode: "exact",
        elements: "<?php echo $elements;?>",
        theme: "advanced",
        language: "zh-tw",
        height : "380",
        theme_advanced_resizing : true,

        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
        // Theme options
        theme_advanced_buttons1: "preview,|,bullist,numlist,|,outdent,indent,|,fontselect,fontsizeselect,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor",
        theme_advanced_buttons2: "undo,redo,|,tablecontrols,|,sub,sup,|,link,image,netImageBrowser,|,code",
        theme_advanced_buttons3: "",

        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",

        entity_encoding: "raw",
        add_unload_trigger: false,
        remove_linebreaks: false,

        force_p_newlines: false,
        force_br_newlines: false,
        forced_root_block: false,
        apply_source_formatting: false,

        // Example content CSS (should be your site CSS)
        //content_css: "css/content.css",

		valid_children : "+body[style]",

        file_browser_callback : "elFinderBrowser",


        // Style formats
        style_formats: [
            { title: 'Bold text', inline: 'b' },
            { title: 'Red text', inline: 'span', styles: { color: '#ff0000'} },
            { title: 'Red header', block: 'h1', styles: { color: '#ff0000'} },
            { title: 'Example 1', inline: 'span', classes: 'example1' },
            { title: 'Example 2', inline: 'span', classes: 'example2' },
            { title: 'Table styles' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
        ],

        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });


	function elFinderBrowser (field_name, url, type, win) {
	  var elfinder_url = '<?php echo backendUrl("elfinderpop");?>';    // use an absolute path!
	  tinyMCE.activeEditor.windowManager.open({
	    file: elfinder_url,
	    title: 'elFinder 2.0',
	    width: 900,  
	    height: 450,
	    resizable: 'yes',
	    inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
	    popup_css: false, // Disable TinyMCE's default popup CSS
	    close_previous: 'no'
	  }, {
	    window: win,
	    input: field_name
	  });
	  return false;
	}
</script>