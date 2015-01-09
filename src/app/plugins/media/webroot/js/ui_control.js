var fTreeHeight,$height,infoOpen=false,bHeight,panelHeight;
$(function(){
	$height=$("#header").outerHeight()+$("#tabs").outerHeight()+$("#footer").outerHeight()+5;
	bHeight=$("#mediaInterface .toolBar").outerHeight()+$("#Navigation").outerHeight();

	initMediaInterface();
	$.xUpdater.settings.indicator=null;
	$.xUpdater.setCallback('afterRequest',initMediaInterface);
	$.xUpdater.setCallback('beforeRequest',function(){
		$('#mediaInterface,#mediaInterface *').css({cursor:'wait'});
		$("#Navigation .MediaUrl").addClass('MediaUrlLoading');
	});
	$.xUpdater.setCallback('afterRequest', function(){
		$('#mediaInterface,#mediaInterface *').css({cursor:'default'});
	});
	$("#MediaInfoFile").click(function(){
		if(!infoOpen){
			_top=$(window).height()/2-$("#Files .InfoPanel").outerHeight()/2;
			_left=$(window).width()/2-$("#Files .InfoPanel").outerWidth()/2;
			console.log(_left);
			$("#Files .InfoPanel").css({display:'block',top:_top+"px",left:_left+"px"}).stop().animate({opacity:1},300);
			infoOpen=true;
		}else{
			$("#Files .InfoPanel .close").click();
		}
	});

	$("#MediaAddFile").click(function(){
		$("#Files").addClass("showBar");
		$("#FormBar").addClass("showBar");
	});

	$("#CreateFolder form").on('submit',function(){
		$("#CreateFolder .creatingFolder").css({display:'block'}).animate({opacity:0.8},100);
		var $this=$(this);
		$.ajax({
			url:$this.attr("action"),
			data:$this.serialize(),
			type:'POST',
			success:function(html,status,http){
				eval('var path = "'+http.getResponseHeader('MediaPath')+'";');
				eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');
				var content=$.xUpdater.getContentDivs(html);
				//console.log(path+" - "+$.xUpdater.getHash().replace(/.*?admin\/media/,''));
				if(path==$.xUpdater.getHash().replace(/.*?admin\/media/,'')){
					$.xUpdater.updateDivs(content);
				}else{
					$("#CreateFolder").html(content.CreateFolder);
				}
				$.xUpdater.updateDivs(html);
				if(Xnotifier){
					var img = (Xnotifier.type!="success") ? '/img/icons/48/cancel.png' : '/img/icons/48/accept.png' ;
					$("<div />").append($("<img/>",{src:img,'class':'dialog-icon'})).append($("<p/>",{html:Xnotifier.message})).dialog({
						modal:true,width:300,buttons:[
							{text:__('ok'),click:function(){$(this).dialog("destroy")}}
						]
					});

				}
			},
			beforeSend:function(http){
				http.setRequestHeader("X-update",'Navigation Folders Files CreateFolder');
			}
		});
		return false;
	});

	$("#MediaPathForm").on('submit',function(){
		$.xUpdater.setHash("Navigation|Folders|Files=/admin/media"+$("#FolderPath").val());
		return false;
	});

	$("#Files .InfoPanel .close").on('click',function(){
		$("#Files .InfoPanel").stop().animate({opacity:0},300,function(){$(this).css({display:'none'})});
		infoOpen=false;
	});

	$("#MediaFolderTree a").on({
		click:function(e){
			if($(e.target).hasClass("arrow")){
				$("ul:first",$(this).parent()).toggleClass("displayed");
				$(e.target).toggleClass('arrow_displayed');
			}else{
				$("#MediaFolderTree .selected").removeClass('selected');
				$("#Files .selected").removeClass('selected');
				$(this).addClass("selected");
				$(".InfoPanel .inf").css({display:'none'});
				$("#Info_file"+$(this).attr('rel')).css({display:'block'});

				$("#UploadUrl").val($(this).attr("href").replace("/admin/media",""));
				//console.log($(this).attr("href"));
			}
			return false;
		},
		dblclick:function(e){
			if(!$(e.target).hasClass("arrow")){
				$("#MediaFolderTree a.current").removeClass('current');
				$(this).addClass('current');
				$.xUpdater.setHash("Navigation|Folders|Files="+$(this).attr("href"));
			}
			return false;
		}
	});

	$("#Files .file").on({
		click:function(e){
			$("#Files .selected").removeClass('selected');
			$("#MediaFolderTree .selected").removeClass('selected');
			$(this).addClass("selected");
			$(".InfoPanel .inf").css({display:'none'});
			$("#Info_file"+$(this).attr('rel')).css({display:'block'});
			$("#UploadUrl").val($(this).attr("href").replace("/admin/media",""));
			return false;
		},
		dblclick:function(e){
			$.xUpdater.setHash("Navigation|Folders|Files="+$(this).attr("href"));
			return false;
		}
	});

	//$('.MediaUploader').uploadify({removeCompleted:true,script:'/admin/media/upload',folder:'/media/uploads/'})

});

function initMediaInterface(){
	$("#mediaInterface").css({height:$(window).height()-$height+"px"});
	$("#Files").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	$("#Folders").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	$("#MediaFolderTree").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	$("#Files .fileViewer").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	$("#FormBar").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	$("#Files .InfoPanel").draggable();
	$(window).resize(function(){
		$("#mediaInterface").css({height:$(window).height()-$height+"px"});
		$("#Files").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
		$("#Folders").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
		$("#Files .fileViewer").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
		$("#FormBar").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
		$("#MediaFolderTree").css({height:$("#mediaInterface").outerHeight()-bHeight+"px"});
	});
}

window['MediaUploadSkin']={
	'Multiple':{
		'layout':[
			'div',{'class':'selectFiles'},[
				'span',{'class':'label'},__('press_and_select_files'),
				'div',{'class':'buttonBar ChooseFiles'},[
					'span',{'class':'btnIcon'},[],
					'span',{'class':'btnText'},__('select_files'),
				],
				'span',{'class':'helpText'},__('helpUpload'),
			],
			'div',{'class':'uploading'},[
				'span',{'class':'label'},__("uploadingFiles"),
				'ul',{'class':'uploadifyQueue sortable clearfast'},[],// Aqui dentro va el template de cada archivo
			],
			'div',{'class':'buttons'},[
				'button',{'class':'buttonBar CancelAllUploads cancelDisabled','onclick':'return false;'},[
					'span',{'class':'btnIcon'},[],
					'span',{'class':'btnText'},__('cancel_uploads'),
				],
				'button',{'class':'buttonBar hiddePanel','onclick':'return false;'},[
					'span',{'class':'btnIcon'},[],
					'span',{'class':'btnText'},__('hidde_pannel'),
				],
			]
		],
		'item':[
			'li',{'class':'uploadifyQueueItem'},[
				'span',{'class':'fileName'},[],
				'span',{'class':'uploadifyProgress'},[
					'span',{'class':'uploadifyProgressBar'},[]
				],
				'span',{'class':'percentage'},[],
				'button',{'class':'btn cancelItem','onclick':'return false;'},[
					'span',{'class':'btnIcon'},[],
				],
			]
		],
		'animation':{
			'appear':{
				'properties':{opacity:1.0},
				'options':{duration:500}
			},
			'hidde':{
				'properties':{opacity:0.0,height:0},
				'options':{duration:500,complete:function(){$(this).remove()}}
			}
		}
	}
}