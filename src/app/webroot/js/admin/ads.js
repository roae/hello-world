$(function(){
	$(".banners .banner" ).css({display:'none'});
	$("#"+$("#AdType" ).val() ).css({display:'block'});

	$(document).on("change","#AdType",function(){
		$(".banners .banner" ).css({display:'none'});
		$("#"+$(this).val() ).css({display:'block'});
	});
});