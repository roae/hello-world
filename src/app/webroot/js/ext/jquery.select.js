(function($){

	$.Select=function(e){
		this.select=$(e);
		this.select.bind('change',$.proxy(this,'change')).bind("focus",$.proxy(this,"focus")).bind("blur",$.proxy(this,"blur")).bind("keypress",$.proxy(this,"change"));
		this.label=$('<span/>',{class:'SelectOpt'});
		this.wrapper = $("<div/>",{class:'SelectWrapper'});
		this.select.wrap(this.wrapper);
		this.select.before(this.label);

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
			this.wrapper.addClass("select_focus");
		},
		blur:function(){
			this.wrapper.removeClass("select_focus");
		}
	});

	$.fn.Select=function(){
		return this.each(function(index){
			$(this).data('Select', new $.Select(this));
		});
	};

})(jQuery);