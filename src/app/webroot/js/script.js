
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) {
    arguments.callee = arguments.callee.caller;
    var newarr = [].slice.call(arguments);
    (typeof console.log === 'object' ? log.apply.call(console.log, console, newarr) : console.log.apply(console, newarr));
  }
};

// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();){b[a]=b[a]||c}})((function(){try
{console.log();return window.console;}catch(err){return window.console={};}})());


// place any jQuery/helper plugins in here, instead of separate, slower script files.
/*Modernizr.load([
	{
		load:'/js/ext/jquery.ui.js'
	},
	{
		load: "/js/ext/jquery.label.js",
		complete: function(){
			$(function(){
				$('#Search .input,#contactFooter .text,#contactFooter .textarea,#CommentForm .text,#CommentForm .textarea,#contactForm .text,#contactForm .textarea,.search_quest_inner .input, #AddQuestion .question, #AddQuestion .details, aside .captcha').Label();
			});
		}
	}
]);*/

var item_act = 1;
var item_ant = 1;
var $slides = $("#Slider .slide");
var items = $slides.length;
var loadedItems = 0;
var slideActive = true;
var tiempo=5000;
var intervalSlider;

$(function(){

	// Cache the Window object
	$window = $(window);

	$('[data-type="background"]').each(function(){
		var $bgobj = $(this); // assigning the object

		$(window).scroll( function(){
			parallaxBg($bgobj);
		}); // window scroll Ends
		parallaxBg($bgobj);
	});
	function parallaxBg($bgobj){
		// Scroll the background at var speed
		// the yPos is a negative value because we're scrolling it UP!
		var yPos = -($window.scrollTop() / $bgobj.data('bg-speed'));

		if ( $bgobj.data( "bg-relative" ) != undefined ) {
			yPos += $bgobj.offset().top;
		}

		if($bgobj.data("bg-direction") != undefined && $bgobj.data("bg-direction") == "down"){
			yPos*=-1;
		}

		// Put together our final background position
		var coords = '50% '+ yPos + 'px';

		// Move the background
		$bgobj.css({ backgroundPosition: coords });
	}

	$header = $("header");
	$window.on("scroll",function(event){
		if($window.scrollTop() > 80){
			$header.addClass("fixed");
		}else{
			$header.removeClass("fixed");
		}
		// Animacion de los elementos cuando estan visibles
		elementAppear();
	});


	allMods = $(".appear");

// Already visible modules
	allMods.each(function(i, el) {
		var el = $(el);
		if (el.visible(true)) {
			el.addClass("already-visible");
		}
	});

	function elementAppear(){
		allMods.each(function(i, el) {
			var el = $(el);
			if (el.visible(true)) {
				el.delay(200).addClass("come-in");
			}
		});
	}

	$(".link:has(a.fwd)").on('click',function(event){
		if(event.target.tagName.toUpperCase()!="A" || event.target.tag.toUpperCase()!="BUTTON"){
			window.location =  $("a.fwd", this).attr('href');
		}
	}).on('mousemove',function(){
		$(this).addClass('hover');
	}).on('mouseout', function(){
		$(this).removeClass('hover');
	});

	$('.mousestate').hover(function(){$(this).addClass('hover');}, function(){$(this).removeClass('hover');}).bind('mousedown',function(){$(this).addClass('mousedown')}).bind('mouseup',function(){$(this).removeClass('mousedown')});


	$("#Slider .slide img").imgLoad(function(){
		loadedItems++;
		$(this).data("loaded",1);
		if(loadedItems == 1){
			changeSlide($(this ).data("item"));
		}
		//console.log("item: "+$(this ).data("item")+" - total:"+loadedItems);
	});

	$("#Slider .next" ).click(nextSlide);
	$("#Slider .prev" ).click(prevSlide);

	$(".redes .red" ).click(function(){
		window.open($(this ).attr("href"),'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
		return false;
	});

	if($("#CommentForm form").length){
		$(document).on("submit","#CommentForm form",function(){
			//$(this).append($('<div />',{'class':'loading'}));
			$(".loading",$(this)).css({'display':'block'});
			$.ajax({
				url:$(this).attr("action"),
				data:$(this).serialize(),
				type:'POST',
				success:function(html,status,http){
					eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');
					$("#ajaxComment").html(html);
					if(Xnotifier){
						$("#ajaxComment").prepend($("<div />",{'id':'Notifier'}).append($("<div />",{'class':Xnotifier.class}).append($("<span />",{'class':'icon'})).append(Xnotifier.message)));
					}
					$('#CommentForm .text,#CommentForm .textarea').Label();
				},
				beforeSend:function(http){
					http.setRequestHeader("X-update","ajaxComment");
				}
			});

			return false;
		});
	}
});

function changeSlide(item){
	if($("#Slider .img"+item ).data("loaded")){
		//console.log("changeSlide "+item);
		clearInterval(intervalSlider);
		item_ant = item_act;
		item_act = item;
		$("#Slider .loading" ).finish().css({width:"0px"});
		$("#Slider .slide-"+item_ant ).animate({'opacity':0},500);
		$("#Slider .slide-"+item ).animate({'opacity':1},500);
		intervalSlider=setInterval("nextSlide()",tiempo);
		$("#Slider .loading" ).animate({width: "100%"},tiempo);
	}else{
		item_act = item;
		nextSlide();
	}
}

function prevSlide(){
	if(loadedItems > 1){
		if(slideActive){
			//slideActive = false;
			var item = item_act - 1;

			if($("#Slider .img"+item ).length == 0){
				item = items;
			}
			changeSlide( item );
		}
	}
}

function nextSlide(){
	//console.log(loadedItems);
	if(loadedItems > 1){
		if(slideActive){
			//slideActive = false;
			var item = item_act + 1;
			//console.log("nextSlide "+item);
			if($("#Slider .img"+item ).length == 0){
				item = 1;
			}
			changeSlide( item );
		}
	}
}

/**
 * Copyright 2012, Digital Fusion
 * Licensed under the MIT license.
 * http://teamdf.com/jquery-plugins/license/
 *
 * @author Sam Sehnert
 * @desc A small plugin that checks whether elements are within
 *     the user visible viewport of a web browser.
 *     only accounts for vertical position, not horizontal.
 */

(function($) {
	$.fn.visible = function(partial) {
		var $t            = $(this),
			$w            = $(window),
			viewTop       = $w.scrollTop(),
			viewBottom    = viewTop + $w.height(),
			_top          = $t.offset().top,
			_bottom       = _top + $t.height(),
			compareTop    = partial === true ? _bottom : _top,
			compareBottom = partial === true ? _top : _bottom;

		return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
	};
})(jQuery);

/**
 * Trigger a callback when 'this' image is loaded:
 * @param {Function} callback
 */
(function($){
	$.fn.imgLoad = function(callback) {
		return this.each(function() {
			if (callback) {
				if (this.complete || /*for IE 10-*/ $(this).height() > 0) {
					callback.apply(this);
				}
				else {
					$(this).on('load', function(){
						callback.apply(this);
					});
				}
			}
		});
	};
})(jQuery);