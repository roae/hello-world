var Citicinemas = angular.module("Citicinemas", []);

$(function(){

	$(document ).on("click",".check",function(){
		if($(".check").length == $(".check:checked").length){
			$(".checkAll").prop("checked",true);
		}else{
			$(".checkAll").prop("checked",false);
		}
	});
	$(document).on('click',".checkAll",function(){
		if($(this).is(":checked")){
			$(".check,.checkAll").prop("checked",true);
		}else{
			$(".check,.checkAll").prop("checked",false);
		}
	});

	$.xUpdater.setCallback("data",function(){
		$("#data .floating").Floating();
	});

	$.xUpdater.setCallback("ajaxForm",function(){
		$("#ajaxForm .slugger").slug();
		$("#ajaxForm input[type=text]:first" ).focus();

		$("#ajaxForm .tiny-mini" ).tinymce(tiny_mini_options)
	});

	//$('.ui-button').button();

	$(".tabs").tabs();
	$("header").Floating();

	//$('.ui-radio').buttonset();

	$(".slugger").slug();

	$(document).on('submit',".ajaxForm",function(){
		var $win=$.create(_bootstrapDialogTmpl);
		$win.addClass("modalMessage");
		$(".modal-header",$win ).remove();
		//$("#Loading").css({display:'block'});
		$form = $(this);
		$form.append($("<div/>",{class:'loading'}));


		$.ajax({
			url:$form.attr('action'),
			type:'post',
			data:$form.serialize(),
			success:function(html,status,http){
				eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');

				if(Xnotifier){
					var icon = (Xnotifier.type!="success") ? 'icon-remove-sign' : 'icon-ok-sign' ;

					$(".modal-body",$win).empty().createAppend([
						'i',{'class':icon+' icon-3x'},'',
						'p',{},Xnotifier.message
					]);
					$(".modal-footer",$win).empty().show()
						.append($("<button/>",{'class':'btn btn_primary','data-dismiss':'modal','aria-hidden':true,'html':__("acept",false)}));

					$win.modal("show");
				}
				var content=$.xUpdater.getContentDivs(html);

				if(content){
					$.xUpdater.updateDivs(html);
				}else{
					$("#"+$form.data("div")).html(html);
				}

				$.xUpdater({selector:'#'+$form.data("update")+' a[rev^="#"]'});

				$("textarea.tiny-mini",$form ).tinymce(tiny_mini_options)

				//$(".loading",$form).remove();
			},
			beforeSend:function(http){
				http.setRequestHeader("X-update",$form.data("update")+" "+$form.data("div"));
			}
		});
		return false;
	});

	$(document).on("change",'.foreignId',function(){
		var hash=$.xUpdater.getHash();
		if(hash.match(/foreign_id:\d+/)){
			hash=hash.replace(/foreign_id:\d+/,'foreign_id:'+$(this).val());
		}else if(hash!=""){
			hash+='foreign_id:'+$(this).val()+'/';
		}else{
			hash="data=/admin/comments/index/class:Hotel/foreign_id:"+$(this).val()+"/";
		}
		$.xUpdater.setHash(hash);
	});

	if($("#Notifier").length){
		setTimeout(function(){
			$("#Notifier").slideUp("slow");
		},5000);
	}

	$(document).on(
		{
			'click':function(event){
				if(event.target.tagName.toUpperCase()!="A" || event.target.tag.toUpperCase()!="BUTTON"){
					window.location =  $("a.fwd", this).attr('href');
				}
			},
			'mouseenter':function(){
				$(this).addClass('hover');
			},
			'mouseleave':function(){
				$(this).removeClass('hover');
			}
		},
		".link:has(a.fwd)"
	);

	$(document ).on("click","[data-confirm]",function(e){
		var $this = $(this);
		var $win=$.create(_bootstrapDialogTmpl);
		$win.addClass("modalMessage")
		$(".modal-header",$win).remove();
		$(".modal-body",$win).createAppend([
			'i',{'class':'icon-question icon-3x'},'',
			'p',{},$this.data("confirm")
		]);
		$(".modal-footer",$win)
			.append($("<button/>",{'class':'btn','data-dismiss':'modal','aria-hidden':true,'html':__("no")}))
			.append($("<button/>",{'class':'btn btn_primary','data-toggle':'modal','data-target':"#window",'html':__("yes")}).on('click',function(){
				window.location = $this.attr("href");
			}));
		$("body").append($win);
		$win.modal("show");
		return false;

	});

});


function paginAction(e,msg){
	var $win=$.create(_bootstrapDialogTmpl);
	$win.addClass("modalMessage")
	$(".modal-header",$win).remove();
	var okAction=function(){
		$("#XpaginUrl").val($(e).val());
			$(".modal-body",$win).empty().createAppend([
				'div',{'class':'loading'},[
					'i',{'class':'icon-sun icon-spin'},'',
					'p',{'class':'msgLoading'},__("loading")
				]
			]);
			$(".modal-footer",$win).hide();
			$.ajax({
				url:$("#XpaginForm").attr("action"),
				type:'post',
				data:$("#XpaginForm").serialize(),
				success:function(html,status,http){
					eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');
					$("#data").html(html);

					if(Xnotifier){
						var icon = (Xnotifier.type!="success") ? 'icon-remove-sign' : 'icon-ok-sign' ;

						$(".modal-body",$win).empty().createAppend([
							'i',{'class':icon+' icon-3x'},'',
							'p',{},Xnotifier.message
						]);
						$(".modal-footer",$win).empty().show()
							.append($("<button/>",{'class':'btn btn_primary','data-dismiss':'modal','aria-hidden':true,'html':__("acept",false)}));

						$win.modal("show" );
					}
				},
				error:function(){

				},
				beforeSend:function(http){
					http.setRequestHeader("X-update",'data');
				}
			});
	};
	$(".modal-body",$win).createAppend([
		'i',{'class':'icon-question icon-3x'},'',
		'p',{},msg
	]);
	$(".modal-footer",$win)
		.append($("<button/>",{'class':'btn','data-dismiss':'modal','aria-hidden':true,'html':__("no")}))
		.append($("<button/>",{'class':'btn btn_primary','data-toggle':'modal','data-target':"#window",'html':__("yes")}).on('click',okAction));
	$("body").append($win);
	$win.modal("show");
	return false;
}

var _bootstrapDialogTmpl=[
	'div',{'class':'modal hide fade','abindex':'-1','role':'dialog','aria-lebelledby':'myModalLabel','aria-hidden':true},[
		'div',{'class':'modal-header'},[
			'button',{'type':'button','class':'close','data-dismiss':'modal','aria-hidden':true},"&times;",
			'h3',{},''
		],
		'div',{'class':'modal-body'},'',
		'div',{'class':'modal-footer'}
	]
];