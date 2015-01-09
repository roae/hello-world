/**
 * jQuery.L10n Plugin jQuery
 * @version 0.1
 * Lleva el control de los elementos editables
 *
 */
/* @var this $.L10n */
(function($){

	/**
	 * objeto L10n
	 *
	 * @contructor
	 * @class L10n
	 * @param e {HTMLElement} la clave de edicion
	 * @cat Plugins/L10n
	 */

	$.L10n=function(e,o){
		if(!o && o!="update"){
			var $this=this;

		    this.na=navigator;
			this.ua = this.na.userAgent;
			this.isOpera = window.opera && this.opera.buildNumber;
			this.isWebkit = /WebKit/.test(ua);
			this.isIE = !this.isWebkit && !this.isOpera && (/MSIE/gi).test(this.ua) && (/Explorer/gi).test(this.na.appName);
			this.isIE6 = this.isIE && /MSIE [56]/.test(this.ua);
			this.isGecko = !this.isWebKit && /Gecko/.test(this.ua);
			this.isMac = this.ua.indexOf('Mac') != -1;

			this.blockRe = /^(DIV|FORM|T(ABLE|BODY|HEAD|FOOT|H|R|D)|LI|OL|UL|CAPTION|BLOCKQUOTE|CENTER|DL|DT|DD|DIR|FIELDSET|NOSCRIPT|MENU|ISINDEX|SAMP|ARTICLE|NAV|SECTION|FOOTER|HEADER|HGROUP)$/;
			/**
			 * Atributos del plugin
			 */
			// Elemento de edicion
			this.element=$(e);
			if(this.isGecko){ // si es firefox lo cambiamos por un div el elemento
				var div=$("<div/>",{'class':'L10nKey',title:this.element.attr("title"),rel:this.element.attr("rel")});
				this.element.before(div.html(this.element.html()));
				this.element.remove();
				this.element=div;
			}
			//console.log(this.element.parent().get(0).tagName.toUpperCase());
			if(this.blockRe.test(this.element.parent().get(0).tagName.toUpperCase())){
				this.element.css("display","block");
			}
			// Tipo de etiqueta que se esta usando
			this.tag=this.element.get(0).tagName.toLowerCase();
			// Declaracion de los atributos de la clase
			this.document=document;
			// div que indica que el texto es editable
			this.indicator=null;
			// Guarda el contenido que tiene el elemento para determinar si cambio o no
			this.initContent=this.element.html();
			// inidica si el elemento tien o no el foco
			this.hasFocus=false;
			// se usa para el control del timer en la actualizacion de los elementos
			this.timer=null;
			// guarda la clave de traduccion del elemento
			this.key=this.element.attr("rel");
			// vandera que indica si se puso un br al final
			this.br=false;

			this.init();

			this.execCommand('styleWithCSS', false);
			this.execCommand('insertBrOnReturn', false);
			this.execCommand('enableObjectResizing', true);
		}else if(o=="update"){
			updateElements($(e));
		}
	}

	var $L=$.L10n;

	$L.fn=$L.prototype={
		L10n: '0.1'
	};

	$L.fn.extend = $.extend;

	$L.fn.extend({
		/**
		 * inicializa los elementos. Pone los eventos necesarios
		 * @method init
		 * @return void
		 */
		init:function(){
			var $this=this;
			this.element.attr("contenteditable",'true');
			this.element.bind('keydown',$.proxy($this,'keydown'));
			this.element.bind('keyup',$.proxy($this,'keyup'));
			this.element.bind('blur',$.proxy($this,'blur'));
			this.element.bind('focus',$.proxy($this,'focus'));
			this.element.bind('click',$.proxy($this,'click'));
			//this.element.hover($.proxy($this,'mouseover'),$.proxy($this,'mouseout'));

		},
		click:function(type){
			var $this=this;
			if(this.isGecko && !this.hasFocus && type!="focus"){
				this.execCommand("selectAll",true);
			}else if(type=="focus"){
				if(!this.hasFocus){
					//this.execCommand("selectAll",true);
				}
			}
			this.hasFocus=true;
			this.oldContent=this.element.html();
			this.createIndicator();
			this.element.addClass("L10nActivate");
			$.L10nTools.setElement(this.element);
			$.L10nTools.update();
		},
		focus:function(inner){
			this.click("focus");
		},
		mouseover:function(){
			this.createIndicator();
		},
		mouseout:function(){

			this.deleteIndicator();
		},
		keydown:function(e){
			if (e){
				var Key=e.which;
			}else{
				var Key=event.keyCode;
			}
		},
		keyup:function(e){
			if(this.br){
				var html=this.element.html().replace(/<br[^>]*>$/,'');
				this.execCommand('selectAll',true);
				this.execCommand('delete',true);
				this.execCommand('insertHTML',html);
			}
			this.br=false;
			if(this.element.html().match(/<br[^>]*>$/)){
				this.br=true;
			}
			this.update();
		},
		blur:function(){
			this.deleteIndicator();
			this.hasFocus=false;
			if(this.element.html().match(/<br[^>]*>$/)){
				var html=this.element.html().replace(/<br[^>]*>$/,'');
				this.element.append(html);
				//this.onChange();
			}
			if(this.initContent!=this.element.html()){
				this.onChange();
			}
			this.element.removeClass("L10nActivate");
			// si deja el elemento que se este editando y esta vacio se pone la clave de traduccion
			if(this.element.html()==""){
				$(this.tag+'[title="'+this.element.attr("title")+'"]').text("["+this.key+"]");
			}
		},
		deleteIndicator:function(){
			/*if(this.indicator){
				this.indicator.remove();
				this.indicator=null;
			}*/
		},
		onChange:function(){
			updateElements(this.element);
		},
		update:function(){
			if(this.oldContent != this.element.html()){
				this.onChange();
				this.oldContent=this.element.html();
			}
			this.setIndicator();
			$.L10nTools.update();
		},
		setIndicator:function(){
			/*if(this.hasFocus && this.indicator){
				this.indicator.css({
					width:this.element.width()+'px',
					height:this.element.height()+'px',
					left:this.element.offset().left-1+'px',
					top:this.element.offset().top-1+'px'
				});
			}*/
		},
		createIndicator:function(){
			/*if(!this.indicator){
				this.indicator=$("<div/>",{'id':'L10nIndicator'});
				$('body').append(this.indicator);
				this.setIndicator();
			}*/
		},
		execCommand:function(command,param){
			try{
				this.document.execCommand(command, false, param);
			}catch(e){}
		}
	});

	function updateElements(element){

		var tag=element[0].tagName.toLowerCase();
		var key,rel=element.attr("rel");// se obtiene la clave de edicion en el atributo rel
		// se actualizan todas las clavez de traduccion iguales
		if(this.isGecko){
			$(tag+'[title="'+element.attr("title")+'"]:not([class="'+element.attr("class")+'"])').each(function(i,e){
				e.innerHTML="";
				$(e).append(element.html());
			});
		}else{
			$(tag+'[title="'+element.attr("title")+'"]:not([class="'+element.attr("class")+'"])').html(element.html());
		}
		// Se guarda el contenido de la clave
		if($("#L10nKey"+rel).length>0){
			$("#L10nKey"+rel).val(element.html());
		}else{
			key=rel;
			if(element.attr("title").match(/[1-9]+\s\-\s\[[a-zA-Z0-9_\-]+\]/)){ // si es una clave ya registrada
				key=element.attr("title").replace(/([1-9]+)\s\-\s\[([a-zA-Z0-9_\-]+)\]/,'$1-$2');
			}
			$("#L10nForm").append($('<input/>',{type:'hidden',val:element.html().replace(/(<br[^>]*>)*$/g,''),name:'data[L10n]['+key+']',id:"L10nKey"+rel}));
		}
	}

	$.fn.L10n=function(o){
		return this.each(function(index){
			$(this).data("L10n",new $L(this,o));
		});
	}
})(jQuery);