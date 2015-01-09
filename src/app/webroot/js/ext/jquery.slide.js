/*!
* Slide - jQuery Plugin
* Version: 0.1
*
* Autor: Efrain Rochin Aramburo
*
* http://jquery.com
*/
(function($){

	// Configuracion default
	var settings={
		afterStart:$.noop,
		beforeAnimate:$.noop,
		beforeStart:$.noop,
		previous:".prev",
		next:".next",
		pages:".page",
		items:".item",
		container:".container",
		scroll:".slide",
		type:"slide",
		axis:"horizontal",
		circular:true,
		itemsPerClick:1,
		itemsPerPage:1,
		autoChange:false,
		autoHeight:true,
		interval:3000,
		effect:{
			easing:"linear",
			duration:500
		}
	};

	/**
	* Objeto Gallery
	*
	* @constructor
	* @class Gallery
	* @param e {HTMLElement} El elemento que se usara para la galeria.
	* @param o {Object} Objeto de la propiedades de configuracion .
	* @cat Plugins/Gallery
	*/

	$.Slide=function(e,opc){

		// Se combinan las configuraciones pasadas en la declaracion con las configuraciones por default
		this.settings= $.extend({}, settings, opc || {});

		// Declaracion de los atributos de la clase
		this.pag_act=1;
		this.pag_ant=1;
		this.pag_total=0;
		this.timer=null;

		//Elementos html(jQuery)
		this.items=null;
		this.container=null;
		this.scroll=null;
		this.prevButton=null;
		this.nextButton=null;
		this.pagesButtons=null;

		//Se hace un cast del objeto HTML a jQuery
		var $this=$(e);
		//Se obtienen los objetos que conforman la galeria
		this.container=$(this.settings.container,$this);
		this.items=$(this.settings.items,this.container);
		this.scroll=$(this.settings.scroll,this.container);
		this.prevButton=$(this.settings.previous,$this);
		this.nextButton=$(this.settings.next,$this);
		this.pagesButtons=$(this.settings.pages,$this);
		
		if(this.settings.autoHeight){
			//Se ajusta el alto del contenedor al tama�o del primero elemento de la galeria
			this.container.css({height:this.items.eq(0).outerHeight()+"px"});
		}

		if(this.settings.type=="fade"){
			this.items.slice(1).css({display:"none"});
		}

		var self=this;
		//se pone el evento click a los controles de la galeria
		this.nextButton.click($.proxy(self,"changeNext"));
		this.prevButton.click($.proxy(self,"changePrev"));
		this.pagesButtons.each($.proxy(self,"initPages"));

		//Se obtiene el numero total de paginas
		this.pag_total=this.items.length/this.settings.itemsPerPage;
		this.pag_total=Math.ceil(this.pag_total);

		if(this.settings.autoChange){
			this.timer=setTimeout(function(){
				self.changeNext();
			},this.settings.interval);
		}

		//Callback
		this.settings.afterStart();
	}

	// abreviacion
	var $S = $.Slide;

    $S.fn = $S.prototype = {
        Slide: '0.1'
    };

    $S.fn.extend = $.extend;

    $S.fn.extend({
		/**
		* Pone el elevento click a los botones de las paginas
		*
		* @method initPages
		* @param index{number} inidica el indice que le corresponde al elemento en la coleccion
		* @param element{HTML} elemento html
		* @return undefined
		*/
		initPages:function(index,element){
			var self=this;
			$(element).click(function(){
				self.changeTo(index+1);
				return false;
			});
		},
		/**
		* Hace la animacion y el cambio en la galeria
		*
		* @method change
		* @return undefined
		*/
		change:function(){
			var self=this;
			var e;
			if(this.settings.autoChange){
				window.clearTimeout(this.timer);
			}
			if(this.settings.type=="slide"){
				if(this.settings.axis=="horizontal"){
					var width=0;
					if(this.settings.itemsPerClick>1){
						width=this.items.outerWidth()*this.settings.itemsPerClick;
					}else{
						width=this.items.outerWidth();
					}
					var x=width*(this.pag_act-1)*-1;
					e=$.extend({},this.settings.effect);
					this.scroll.animate({left:x+"px"},e);
					e=$.extend({},this.settings.effect);
				}
			}else if(this.settings.type=="fade"){
				var act=this.items.eq(this.pag_act-1),eAct=$.extend({},this.settings.effect);
				e=$.extend({complete:function(){
						act.fadeIn(eAct);
				}},this.settings.effect);
				this.items.eq(this.pag_ant-1).fadeOut(e);
				
			}
			this.pagesButtons.eq(this.pag_ant-1).removeClass("selected");
			this.pagesButtons.eq(this.pag_act-1).addClass("selected");
			e=$.extend({},this.settings.effect);
			if(this.settings.autoHeight){
				this.container.animate({height:this.items.eq(this.pag_act-1).height()+"px"},e);
			}
			if(this.settings.autoChange){
				this.timer=window.setTimeout(function(){self.changeNext();},this.settings.interval);
			}
		},
		/**
		* Cambia la galeria a la pagina indicada
		*
		* @method changeTo
		* @param page{number} pagina a la que la galeria se cambiara
		* @return undefined
		*/
		changeTo:function(page){
			if(page!=this.pag_act){
				this.pag_ant=this.pag_act;
				this.pag_act=page;
				this.change();
			}
		},
		/**
		* Cambia la galeria a la pagina siguiente
		*
		* @method changeNext
		* @return undefined
		*/
		changeNext:function(){
			if(this.pag_act<this.pag_total){
				this.pag_ant=this.pag_act;
				this.pag_act++;
			}else if(this.settings.circular){
				this.pag_ant=this.pag_act;
				this.pag_act=1;
			}
			this.change();
			return false;
		},
		/**
		* Cambia la galeria a la pagina anterior
		*
		* @method changePrev
		* @return undefined
		*/
		changePrev:function(){
			if(this.pag_act>1){
				this.pag_ant=this.pag_act;
				this.pag_act--;
			}else if(this.settings.circular){
				this.pag_ant=this.pag_act;
				this.pag_act=this.pag_total;
			}
			this.change();
			return false;
		}
	});

	/*
	* Pone la animacion y la funcionalidad de las galerias sin afectar la presentacion.
	*
	* @example $("#slide").Slide();
	* @default estructure
	*
	* <div id="Slide">
	* 	<div class="container">
	* 		<div class="slide">
	* 			<div class="item"></div>
	* 			<div class="item"></div>
	* 			<div class="item"></div>
	* 		</div>
	* 	</div>
	* 	<div class="prev"></div>
	* 	<div class="next"></div>
	* 	<div class=".page"></div>
	* 	<div class=".page"></div>
	* 	<div class=".page"></div>
	* 	<div class=".page"></div>
	* </div>
	*
	* Esta estructura y nombre de las clases se pueden cambiar dependiendo del dise�o.
	*
	* @method Gallery
	* @return jQuery
	* @param o {Hash|String} objetos que contiene la configuracion
	*/

	$.fn.Slide=function(opc){
		return this.each(function(index){
			$(this).data('Slide', new $S(this, opc));
		});
	};

})(jQuery);
