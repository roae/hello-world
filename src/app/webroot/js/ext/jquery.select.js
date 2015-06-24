(function($){

	$.Select=function(e){
		this.select=$(e);
		this.label=$('<span/>',{class:'SelectOpt'});
		this.wrapper = $("<div/>",{class:'SelectWrapper'});

		this.select.wrap(this.wrapper);
		this.select.before(this.label);

		this.wrapper = this.select.closest(".SelectWrapper");

		this.select.bind('change',$.proxy(this,'change')).bind("focus",$.proxy(this,"focus")).bind("blur",$.proxy(this,"blur")).bind("keypress",$.proxy(this,"change"));
		this.select.bind("keypress", $.proxy(this,'keypress'));
		this.select.bind("keydown", $.proxy(this,'keydown'));

		this.label.text($('option[value="'+this.select.val()+'"]',this.select).html());
	};


    $.Select.fn = $.Select.prototype = {
        Version: '1.0'
    };

    $.Select.fn.extend = $.extend;

	$.Select.fn.extend({
		change:function(){
			this.label.text($('option[value="'+this.select.val()+'"]',this.select).html());
			//console.log(this.select.val());
		},
		focus:function(){
			this.wrapper.addClass("select_focus" );
		},
		blur:function(){
			this.wrapper.removeClass("select_focus");
		},
		/*keypress:function(){
			console.dir(arguments);
		},*/
		keydown:function(event){
			if(event.keyCode == 40 ){ // Flecha abajo
				if(this.select.val()){
					this.label.text($('option[value="'+this.select.val()+'"]',this.select ).next().html());
				}else{
					this.label.text($('option:first',this.select ).next().html());
				}
			}else if(event.keyCode == 38){ // Flecha arriba
				this.label.text($('option[value="'+this.select.val()+'"]',this.select ).prev().html());
			}
		}
	});

	$.fn.Select=function(){
		return this.each(function(index){
			$(this).data('Select', new $.Select(this));
		});
	};

})(jQuery);