
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

	//$(".floating").Floating();

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

	//Website

	if( $('body').hasClass('home') ) {

		$('.mobile-apps').removeClass('small');

		var $slider = $('#main-slider'),
				$movies = $slider.find('.movie'),
				$pagination = $slider.find('.pagination'),
				current_movie = 1,
				total_movies = 3;

		$pagination.find('a').on('click', function(event) {

			event.preventDefault();

			var self = $(this),
					selected_index = self.parent().index();

			console.log('index: ' + selected_index);

			if( current_movie != (selected_index + 1) ) {

				$movies.fadeOut(200);
				$($movies[selected_index]).fadeIn(400);

				current_movie = selected_index + 1;

				$pagination.find('a').removeClass('current');
				self.addClass('current');

			}

		});

		$('.next-premieres .movies-list' ).owlCarousel({
			center:true,
			loop:true,
			items : 8,
			pagination : true,
	    	paginationNumbers: false,
	    	itemsCustom : false,
		    itemsDesktop : [1199,4],
		    itemsDesktopSmall : [980,3],
		    itemsTablet: [768,3],
		    itemsTabletSmall: false,
		    itemsMobile : [479,1],
		    singleItem : false,
		    itemsScaleUp : false,
		});

	}

	$('.the-content').waypoint(function(direction) {

	  	var $header = $('#main-header');

	  	if( direction == 'down' ) {
	  		$header.addClass('sticky');
	  		//$('body').css('padding-top', '118px');
	  	} else {
	  		$header.removeClass('sticky');
	  		//$('body').css('padding-top', '0');
	  	}

	});

	if($('.complex:has(.movies)' ).length){
		// Waypoint para empezar a mover el nombre de los complejos
		$('.complex').waypoint({
			handler:function(direction) {
				var $complexName = $(".complex-name",$(this.element));
				$complexName.toggleClass("sticky");
				if(direction == "down"){
					$(".movies",$(this.element) ).css({marginTop:$complexName.outerHeight()});
					$complexName.css({position: "fixed",top: "68px",zIndex: 8});
				}else{
					$complexName.css({position: "static"});
					$(".movies",$(this.element) ).css({marginTop:"auto"});
				}
				//console.log(direction);
			},
			continuous:false,
			offset:80
		});

		// Waypoint para detener el elemento sticky del complejo anterior

		$('.complex:has(.movies)').waypoint({
			handler:function(direction) {
				if(direction == "down"){
					if($(this.element).prev().hasClass("complex")){
						$(".complex-name",$(this.element).prev() ).css({position:'absolute',bottom:0,top:'auto'});
					}
				}else{
					if($(this.element).prev().hasClass("complex")) {
						$( ".complex-name", $( this.element ).prev() ).css( {position: 'fixed', top: '68px',bottom:'auto'} );
					}
				}
			},
			continuous:false,
			offset:140
		});
	}


	if($(".billboard-list" ).outerHeight() > $(".billboard-aside" ).outerHeight()){
		$(".billboard-aside .vertical-banner" ).waypoint({
			handler: function(direction){
				if(direction == "down"){
					$(this.element ).css({width: $(this.element ).outerWidth() }).css({position: "fixed",top: "100px",zIndex: 8});
				}else{
					$(this.element ).css({position:'static'});
				}

			},
			continuous:false,
			offset:'100px'
		});

		$("#main-footer" ).waypoint({
			handler: function(direction){
				console.log(direction);
				if(direction == "down"){
					$(".billboard-aside .vertical-banner").css({position: "absolute",bottom:0,top:"auto"});
				}else{
					$(".billboard-aside .vertical-banner").css({position: "fixed",bottom:"auto",top:"100px"});
				}

			},
			continuous:false,
			offset: $(".billboard-aside .vertical-banner").outerHeight()+100
		});
	}

	var functions = {
		remove_blur: function() {
			$('#main-header, .the-content, #main-footer').removeClass('blured');
		},
		add_blur: function() {
			$('#main-header, .the-content, #main-footer').addClass('blured');
		}
	};

	if( $('.trailer-trigger').length ) {


		$('.trailer-trigger').on('click', function(event) {

			event.preventDefault();

			if( $('#blured-lightbox').length ) {
				$('#blured-lightbox').remove();
			}

			var self = $(this),
					video_url = self.attr('href'),
					blured_lightbox = $('<div id="blured-lightbox"></div>'),
					blured_lightbox_content = $('<div class="blured-lightbox-content video"></div>'),
					blured_lightbox_loader = $('<span class="blured-lightbox-loader"></span>');
					blured_lightbox_close = $('<a class="blured-lightbox-close">Close</a>');
					blured_lightbox_title = $('<strong></strong>'),
					blured_lightbox_iframe = $('<iframe frameborder="0" allowfullscreen></iframe>'),
					video_id = '';

			if( video_url.indexOf('?v=') !== -1 ) {
				video_id = video_url.substr(video_url.indexOf('=') + 1);
			}

			functions.add_blur();

			blured_lightbox_title.html($('.blured-title').text());
			blured_lightbox_iframe.attr('src', '//www.youtube.com/embed/' + video_id + '?rel=0&showinfo=0&autohide=1&autoplay=1');

			blured_lightbox_content.append(blured_lightbox_title, blured_lightbox_iframe);

			$('.pause-flag').trigger('click');

			blured_lightbox.on('click', function() {
				functions.remove_blur();

				blured_lightbox.fadeOut(200, function() {
					blured_lightbox.remove();
					$('.pause-flag').trigger('click');
				});
			});

			$('body').append(blured_lightbox.append(blured_lightbox_loader, blured_lightbox_close, blured_lightbox_content));

		});

	}

	$(window).on('keyup', function(event) {
		if( event.keyCode == 27 ) {

			var blured_lightbox = $('#blured-lightbox');

			functions.remove_blur();

			blured_lightbox.fadeOut(200, function() {
				blured_lightbox.remove();
			});
		}
	});

	if( $('.movie-gallery-container').length ) {

		$('.movie-gallery-carousel').carouFredSel({
			width: '100%',
			scroll: {
				items: 0,
				duration: 10000,
				easing: 'linear'
			},
			duration: 10000,
			auto: {
				button: $('.pause-flag'),
				timeoutDuration: 0
			}
		});

		$('.litebox').liteBox({
		  revealSpeed: 400,
		  background: 'rgba(0,0,0,.8)',
		  overlayClose: true,
		  escKey: true,
		  navKey: true
		});

	}

	$('#header-location-select').find('.city-selector').on('click', function(event) {
		var self = $(this);

		if( self.attr('href') == '#' ) {
			event.stopPropagation();
			schedules_blured_lightbox(self);
		}
	});

	$('.home .buy-tickets').on('click', function(event) {

		var self = $(this);

		if( self.attr('href') == '#' ) {
			event.preventDefault();
			schedules_blured_lightbox(self, self.data('slug'));
			return false;
		}

	});

	function schedules_blured_lightbox(self, slug) {

		//event.preventDefault();

		var functions = {
			remove_blur: function() {
				$('#main-header, .the-content, #main-footer').removeClass('blured');
			},
			add_blur: function() {
				$('#main-header, .the-content, #main-footer').addClass('blured');
			}
		};

		if( $('#blured-lightbox').length ) {
			$('#blured-lightbox').remove();
		}

		var blured_lightbox = $('<div id="blured-lightbox"></div>'),
				blured_lightbox_content = $('<div class="blured-lightbox-content"></div>'),
				blured_lightbox_title = $('<strong class="dark geolocation">Selecciona tu ciudad</strong>'),
				blured_lightbox_places = $('<ul></ul>'),
				places_list = $('#header-location-select').find('.places li');

		functions.add_blur();

		if( slug ) {
			slug = '#' + slug;
		} else {
			slug = '';
		}

		//blured_lightbox_title.html($('.blured-title').text());

		places_list.each(function(i, e) {
			var place = $(e),
					link = place.find('a');

			blured_lightbox_places.append('<li><a href="' + link.attr('href') + slug + '">' + link.text() + '</a></li>');
		});

		blured_lightbox_content.append(blured_lightbox_title, blured_lightbox_places);

		blured_lightbox.on('click', function() {
			functions.remove_blur();

			blured_lightbox.fadeOut(200, function() {
				blured_lightbox.remove();
			});
		});

		$('body').append(blured_lightbox.append(blured_lightbox_content));
	}

	var login_functions = {
		remove_blur: function() {
			$('#main-header, .the-content, #main-footer').removeClass('blured');
		},
		add_blur: function() {
			$('#main-header, .the-content, #main-footer').addClass('blured');
		}
	};

	$('.account-container').find('.signin').on('click', function(event) {
		event.preventDefault();

		$('#login-container').addClass('show');
		login_functions.add_blur();
	});

	$(window).on('keyup', function(event) {
		if( event.keyCode == 27 ) {
			$('#login-container').removeClass('show');
			login_functions.remove_blur();
		}
	});

	$('#login-close').on('click', function(event) {
		event.preventDefault();

		$('#login-container').removeClass('show');
		login_functions.remove_blur();
	});

	if( $('#complex-container').length ) {

		var location_container = $('#complex-container'),
    		map = new GMaps({
		      div: '#map',
		      lat: 24.82301520608666,
		      lng: -107.36934023201599,
		      zoom: 12,
		      scrollwheel: false
		    }),
    		addresses = location_container.find('.address'),
    		cities = location_container.find('.city-trigger');

    addresses.each(function(i, e) {

    	var $e = $(e);

    	if( $e.css('display') == 'block' ) {
		    map.addMarker({
		      lat: $e.data('lat'),
		      lng: $e.data('lng'),
		      title: 'Lima'
		    });
    	}

    });

    cities.on('click', function(event) {
    	event.preventDefault();

    	var self = $(this),
    			city_id = self.data('id');

    	cities.removeClass('selected');
    	self.addClass('selected');

			addresses.fadeOut(200, function() {

				var current_addresses = location_container.find('.city-' + city_id),
						last_lat = 0,
						last_lng = 0;

				map.removeMarkers();

				current_addresses.each(function(i, e) {
		    	var $e = $(e),
		    			lat = $e.data('lat'),
		    			lng = $e.data('lng');

			    map.addMarker({
			      lat: lat,
			      lng: lng,
			      title: 'Lima'
			    });

			    if( lat != '' && lat != null ) {
				    last_lat = lat;
				    last_lng = lng;
			    }
		    });

		    map.setCenter(last_lat, last_lng);
				current_addresses.fadeIn(100);
			});
    });

	}

})(jQuery);