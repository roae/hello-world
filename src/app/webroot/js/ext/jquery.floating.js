(function($){

	$.fn.Floating=function(options){
		var elements = [];
		if($("body").data("FloatingElements")){
			elements = $("body").data("FloatingElements");
		}

		this.each(function(){
			elements.push(this);
		});
		$("body" ).data("FloatingElements",elements);
		$(window).unbind('scroll.Floating',Scroll);

		$.each(elements,function(index,element){
			var tag=this.tagName.toLowerCase();
			var $this=$(element);
			if($(element ).data("Floating")){
				hideFloating($(element));
			}
			var top=$this.offset().top - parseFloat($this.css('margin-top').replace(/auto/, 0));

			var left=$this.offset().left;
			var topOffset=0;
			$.each(elements,function(i,element){

				var _top=$(element).offset().top - parseFloat($(element).css('margin-top').replace(/auto/, 0));
				var x1=$(element).offset().left;
				var x2=$(element).offset().left + $(element).outerWidth();
				// si no es el mismo elemento la altura del elemento acutual es menor a la altura
				if(i!=index && _top < top && (left>=x1 && left <=x2) ){
					top-=$(element).outerHeight(true); // valor en donde debe empezara a flotar el elemento
					topOffset+=$(element).outerHeight(true); // posicion en la que flotara el elemento
				}
			});
			/**
			 * se obtiene el atributo style por que se cambiara el atributo width con css para despues remplazarlo por este cuando
			 * ya no sea flotante
			 */
			//$this.data("inlineCSS", );

			$this.data("Floating",{
				'inlineCss':(!$this.attr("style") ? "" : $this.attr("style")),
				'top':top,
				'topOffset':topOffset
			});
			data={element:$this};

			$(window).bind('scroll.Floating',data,Scroll);
		});
		$(window ).trigger("scroll.Floating")
		return;
	};

	function Scroll(event){
		if($(this).scrollTop() >= event.data.element.data("Floating" ).top && !event.data.element.hasClass("fixed")){
			setFloating(event.data.element);
		}else if($(this).scrollTop() <= event.data.element.data("Floating" ).top && event.data.element.hasClass("fixed")){
			hideFloating(event.data.element);
		}
	}

	function setFloating($this){
		topOffset = $this.data("Floating" ).topOffset;
		// se pone el width actual diractemente en el atributo style para no perderlo
		$this.css({width:$this.width()});
		if($this[0].tagName.toLowerCase()=="thead"){ // en caso de ser un thead
			// se ponen todos los width de los th dentro del thead igual a los td de la tabla
			$this.each(function(index,node){
				$(node.parentNode).find('th,tbody tr:first td').each(function(key,cell){
					$(cell).css({width:$(cell).css('width')});
				});
			});
		}
		//$clone.css({display:'block'});
		// se crea un div que ocupa el lugar que ocupa el elemento flotante
		var randomNum = Math.ceil(Math.random() * 9999); /* Pick random number between 1 and 9999 */
		$this.data("PanelSpaceID", "FloatingSpace" + randomNum);
		var tag = $this[0].tagName.toLowerCase();
		var tags = ['tr']; // arreglo con etiquetas en las que se deben de crear los hijos para un mejor funcionamiento
		var $childrens = "&nbsp;";
		if( _.contains(tags,tag)){
			$childrens = $this.children().clone();
		}
		$this.before(
			$("<"+tag+"/>",{
					id:$this.data("PanelSpaceID")
				} ).css({
					width:$this.outerWidth(true)+"px",
					height:$this.outerHeight(true)+"px",
					float: $this.css("float"),
					display: $this.css("display")
				} ).append(
						$childrens
				)
		);

		$this.addClass("fixed").css({top:topOffset+"px",position:'fixed'});
	}

	function hideFloating($this){
		// se restaura el atributo style

		$this.attr("style",$this.data("Floating" ).inlineCss);
		if($this[0].tagName.toLowerCase()=="thead"){ // en caso de ser un thead
			$this.each(function(index,node){
				// se pone el width de los thead en auto para dejarlo como defautl
				$(node.parentNode).find('th,tbody tr:first td').each(function(key,cell){
					$(cell).css({width:''});
				});
			});
		}
		//$clone.css({display:'none'});
		$("#"+$this.data("PanelSpaceID")).remove();
		$this.removeClass("fixed");

	}

	function resizeElements(){

	}

})(jQuery);

$(function(){
	$(".floating").Floating();
})