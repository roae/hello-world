(function($){

	$.Label=function(e){
		var $this=$(e);
		this.input=$('input,textarea',$this).bind('focus',$.proxy(this,'focus')).bind('blur',$.proxy(this,'blur'));
		this.label=$('label',$this);
		if(this.input.val()!=""){
			this.label.css("display","none");
		}
	};

	var $L = $.Label;

    $L.fn = $L.prototype = {
        Label: '0.1'
    };

    $L.fn.extend = $.extend;

	$L.fn.extend({
		focus:function(){
			this.label.css({display:'none'});
		},
		blur:function(){
			if(this.input.val()==""){
				this.label.css({display:'block'});
			}
		}
	});

	$.fn.Label=function(){
		return this.each(function(index){
			$(this).data('Label', new $L(this));
		});
	};
	
})(jQuery);