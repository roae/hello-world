$(function(){
	$(document).on('click',".TogglePermision",function(event){
		event.preventDefault();
		var $this = $(this);
		$this.addClass("changing");
		$.ajax({
			url:$this.attr("href"),
			type:'get',
			success:function(html){
				$this.parent().html(html);
			}
		});
	});

	$(document).on("click",".controller",function(event){
		event.preventDefault();
		var $this=$(this);
		if(!$this.hasClass("showing")){
			if(!$this.data("loaded")){
				$this.addClass("loading_permissions");
				$.ajax({
					url:$this.attr("href"),
					success:function(html){
						$this.removeClass("loading_permissions");
						$this.after(html);
					}
				});
				$this.data("loaded",true);
			}
			$this.addClass("showing");
		}else{
			$this.removeClass("showing");
		}
	});
});