(function($){

	$.Select=function(e){
		this.e=$(e);
		this.select=$('select',this.e).bind('change',$.proxy(this,'change')).bind("focus",$.proxy(this,"focus")).bind("blur",$.proxy(this,"blur")).bind("keypress",$.proxy(this,"change"));
		this.label=$('label',this.e).wrapInner($("<span />"));
		this.e.append($('<span />',{'class':'arrow'}));
		$("span",this.label).text($('option[value="'+this.select.val()+'"]',this.select).html());
	};


    $.Select.fn = $.Select.prototype = {
        Label: '0.1'
    };

    $.Select.fn.extend = $.extend;

	$.Select.fn.extend({
		change:function(){
			$("span",this.label).text($('option[value="'+this.select.val()+'"]',this.select).html());
			//console.log(this.select.val());
		},
		focus:function(){
			this.e.addClass("select_focus");
		},
		blur:function(){
			this.e.removeClass("select_focus");
		}
	});

	$.fn.Select=function(){
		return this.each(function(index){
			$(this).data('Select', new $.Select(this));
		});
	};

})(jQuery);