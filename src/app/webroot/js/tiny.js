function fileBrowserCallBack(field_name, url, type, win) {
	browserField = field_name;
	browserWin = win;
	window.open("/admin/media/tiny_images/", "browserWindow", "modal,width=1000,height=700,scrollbars=yes");
}

var tiny_mini_options = {
	script_url : WEB_ROOT+'js/ext/tiny_mce/tiny_mce.js',
	skin : "dashboard",
	file_browser_callback: "fileBrowserCallBack",
	theme : "advanced",
	plugins : "pagebreak,style,layer,advhr,advimage,advlink,iespell,inlinepopups,media,searchreplace,contextmenu,paste,directionality,visualchars,nonbreaking,xhtmlxtras,advlist",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,",
	theme_advanced_buttons2 : "pastetext,pasteword,|,search,replace,|,link,unlink,image,cleanup,code,|,forecolor,",
	theme_advanced_resizing:true,
	theme_advanced_resize_horizontal:false,
	dialog_type:"modal",
	apply_source_formatting:false,
	remove_linebreaks:true,
	paste_remove_styles:true,
	paste_remove_spans:true,
	paste_strip_class_attributes:"all",
	paste_text_use_dialog:true,
	content_css : WEB_ROOT+"css/tiny.css",
	relative_urls:false
};

$(function(){
	$('textarea.tiny').tinymce({
		// Location of TinyMCE script
		script_url : WEB_ROOT+'js/ext/tiny_mce/tiny_mce.js',
		skin : "dashboard",
        file_browser_callback: "fileBrowserCallBack",
		// General options
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,youtube",

		// Theme options
		//theme_advanced_buttons1 : "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,fullscreen,undo,redo,|,sub,sup",
		//theme_advanced_buttons2 : "link,unlink,image,cleanup,code,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,tablecontrols,",
		theme_advanced_buttons1 : "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,fullscreen,",
		theme_advanced_buttons2 : "pastetext,pasteword,|,search,replace,|,link,unlink,image,cleanup,code,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,undo,redo,|,sub,sup,|,youtube",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing:true,
		theme_advanced_resize_horizontal:false,
		dialog_type:"modal",
		apply_source_formatting:false,
		remove_linebreaks:true,
		paste_remove_styles:true,
		paste_remove_spans:true,
		paste_strip_class_attributes:"all",
		paste_text_use_dialog:true,
		//content_css : WEB_ROOT+"css/tiny.css",
		relative_urls:false,
		init_instance_callback : function(editor) {
			$("#"+editor.id + "_tbl .mceFirst:first" ).Floating();
			//console.log("Editor: " + editor.id + " is now initialized.");
		}

	});

	$('textarea.tiny-mini').tinymce(tiny_mini_options);

});
