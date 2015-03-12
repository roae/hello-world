/*
* L10nTools - jQuery prototype
* @version: 0.1
* Controla las herramientas de edicion de contenido
*
* http://jquery.com
*
*/

/**
 * Constructor
 */
$.L10nTools=function(){
	var me=$.L10nTools;
	$('#L10nRibbonBar a[rel!=""]').click(me.click);

	me.editElement=$("<span />",{id:'L10nEditElement',text:__("System.dblclick_to_edit")}).data("hidden",true).appendTo("body");
	$("body").append();
	$("[data-l10nid]").on({
		click:me.clickToEdit,
		/*click:function(e){
			//console.dir(e);
			//console.log("click");
			if($(this).eq(0)[0].tagName.toUpperCase() == "SELECT"){
				$.proxy(this,me.clickToEdit);
				//e.stopPropagation();
			}
			//return false;
		},*/
		dblclick:me.clickToEdit,
		mouseenter:function(e){
			//console.log("rochin");
			if(me.editElement.data("hidden")){
				var $this=$(this),left=$this.offset().left,top=$this.offset().top;
				//console.dir(e);
				me.editElement.css({
					'display':'block',
					'top':e.pageY+10,
					'left':e.pageX+10,
				})
				.data("hidden",false);
			}
		},
		mousemove:function(e){
			me.editElement.css({'top':e.pageY+10,'left':e.pageX+10});
		},
		mouseleave:function(e){
			me.editElement.css({display:'none'}).data("hidden",true);
		}
	});
}
$.L10nTools.element=null;

$.L10nTools.clickToEdit = function(e){
	//console.dir(e);
	if(($(this).eq(0)[0].tagName.toUpperCase() != "SELECT" && e.type=="dblclick") || (e.type == "click" && $(this).eq(0)[0].tagName.toUpperCase() == "SELECT")){
		$("#"+$(this).data("l10nid")).dialog({
			modal:true,draggable:false,width:400,height:"auto",resize:false,closeOnScape:true,show:{effect:'scale',duration:100},hide:{effect:'scale',duration:100},
			buttons:[
				{
					text:'acept',
					click:function(){
						$(this).dialog("close");
					}
				}
			]
		});
	}
};

$.L10nTools.click=function(){
	if(!$(this).hasClass("toolDisabled")){
		if(this.rel=="createLink"){
			$.L10nTools.createLink();
		}else if(this.rel=='unlink' && $(window.getSelection().focusNode).parent()[0].tagName=="A"){
			$(window.getSelection().focusNode).unwrap();
			$.L10nTools.element.L10n("update");
		}else if(this.rel=="insertImg"){
			$.L10nTools.insertImg();
		}else{
			$.L10nTools.execCommand(this.rel,this.rev);
			$.L10nTools.update();
		}
	}
	return false;
};

$.L10nTools.insertImg=function(){
	$window=$('<div />',{title:__('window_img_insert_title'),'class':'L10nImgWindow'}).createAppend([
				'iframe',{'src':'/admin/media/insert:true/?L10n=false','id':'L10nMediaIframe','style':'width:100%; height:100%;'},[]
			]).dialog({
				modal:true,width:($(window).width()-100),height:($(window).height()-40),resizable:true,autoOpen:false,
				close:function(){
					$(this).remove();
				},
				create:function(){
				},
				buttons:[
					{html:__("cancel"),click:function(){
						$(this).dialog("close");
					}},
					{html:__("select_img"),click:function(){
					}}
				]
			});
	$window.dialog("open");
	$("#L10nMediaIframe").load(function(){
		//$(".file",this).click(function(){console.log("click")});
		//console.dir(this);
		//console.log("richn");
		/*console.dir(this.contentWindow);
		$(this.contentDocument).ready(function(){
			this.contentWindow.clickFile=function(){
				$(".file").click(function(){
					console.log("rochin");
				});
			}
		})*/
	});
	//window.open("/admin/media?L10n=false","browserWindow","modal,width=1024,height:768,scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no");
};

$.L10nTools.createLink=function(){
	//console.dir($.L10nTools.element);
	var selection=window.getSelection();
	var link=$(window.getSelection().focusNode).parent()[0].tagName=="A"; // ya es un link?
	var $link=$(window.getSelection().focusNode).parent(); // se obtiene el padre el contenido seleccionado
	if(!selection.isCollapsed && !link){ // si hay texto seleccionado y no hay link
		var text=selection.getRangeAt(0).cloneContents().textContent; // se obtiene el texto
		$.L10nTools.insertNodeAtSelection($('<a />',{'class':'L10nLink',html:text})); // se remplaza la seleccion por el link
		$link=$('.L10nLink');
	}
	$window=$('<div />',{title:__('System.window_link_title')}).createAppend([
				'label',{},__('System.L10n_url_link_label'),
				'input',{id:'System.L10nLinkHref',type:'text',value:"http://"},[],
				'label',{},__('System.L10n_title_link'),
				'input',{id:'L10nLinkTitle',type:'text'},[],
				'label',{},__('System.L10n_rel_link'),
				'input',{id:'L10nLinkRel',type:'text'},[],
				'input',{id:'L10nLinkTarget',type:'checkbox'},[],
				'label',{'for':'L10nLinkTarget'},__('System.L10n_target_link'),
			]).dialog({
				modal:true,width:400,resizable:false,autoOpen:false,
				close:function(){
					if($link && !link){
						$link.contents().unwrap();
					}
					$(this).remove();
				},
				create:function(){
					if(link){ // si ya es un link se ponen los atributos del link en el formulario
						$("#L10nLinkHref").attr("value",$link.attr("href"));
						$("#L10nLinkTitle").attr("value",$link.attr("title"));
						$("#L10nLinkRel").attr("value",$link.attr("rel"));
						$("#L10nLinkTarget").attr("checked",$link.attr("target")=="_blank");
					}
				},
				buttons:[
					{html:__("cancel"),click:function(){
						if(!link){ // si no es un link se eliminara el link creado
							$link.contents().unwrap();
						}
						$(this).dialog("close");
					}},
					{html:__("acept"),click:function(){
						// se modifican los atributos del link por los proporsionados en el formulario
						//target=($("#L10nLinkTarget").attr("checked")) ? '_blank' : '_self';
						//console.log(target);
						var attr={href:$('#L10nLinkHref').val()};
						if($('#L10nLinkTitle').val()!=""){
							$.extend(attr,{'title':$('#L10nLinkTitle').val()});
						}else{
							$link.removeAttr("title");
						}
						if($("#L10nLinkRel").val()!=""){
							$.extend(attr,{'rel':$("#L10nLinkRel").val()});
						}else{
							$link.removeAttr("rel");
						}
						if($("#L10nLinkTarget").is(":checked")){
							$.extend(attr,{'target':'_blank'});
						}else{
							$link.removeAttr("target");
						}

						$link.attr(attr).removeAttr('class');
						$link=null;
						$(this).dialog("close");
						$.L10nTools.element.L10n("update");
					}}
				]
			});
	$window.dialog("open");
}

$.L10nTools.insertNodeAtSelection=function(insertNode){
	//console.log(insertNode);
	insertNode=insertNode[0];
	//console.log(insertNode);
	// get current selection
	var sel = window.getSelection();

	// get the first range of the selection
	// (there's almost always only one range)
	var range = sel.getRangeAt(0);

	// deselect everything
	sel.removeAllRanges();

	// remove content of current selection from document
	range.deleteContents();

	// get location of current selection
	var container = range.startContainer;
	var pos = range.startOffset;

	// make a new range for the new selection
	range=document.createRange();

	if (container.nodeType==3 && insertNode.nodeType==3) {

		// if we insert text in a textnode, do optimized insertion
		container.insertData(pos, insertNode.nodeValue);

		// put cursor after inserted text
		range.setEnd(container, pos+insertNode.length);
		range.setStart(container, pos+insertNode.length);

	} else {


		var afterNode;
		if (container.nodeType==3) {

			// when inserting into a textnode
			// we create 2 new textnodes
			// and put the insertNode in between

			var textNode = container;
			container = textNode.parentNode;
			var text = textNode.nodeValue;

			// text before the split
			var textBefore = text.substr(0,pos);
			// text after the split
			var textAfter = text.substr(pos);

			var beforeNode = document.createTextNode(textBefore);
			afterNode = document.createTextNode(textAfter);

			// insert the 3 new nodes before the old one
			container.insertBefore(afterNode, textNode);
			container.insertBefore(insertNode, afterNode);
			container.insertBefore(beforeNode, insertNode);

			// remove the old node
			container.removeChild(textNode);

		} else {

			// else simply insert the node
			afterNode = container.childNodes[pos];
			container.insertBefore(insertNode, afterNode);
		}

		range.setEnd(afterNode, 0);
		range.setStart(afterNode, 0);
	}

	sel.addRange(range);
};


$.L10nTools.update=function(){
	var me=$.L10nTools;

	$('#L10nRibbonBar a[rev="'+me.commandValue('heading')+'"]').addClass("active");
	$('#L10nRibbonBar a[rev!="'+me.commandValue('heading')+'"]').removeClass("active");
	if(!me.commandValue('heading')){
		$('#L10nRibbonBar a[rel="normal"]').addClass("active")
	}

	var commands=[
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'justifyleft',
			'justifyright',
			'justifyfull',
			'insertorderedlist',
			'insertunorderedlist',
			'subscript',
			'superscript',
		];
	$.each(commands,function(k,command){
		var button=$('#L10nRibbonBar a[rel="'+command+'"]');
		if(me.commandState(command)){
			button.addClass("active");
		}else{

			button.removeClass("active");
		}
	});
	//console.log(me.element.parent().get(0).tagName.toLowerCase());
	var tag=me.element.parent().get(0).tagName.toUpperCase();
	var block=/^(DIV|FORM|T(ABLE|BODY|HEAD|FOOT|H|R|D)|LI|OL|UL|CAPTION|BLOCKQUOTE|CENTER|DL|DT|DD|DIR|FIELDSET|NOSCRIPT|MENU|ISINDEX|SAMP|ARTICLE|NAV|SECTION|FOOTER|HEADER|HGROUP)$/;

	if(!block.test(tag)){
		$("#L10nRibbonBar .block").addClass("toolDisabled");
		if(tag=="A"){
			$("#L10nRibbonBar .link").addClass("toolDisabled");
		}
	}else{
		$("#L10nRibbonBar .tool").removeClass("toolDisabled");
	}

}

$.L10nTools.execCommand=function(command,param){
	var me=$.L10nTools;
	if(me.element){
		me.element.focus();
	}
	try{
		document.execCommand(command, false, param);
	}catch(e){}
};

$.L10nTools.commandState=function(command){
	try{
		return document.queryCommandState(command);
	}catch(e){}
},
$.L10nTools.commandValue=function(value){
	try{
		return document.queryCommandValue(value);
	}catch(e){}
}

$.L10nTools.setElement=function(e){
	$.L10nTools.element=e;
}



/**
 * Crea la barra de herramientas del editor de contenido
 */
$(function(){
	var langs="",count=0;
	$.each(I18nLocale,function(k,v){
		langs+="<option value='"+k+"'>"+__(v)+"</option>";
		count++;
	});
	/*if(count>1){
		langs="<option value=''>"+__("select_a_lang")+"</option>"+langs;
	}*/
	$("body").css({paddingBottom:"75px"});
	$("body").createAppend(
		'div',{id:'L10nRibbonBar'},[
			'div',{className:'ribbonContent',id:'ribbonContent'},[
				'div',{className:'ribbonCenter clearfix'},[
					'div',{className:'RibbonTabsTools'},[
						'a',{className:'showBar',id:'L10nRibbonBarShowMissings',href:'#'},__("System.show_missing_keys"),
						'a',{className:'showBar',id:'L10nRibbonBarHiderShower',href:'#'},__("System.hide_bar")
					],
					'div',{className:'ribbonSection'},[
						'span',{className:'ribbonSecTitle'},__("System.Font"),
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.title_bold",true),className:'min left tool',href:'#',rel:'bold'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/bold.png'},'',
							],
							'a',{title:__("System.title_italic",true),className:'min tool',href:'#',rel:'italic'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/italic.png'},'',
							],
							'a',{title:__("System.title_underline",true),className:'min right tool',href:'#',rel:'underline'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/underline.png'},'',
							],
							'br',{className:'spacer'},"",
							'a',{title:__("System.title_strike_trough",true),className:'min left tool',href:'#',rel:'strikethrough'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/strike_trough.png'},'',
							],
							'a',{title:__("System.title_subscript",true),className:'min tool',href:'#',rel:'subscript'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/subscript.png'},'',
							],
							'a',{title:__("System.title_superscript",true),className:'min right tool',href:'#',rel:'superscript'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/superscript.png'},'',
							],

							/*'select',{style:'width:115px;margin-right:4px;',className:'toolSelect',rel:'fontName'},[
								"option",{value:''},__("Font_types"),
								"option",{value:'Arial, Helvetica, sans-serif'},"Arial, Helvetica, sans-serif",
								"option",{value:'"Times New Roman", Times, serif'},"Times New Roman, Times, serif",
								"option",{value:'Georgia, "Times New Roman", Times, serif'},"Georgia, Times New Roman, Times, serif",
								"option",{value:'"Courier New", Courier, monospace'},"Courier New, Courier, monospace",
								"option",{value:'Verdana, Arial, Helvetica, sans-serif'},"Verdana, Arial, Helvetica, sans-serif",
								"option",{value:'Geneva, Arial, Helvetica, sans-serif'},"Geneva, Arial, Helvetica, sans-serif"
							],
							'select',{className:'toolSelect',href:'#',style:'width:44px;',rel:'fontSize'},[
								'option',{value:'1'},'8',
								'option',{value:'2'},'9',
								'option',{value:'3'},'10',
								'option',{value:'4'},'11',
								'option',{value:'5'},'12',
								'option',{value:'6'},'14',
								'option',{value:'7'},'16',
								'option',{value:'8'},'18',
								'option',{value:'9'},'20',
								'option',{value:'10'},'22',
								'option',{value:'11'},'24',
								'option',{value:'12'},'26'
							]*/
						]
					],
					'div',{className:'ribbonSeparator'},"&nbsp;",
					'div',{className:'ribbonSection'},[
						'span',{className:'ribbonSecTitle'},__("System.Paragraph"),
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.bulleted_list",true),className:'min left block tool',href:'#',rel:'insertunorderedlist'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/bulleted_list.png'},'',
							],
							'a',{title:__("System.numbered_list",true),className:'min block tool',href:'#',rel:'insertorderedlist'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/numbered_list.png'},'',
							],
							'a',{title:__("System.increase_indent",true),className:'min block tool',href:'#',rel:'indent'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/increase_indent.png'},'',
							],
							'a',{title:__("System.decrease_indent",true),className:'min right block tool',href:'#',rel:'outdent'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/decrease_indent.png'},'',
							],
							'br',{className:'spacer'},"",
							'a',{title:__("System.align_left",true),className:'min left tool',href:'#',rel:'justifyleft'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/align_left.png'},'',
							],
							'a',{title:__("System.align_right",true),className:'min tool',href:'#',rel:'justifyright'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/align_right.png'},'',
							],
							'a',{title:__("System.align_justify",true),className:'min tool',href:'#',rel:'justifyfull'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/align_justify.png'},'',
							],
							'a',{title:__("System.align_center",true),className:'min right tool',href:'#',rel:'justifycenter'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/align_center.png'},'',
							],
						]
					],
					'div',{className:'ribbonSeparator'},"&nbsp;",
					'div',{className:'ribbonSection styles'},[
						'span',{className:'ribbonSecTitle'},__("System.Styles"),
						'a',{className:'stylePrev',id:'ribbonPrevStyle',href:'#'},'',
						'a',{className:'styleNext',id:'ribbonNextStyle',href:'#'},'',
						'div',{className:'stylesCarrousel'},[
							'div',{'class':'slideContainer'},[
								'div',{'class':'slide',id:'ribbonSlide'},[
									'a',{className:'style tool',href:'#',title:__("System.Normal",true),rel:'normal'},[
										'span',{className:'previewStyle normal'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Normal")
									],
									'a',{className:'style block tool',href:'#',title:__("System.Paragraph",true),rel:'insertparagraph',rev:'p'},[
										'span',{className:'previewStyle paragraph'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},"Paragraph"
									],
									'a',{className:'style block tool',href:'#',title:__("System.Header",true)+' 1',rel:'heading',rev:'h1'},[
										'span',{className:'previewStyle header1'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 1'
									],
									'a',{className:'style block tool',href:'#',title:__("System.Header",true)+' 2',rel:'heading',rev:'h2'},[
										'span',{className:'previewStyle header2'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 2'
									],
									'a',{className:'style block tool',href:'#',title:__("System.Header",true)+' 3',rel:'heading',rev:'h3'},[
										'span',{className:'previewStyle header3'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 3'
									],
									'a',{className:'style block tool',href:'#',title:__("System.Header",true)+' 4',rel:'heading',rev:'h4'},[
										'span',{className:'previewStyle header4'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 4'
									],
									'a',{className:'style block tool',href:'#',title:__("System.System.Header",true)+' 5',rel:'heading',rev:'h5'},[
										'span',{className:'previewStyle header5'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 5'
									],
									'a',{className:'style block tool',href:'#',title:__("System.Header",true)+' 6',rel:'heading',rev:'h6'},[
										'span',{className:'previewStyle header6'},"AaBbCcDdEeFf",
										'span',{className:'styleName'},__("System.Header",true)+' 6'
									]
								]
							]
						]
					],
					'div',{className:'ribbonSeparator'},"&nbsp;",
					'div',{className:'ribbonSection'},[
						'span',{className:'ribbonSecTitle'},__("System.Tools"),
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.insert_link",true),className:'min left link tool',href:'#',rel:'createLink',rev:'#'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/insert_link.png'},'',
							],
							'a',{title:__("System.remove_link",true),className:'min right link tool',href:'#',rel:'unlink'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/remove_link.png'},'',
							],
							'br',{className:'spacer'},"",
							'a',{title:__("System.select_all",true),className:'min left tool',href:'#',rel:'selectAll'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/select_all.png'},'',
							],
							'a',{title:__("System.remove_format",true),className:'min right tool',href:'#',rel:'removeFormat'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/remove_format.png'},'',
							],
							'br',{className:'spacer'},"",
							/*'a',{title:__("System.undo",true),className:'min left',href:'#',rel:'undo'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/undo.png'},'',
							],
							'a',{title:__("System.redo",true),className:'min right',href:'#',rel:'redo'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/redo.png'},'',
							],
							'a',{title:__("System.copy",true),className:'min',href:'#',rel:'copy'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/copy.png'},'',
							],
							'a',{title:__("System.cut",true),className:'min right',href:'#',rel:'cut'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/cut.png'},'',
							],*/
						],
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.Insert_image",true),className:'big tool',href:'#',rel:'insertImg'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/insert_image.png'},'',
								'span',{},"Image"
							]
						],
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.title_help",true),className:'big help',href:'#',id:'L10nHelpButton'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/help.png'},'',
								'span',{},"Help"
							]
						],
					],
					'div',{className:'ribbonSeparator'},"&nbsp;",
					'div',{className:'ribbonSection'},[
						'span',{className:'ribbonSecTitle'},__("System.Finalization"),
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.cancel",true),className:'big',id:'L10nCancelButton',href:'/admin/i18n/l10n/cancel/'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/cancel.png'},'',
								'span',{},"Cancel"
							]
						],
						'div',{className:'ribbonGroup'},[
							'form',{id:'L10nForm',action:"/admin/i18n/l10n/edit"},[
								'label',{'for':"L10nSelectLang"},__("System.select_a_language"),
								'select',{id:"L10nSelectLang",style:'width:118px;',name:'data[Lang][locale]'},langs
							]
						],
						'div',{className:'ribbonGroup'},[
							'a',{title:__("System.Save_content",true),id:'L10nSaveButton',className:'big',href:'/admin/i18n/l10n/edit/'},[
								'img',{alt:'',src:WEB_ROOT+'i18n/img/save.png'},'',
								'span',{},"Save"
							]
						],
					]
				],
				'div',{className:'ribbonTabs'},[
					'div',{id:'L10nMissingKeysTabs'},[
						'ul',{id:'ribbonTabs'},[
							'li',{},[
								'a',{href:"#L10nMetas"},__("System.tab_Meta_datos")
							],
							/*'li',{},[
								'a',{href:"#L10nElements"},__("System.tab_Elementos")
							],
							'li',{},[
								'a',{href:"#L10nJs"},__("System.tab_js_keys")
							],
							'li',{},[
								'a',{href:"#L10nMissing"},__("System.tab_missings")
							],*/
						]
					]
				]
			]
		]
	);
	// se pasan los lis de los tabs personalisados
	$("#ribbonTabs").append($("#I18nTabNames li"));
	$("#I18nTabNames").remove();
	$("#ribbonTabs a").each(function(){
		href=$(this).attr("href");
		$(this).attr("href","#"+href.substr(1, href.length - 1)); // se le quita el primer caracter por que misteriosamente siempre llega un espacio
	});
	// se pasan los los divs que tienen las faltantes
	$(".L10nTab").appendTo("#L10nMissingKeysTabs");
	$("#L10nMissingKeysTabs").tabs();
	$("body").append($('<div>',{className:'L10nLoad',id:'L10nLoad'})); // se agrega el div loading

	var keyCtrlPress,changeLocationToEdit=false;
	$(document).bind("keydown","ctrl",function(){
		keyCtrlPress=true;
	}).bind("keyup","ctrl",function(){
		keyCtrlPress=false;
	});

	$('a[href!="#"]').click(function(){
		var $link=$(this);
		if(keyCtrlPress){
			$("<div/>",{html:__("System.leave_this_page_quest"),title:'message'}).dialog({
				modal:true,
				width:400,
				resizable:false,
				buttons:[{
					text:__("System.save_and_leave",true),
					click:function(){
						$(this).dialog("close");
						changeLocationToEdit=$link.attr("href");
						$("#L10nSaveButton").click();
					}
				},{
					text:__("System.only_leave",true),
					click:function(){
						$(this).dialog("close");
						window.onbeforeunload=null;
						location=$link.attr("href");
					}
				},{
					text:__("System.cancel",true),
					click:function(){
						$(this).dialog("close");
					}
				}]
			});
		}
	});

	$("#L10nSaveButton").click(function(){

			window.onbeforeunload=null;
			$("#L10nLoad").fadeIn();
			var finalizar=(changeLocationToEdit) ? "/continue:1":"/";
			$.ajax({
				url:$("#L10nForm").attr('action')+finalizar,
				type:'post',
				data:$("#L10nForm").serialize(),
				success:function(){
					$("#L10nLoad").fadeOut();
					$("<div/>",{html:__("System.contect_saved_successfully"),title:'message'}).dialog({
						modal:true,
						width:300,
						resizable:false,
						buttons:[{
							text:__("ok",true),
							click:function(){
								if(changeLocationToEdit){
									location=changeLocationToEdit;
								}else{
									location.reload();
								}
							}
						}]
					});
				}
			});
		//$("#L10nForm").submit();
		return false;
	});

	$("#L10nCancelButton").click(function(){
		window.location=$(this).attr("href");
		return false;
	});

	$("#L10nHelpButton").click(function(){
		$('<div />',{title:__('L10nHelpDialogTitle')}).createAppend([
			'div',{id:'L10nHelpTabs'},[
				'ul',{},[
					'li',{},['a',{href:'#L10nHelpBasic'},__("System.L10nHelpBasicTab")],
					'li',{},['a',{href:'#L10nHelpAdvanced'},__("System.L10nHelpAdvancedTab")],
					'li',{},['a',{href:'#L10nHelpShortcuts'},__("System.L10nHelpShotcutsTab")],
				],
				'div',{id:'L10nHelpBasic'},__("System.L10nHelpBasicTabContent"),
				'div',{id:'L10nHelpAdvanced'},__("System.L10nHelpAdvancedTabContent"),
				'div',{id:'L10nHelpShortcuts'},__("System.L10nHelpShortcutsTabContent"),
			]
		]).dialog({
			modal:true,width:500,height:500,resizable:false,
			buttons:[{
					text:__("System.close",true),
					click:function(){$(this).dialog("close");$(this).remove()}
			}]
		});
		$("#L10nHelpTabs").tabs();
	});

	$("#L10nRibbonBar").css({height:'79px'});

	$("#L10nRibbonBarHiderShower").on("click",function(){
		var $this=$(this);
		if(!$this.data("toggle")){
			$("#L10nRibbonBar").animate({height:'0px'},500);
			$(this).html(__("System.show_bar"));
			$("body").data("oldPaddingBottom",$("body").css("paddingBottom"));
			$("body").css({paddingBottom:"0px"});
			$this.data("toggle",true);
		}else{
			$("#L10nRibbonBar").animate({height:'79px'},500);
			$(this).html(__("System.hide_bar"));
			$("body").css({paddingBottom:$("body").data("oldPaddingBottom")});
			$this.data("toggle",false);
		}
		return false;
	});

	var resize=false;
	$("#L10nRibbonBarShowMissings").on("click",function(){
		var $this=$(this);
		if(!$this.data("toggle")){
			$("#L10nRibbonBar").data('oldHeight',$("#L10nRibbonBar").height());
			$("#L10nRibbonBar").css({height:($(window).height()-20)+"px"},100);
			$("#ribbonContent").data('oldHeight',$("#ribbonContent").height());
			$("#ribbonContent").css({height:($(window).height()-30)+"px"});
			$("#L10nMissingKeysTabs").css({height:($(window).height()-120)+"px"});
			$("#L10nMissingKeysTabs").css({minHeight:"250px"});
			resize=true;
			$this.data("toggle",true);
		}else{
			$("#L10nRibbonBar").css({height:$("#L10nRibbonBar").data('oldHeight')+"px"});
			$("#ribbonContent").css({height:$("#ribbonContent").data('oldHeight')+"px",minHeight:$("#ribbonContent").data('oldHeight')+"px"});
			$("#L10nMissingKeysTabs").css({minHeight:"auto"});
			resize=false;
			$this.data("toggle",false);
		}
		return false;
	});

	$(window).resize(function(){
		if(resize){
			$("#L10nRibbonBar").css({height:($(window).height()-20)+"px"});
			$("#ribbonContent").css({height:($(window).height()-30)+"px"});
			$("#L10nMissingKeysTabs").css({height:($(window).height()-120)+"px"});
		}
	});

	pagTotal=3;
	pag=1;
	$("#ribbonPrevStyle").css({opacity:0.3});
	$("#ribbonNextStyle").click(function(){
		$("#ribbonPrevStyle").css({opacity:1});
		if(pag!=pagTotal){
			$("#ribbonSlide").animate({left:'-=136px'},400);
			pag++;
			if(pag==pagTotal){
				$(this).css({opacity:0.3});
			}
		}
		return false;
	});
	$("#ribbonPrevStyle").click(function(){
		$("#ribbonNextStyle").css({opacity:1});
		if(pag!=1){
			$("#ribbonSlide").animate({left:'+=136px'},400);
			pag--;
			if(pag==1){
				$(this).css({opacity:0.3});
			}
		}
		return false;
	});

	$("#ribbonContent").css("width",$("#ribbonContent .ribbonCenter").css("width"));

	$.L10nTools();
});