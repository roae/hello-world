/*
* xUpdater - jQuery prototype
* Version: 2.0
* Peticiones ajax con historial
*
* http://jquery.com
*
*/

var _xUpdaterDialogTmpl=[
	'div',{'class':'modal hide fade','abindex':'-1','role':'dialog','aria-lebelledby':'myModalLabel','aria-hidden':true},[
		'div',{'class':'modal-header'},[
			'button',{'type':'button','class':'close','data-dismiss':'modal','aria-hidden':true},"&times;",
			'h3',{},''
		],
		'div',{'class':'modal-body'},'',
		'div',{'class':'modal-footer'}
	]
];

$.xUpdater=function(opc){
	var self=$.xUpdater;
	self.settings= $.extend({}, self.settings, opc || {});
	self.selector=self.settings.selector;
	$.History.bind(function(hash){
		if(hash!=self.lastHash){
			self.loadPage(hash);
			self.lastHash=hash;
		}
	});

	$(self.selector).click(self.click);
	$.xUpdater.afterRequest.push(self.settings.callbacks.afterRequest);
	self.window=$.create(_xUpdaterDialogTmpl);
	$(".modal-header",self.window).remove();
	self.window.attr("id","xUpdaterWindow").appendTo("body");
};

$.xUpdater.div="";
$.xUpdater.selector="";
$.xUpdater.id="";
$.xUpdater.window=null;
$.xUpdater.afterRequest=[];
$.xUpdater.beforeRequest=[];
$.xUpdater.lastHash="";

$.xUpdater.loadPage=function(hash){
	var self=$.xUpdater;
	var $this=$(this);
	var pieces=hash.match(/(.+)\=(.+)/);
	var divs,selector;
	if(pieces){
		divs=pieces[1].split('|');
		selector="#"+divs.join(",#");
	}
	if($(selector).length){
		$(self.settings.indicator).fadeIn('slow');
		self.execBeforeRequest(selector);
		$.ajax({
			url:pieces[2],
			success: function(html){
				$.xUpdater.updateDivs(html);
				$(self.settings.indicator).fadeOut('slow');
			},
			beforeSend:function(http){
				http.setRequestHeader("X-update",divs.join(' '));
			}
		});
		self.div=selector;
	}else if(!hash && self.div){ // si no llega nada en el hash
		$(self.settings.indicator).fadeIn('slow');
		self.execBeforeRequest(self.div);
		$.ajax({
			url:window.location.pathname,
			success: function(html){
				$.xUpdater.updateDivs(html);
				$(self.settings.indicator).fadeOut('slow');
			},
			beforeSend:function(http){
				http.setRequestHeader("X-update",self.div.replace(/#/g,'').replace(/,/g,' '));
			}
		});
	}
};


$.xUpdater.click=function(){
	var self=$.xUpdater;
	id=$(this).attr("rev").match(/#(.+)/);
	if(id){
		var $this=$(this);
		if($this.hasClass("noHistory")){
			$(self.settings.indicator).css({display:'block'});
			//console.log("sin hitoria");
			self.execAction($this);
		}else if($this.hasClass("action") && $this.attr("rel") !== undefined){

				var okButton= function(){
					$(".modal-body",self.window).empty().createAppend([
						'div',{'class':'loading'},[
							'i',{'class':'icon-sun icon-spin'},'',
							'p',{'class':'msgLoading'},__("loading",false)
						]
					]);
					$(".modal-footer",self.window).empty().hide();
					self.execAction($this);
					return false;
				};
				$(".modal-body",self.window).empty().createAppend([
					'i',{'class':'icon-question icon-3x icon'},[],
					'p',{'class':'msg'},$(this).attr("rel")
				]);
				$(".modal-footer",self.window).empty().show()
					.append($("<button/>",{'class':'btn','data-dismiss':'modal','aria-hidden':true,'html':__("no",false)}))
					.append($("<button/>",{'class':'btn btn_primary','data-toggle':'modal','data-target':"#window",'html':__("yes",false)}).on('click',okButton));

				self.window.modal("show");

		}else{
			//console.log("Cambio de hash");
			$.proxy(self.go,$this)();
		}
		return false;
	}
	return true;
};

$.xUpdater.execAction=function($this){
	var self=this;
	var url=$this.attr('href');
	//var divs=id[1].replace("#","").split('|');
	var selector=$this.attr("rev").replace(/|/,",");
	self.execBeforeRequest(selector);
	$.ajax({
		url:url,
		success:function(html,status,http){
			$(self.settings.indicator).css({display:'none'});
			eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');
			$.xUpdater.updateDivs(html,$this);
			if(Xnotifier){
				var icon = (Xnotifier.type!="success") ? 'icon-remove-sign' : 'icon-ok-sign' ;

				$(".modal-body",self.window).empty().createAppend([
					'i',{'class':icon+' icon-3x'},'',
					'p',{},Xnotifier.message
				]);
				$(".modal-footer",self.window).empty().show()
					.append($("<button/>",{'class':'btn btn_primary','data-dismiss':'modal','aria-hidden':true,'html':__("acept")}));

				self.window.modal("show");

			}else{
				self.window.modal("hide");
			}
		},
		beforeSend:function(http){
			var pieces;
			var xUpdate=Array();
			$.each($this.attr("rev").split("|"),function(k,v){
				if(v.match(/.*:.*/)){
					pieces=v.split(":");
					xUpdate.push(pieces[0].replace("#",""));
				}else{
					xUpdate.push(v.replace("#",""));
				}
			});
			http.setRequestHeader("X-update",xUpdate.join(" "));
		}
	});
};
$.xUpdater.count=function(ob){
	var r=0;
	$(ob).each(function(k,v){
		r++;
	});
	return r;
}


$.xUpdater.updateDivs=function(html,$this){
	var content=html
	if(typeof(html)=="string" && html.match(/__ajaxUpdater__/)){
		content=$.xUpdater.getContentDivs(html);
	}
	var self=$.xUpdater;
	var pieces;
	var divsAction= Array();
	var div={};
	var divs=($this) ? $this.attr("rev").split("|") : $.xUpdater.div.split("|");
	//console.dir(divs);
	$.each(divs,function(k,v){
		if(typeof(v)=="string" && v.match(/.*:.*/)){
			pieces=v.split(":");
			divsAction[pieces[0]]=pieces[1];
		}else{
			pieces=[v,"update"];
			divsAction[v]="update";
		}
	});
	if(typeof(content)=="string"){
		if(pieces[1]=="update"){
			$(pieces[0]).html(content);
		}else{
			$(pieces[0]).append(content);
		}
		self.refresh($(pieces[0]));
		self.__callbacks(pieces[0]);
	}else{
		$.each(content, function(k,v){
			if(divsAction["#"+k]=="append"){
				$("#"+k).append(unescape(decodeURIComponent(v)));

			}else{
				$("#"+k).html(unescape(decodeURIComponent(v)));
			}
			self.refresh($("#"+k));
			self.__callbacks("#"+k);
		});
	}
}

$.xUpdater.__callbacks=function(id){
	this.execAfterRequest($(id));
	if(this.settings.callbacks[id.replace("#","")]){
		this.settings.callbacks[id.replace("#","")]($(id));
	}
}

$.xUpdater.getContentDivs=function(html){
	if(html.match(/__ajaxUpdater__/)){
		eval(html.replace(/<[^>].*>|\/\/<.*?A\[|\/\/\]\]>|for\s\(n[^\}]+}/gi,'')); // se quita el codigo prototype que viene en la respuesta
		return __ajaxUpdater__;
	}
	return false;
};

$.xUpdater.go=function(){
	var self=$.xUpdater;
	if($.History.getHash!=""){
		self.div=this.attr("rev");
	}
	self.setHash("#"+id[1]+"="+this.attr("href"));
};

$.xUpdater.setHash=function(hash,callback){
	var pieces=hash.match(/(.+)\=(.+)/);
	if(pieces && callback){
		$.xUpdater.settings.callbacks[pieces[1]]=callback;
	}

	$.History.setHash(hash);
}
$.xUpdater.getHash=function(){
	return $.History.getHash();
}

$.xUpdater.refresh=function(element){
	$($.xUpdater.selector,element).click($.xUpdater.click);
};

$.xUpdater.execAfterRequest=function(selector,ids){
	$.each($.xUpdater.afterRequest,function(key,afterRequestCallback){
		if(typeof(afterRequestCallback) == 'function'){
			afterRequestCallback(selector,ids);
		}
	});
}

$.xUpdater.execBeforeRequest=function(selector,ids){
	$.each($.xUpdater.beforeRequest,function(key,beforeRequestCallback){
		if(typeof(beforeRequestCallback) == 'function'){
			beforeRequestCallback(selector,ids);
		}
	});
}

$.xUpdater.setCallback=function(id,callback){

	if(id && id=="afterRequest"){
		$.xUpdater.afterRequest.push(callback);
	}else if(id && id=="beforeRequest"){
		$.xUpdater.beforeRequest.push(callback);
	}else{
		$.xUpdater.settings.callbacks[id]=callback;
	}
}

$.xUpdater.settings={
	selector:'a[rev^="#"]',
	indicator:'#Loading',
	callbacks:[]
};

$(document).ready(function(){
	$.xUpdater();
});