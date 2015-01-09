$(document).ready(function(){
	$(".link:has(a)").click(function(){window.location =  $("a", this).attr('href');}).hover(function(){$(this).addClass('hover');}, function(){$(this).removeClass('hover');});

	$('.mousestate').hover(function(){$(this).addClass('hover');}, function(){$(this).removeClass('hover');}).bind('mousedown',function(){$(this).addClass('mousedown')}).bind('mouseup',function(){$(this).removeClass('mousedown')});

	$('.inputSearch,#LoginForm .text,#LoginForm .password, form.newsletter .input').Label();

	$("#slideshow").nivoSlider({directionNav: false,controlNav: false,effect:'fade',animSpeed:500,pauseTime:4500,boxCols:4});

	var ac=$("#RestaurantLocation").autocomplete({
		serviceUrl:'/locations/auto/',
		deferRequestBy:200,
		beforeRequest:function(){
			$("#featured .searchbutton").addClass("loading");
		},
		afterRequest:function(data){
			$("#featured .searchbutton").removeClass("loading");
		},
		onSelect:function(value,data){
			$("#LocationId").val(data);
		}
	});
	ac.enable();

	/*$("#login").click(function(){
		$.scrollTo("#LoginForm",500);
		return false;
	});*/

});

$(window).load(function(){
	$(".login_form").Floating();
});