// Contador de boletos
var countTickets = totalPayment = 0;
var countTicketsLimit = 10; // TODO: Poner dato en la configuracion del sitio en el CMS
var ticketsSelected = [];
var LayoutZoom = 1;
//var LayoutZoom = 0;
var LayoutLeft = 0;
//var LayoutRight = 0;
var LayoutTop = 0;
//var LayoutDown = 0;
var zoom = 1;
// Div contenedor de asientos.
var $container;


$(document).on("ready",function(){

	$("#BuyCcnumber,#BuyCvv" ).on("keypress",function(e){
		key = e.keyCode || e.which;
		//console.log(key);
		tecla = String.fromCharCode(key).toLowerCase();
		//console.log(tecla);
		//letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
		numeros = "1234567890"
		especiales = [8, 37, 39, 46, 9];

		tecla_especial = false
		for(var i in especiales) {
			if(key == especiales[i]) {
				tecla_especial = true;
				break;
			}
		}

		if(numeros.indexOf(tecla) == -1 && !tecla_especial)
			return false;
	});

	$("#BuyCcname" ).on("keypress",function(e){
		key = e.keyCode || e.which;
		tecla = String.fromCharCode(key).toLowerCase();
		letras = " abcdefghijklmnñopqrstuvwxyz";
		//numeros = "1234567890"
		especiales = [8, 37, 39, 46, 9];

		tecla_especial = false
		for(var i in especiales) {
			if(key == especiales[i]) {
				tecla_especial = true;
				break;
			}
		}

		if(letras.indexOf(tecla) == -1 && !tecla_especial)
			return false;
	});


	/**
	 * Carga los asientos de la sala
	 */
	$.ajax({
		url:"/shows/seatlayout/"+$("#SeatLayout" ).data("show"),
		success:function(html,status,http){

			eval('var Xnotifier = '+http.getResponseHeader('X-Notifier')+';');
			if(Xnotifier){
				if(Xnotifier.type = "error"){
					 window.location = urlError;
					return;
				}
			}
			$("#SeatLayout" ).html(html);
			//$("#SeatLayout" ).width($("#SeatLayout .layout" ).width());
			//$("#SeatLayout" ).height($("#SeatLayout .layout" ).height());
			$("#SeatLayout .layout" ).each(function(){
				new RTP.PinchZoom($(this), {
				});
			})

			seatSelection();
			setSeatsSelected();


		}
	});

	/**
	 * Funcionamiento de la seleccion de tickets
	 */

	$(".ticketsSelection .plus" ).on("click",function(e){

		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		$qtyInput = $("input[rel='qty']",$parent);
		cantidad = $cantidad.data("qty");
		precio = $("td.price",$parent) .data("price");
		code = $parent.data("code");
		$(".message",$container ).hide("fast");

		if(countTickets < countTicketsLimit){
			cantidad++;
			countTickets++;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			$qtyInput.attr("value",cantidad);
			Tickets[code].qty = cantidad;
			//$ticketResume = $("#buyResume .tickets-details tr[data-code='"+code+"']");
			//$ticketResume.css({display:'table-row'} ).addClass("showing");
			//$(".qty",$ticketResume).html(cantidad);
			//$("#buyResume .btnSelectTicket" ).css({display: 'none'});
			//$("#buyResume .btnSelectSeats" ).css({display: 'inline-block'});

			updateTotals();
			updateResume();
		}else{

		}
	});

	$(".ticketsSelection .less" ).on("click",function(e){
		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		$qtyInput = $("input[rel='qty']",$parent);
		cantidad = $cantidad.data("qty");
		code = $parent.data("code");
		precio = $("td.price",$parent) .data("price");
		if(cantidad != 0){
			cantidad--;
			countTickets--;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			$qtyInput.attr("value",cantidad);
			Tickets[code].qty = cantidad;
			// Se quita el ultimo asiento
			Tickets[code].seats.splice(Tickets[code].seats.length-1,1);
			buildTicketsSelectedArray();
			updateSeatsInputs();
			updateSeatGrid();

			/*$ticketResume = $("#buyResume .tickets-details tr[data-code='"+code+"']");
			if(cantidad == 0){
				$ticketResume.css({display:'none'} ).removeClass("showing");
			}*/
			/*if(countTickets == 0){
				$("#buyResume .btnSelectSeats" ).css({display: 'none'});
				$("#buyResume .btnSelectTicket" ).css({display: 'inline-block'});
			}*/
			//$(".qty",$ticketResume).html(cantidad);
			updateTotals();
			updateResume();

		}
	});

	$("input[rel='qty']").each(function(e){
		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		countTickets += $(this ).attr("value")*1;
		$cantidad.data("qty",$(this ).attr("value") ).html($(this ).attr("value"));
		$cantidad.data("qty");

	});
	updateTotals();

	$(document ).on("click",".closeMessage",function(){
		$(this ).closest(".message" ).hide("fast");
	})

	function updateTotals(){
		totalPayment = 0;
		$(".ticketsSelection tr" ).each(function(){
			$parent = $(this);
			code = $parent.data("code");
			$cantidad = $(".cantidad",$parent);
			cantidad = $cantidad.data("qty");
			precio = $("td.price",$parent) .data("price");
			subtotal = precio*cantidad;
			$(".subtotal",$parent ).html("$"+subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
			$ticketResume = $("#buyResume .tickets-details tr[data-code='"+code+"']");
			$(".subtotal",$ticketResume ).html("$"+subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
			totalPayment+=subtotal;
			$(".ticketsSelection .total .value" ).html("$"+totalPayment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"))
			$("#buyResume .total .value" ).html("$"+totalPayment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"))
		});
	}

	setCCTypeIcon($("#BuyCcnumber" ).val());

	$(document ).on("change",'#BuyCcnumber',function(){
		setCCTypeIcon($(this ).val());
	});
	var $BuyResume = $('#buyResume');
	$('.ticketsSelection').waypoint({
		handler: function(direction) {
			if( direction == 'down' ) {
				$BuyResume.addClass('sticky' )//.css({top: $("#main-header").outerHeight()+"px"});
				//$('body').css('padding-top', '118px');
			} else {
				$BuyResume.removeClass('sticky')//.css({top:'-200px'});
				//$('body').css('padding-top', '0');
			}

		},
		offset: 400
	});

	/*$window = $(window);
	$window.on("scroll",function(event){
		if($BuyResume.hasClass("sticky")){
			$BuyResume.css({top: $("#main-header").outerHeight()+"px"});
		}
	})*/

	$("#main-header" ).append($BuyResume);

	buyCountDown();
	buildTicketsArray();
	updateResume();

});



var minutos=0
var segundos=0
function buyCountDown(){
	if(remainingTime){
		//console.log(remainingTime);
		minutos=Math.floor(remainingTime/60)
		//console.log("minutos "+minutos);
		_remainingTime=remainingTime-(60*minutos)
		segundos=Math.floor(_remainingTime);
		//console.log(segundos);
		remainingTime--;
		//console.log(remainingTime);

		$("#buyResume .time .value" ).html(("00" + minutos).substr(-2,2)+":"+("00" + segundos).substr(-2,2));

		if (remainingTime>=0){
			setTimeout("buyCountDown()",1000)
		}
	}else if(remainingTime === 0){
		window.location = urlExp;
	}
}

function setCCTypeIcon(number){
	type = detectCardType(number.replace(/\s/,""));
	if(type){
		$(".ccType").removeClass("VISA" ).removeClass("MASTERCARD").addClass(type);
		$("#BuyCctype" ).val(type);
	}
}

function seatSelection(){
	$container = $(".seatsSelection");
	$(".status-0",$container ).on("click",seatClick);
}

function seatClick(){
	//console.log(getTotalSeatsSeleted());
	//console.log(countTickets);
	if(ticketsSelected.length <= countTickets && countTickets && !$(this ).hasClass("status-selected")){
		$(".seatCheck",$(this) ).attr("checked",true);
		$(this).removeClass("status-0");
		$(this).addClass("status-selected");
		//var place = /place-\w+-\d+-\d+-\d+/.exec($(this ).attr("class"))[0];

		ticketsSelected.push({
			row:$(this ).data("row"),
			column:$(this ).data("column"),
			row_physical:$(this ).data("row_physical"),
			column_physical:$(this ).data("column_physical"),
			area_category:$(this ).data("area_category"),
			area_number:$(this ).data("area_number")
		});
		//console.log(ticketsSelected.length);
		//console.log(countTickets);
		if(ticketsSelected.length > countTickets){
			//console.log("rochin");
			//console.log(ticketsSelected[0]);
			place_Class = ".place-"+ticketsSelected[0].row+"-"+ticketsSelected[0].column+"-"+ticketsSelected[0].area_category+"-"+ticketsSelected[0].area_number
			$(place_Class).removeClass("status-selected");
			$(place_Class).addClass("status-0");
			$(".seatCheck",$(place_Class) ).attr("checked",false);
			ticketsSelected.splice(0,1);

		}

		$("#buyResume .btnSelectSeats" ).css({display: 'none'});
		buildTicketsSeats();
		updateResume();
		updateSeatsInputs();
	}else if(countTickets == 0){
		$(".message .content",$container ).html("<p>"+__("no-tickets-selected-yet")+"</p><a href='#tickets' class='btn'>"+__("select-tickets")+"</a>");
		$(".message",$container).show("fast");
	}
}

function updateSeatsInputs(){
	var $seatsInputs = $("#seatsInputs" ).empty();
	var index = 0;
	$.each(Tickets,function(k,ticket){
		$.each(ticket.seats,function(i,seat){
			$seat = $("<div></div>",{class:'seat'});
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][row]",type:'hidden','value':seat.row,class:'row'}));
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][column]",type:'hidden','value':seat.column,class:'column'}));
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][row_physical]",type:'hidden','value':seat.row_physical,class:'row_physical'}));
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][column_physical]",type:'hidden','value':seat.column_physical,class:'column_physical'}));
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][area_category]",type:'hidden','value':seat.area_category,class:'area_category'}));
			$seat.append($("<input/>",{name:"data[BuySeat]["+index+"][area_number]",type:'hidden','value':seat.area_number,class:'area_number'}));
			$seatsInputs.append($seat);
			index++;
		});
	});
}

function getTotalSeatsSeleted(){
	var total =0;
	$.each(Tickets,function(k,v){
		total += v.seats.length;
	});
	return total;
}

var Tickets = {};
function buildTicketsArray(){
	//console.dir(BuySeat);
	var index =0;
	$(".ticketsSelection tr" ).each(function(){
		code = $(this ).data("code");
		if(code){
			cantidad = $(".cantidad",$(this ) ).data("qty");
			Tickets[code] = {
				qty:cantidad,
				seats:[]
			}
			if(cantidad > 0 && BuySeat){

				for(i = 0; i<cantidad; i++){
					Tickets[code].seats.push(BuySeat[index]);
					index++;
				}
			}
		}

	});
}

function buildTicketsSelectedArray(){
	ticketsSelected = []
	$.each(Tickets,function(code,ticket){
		$.each(ticket.seats,function(i,seat){
			ticketsSelected.push(seat);
		});
	});
}

function updateResume(){
	$("#buyResume .tickets-details .ticket" ).removeClass("showing").css({display:'none'} );
	$.each(Tickets,function(code,ticket){
		//console.log(code);
		//console.dir(ticket);
		if(ticket.qty > 0){

			$ticket = $("#buyResume .tickets-details tr[data-code='"+code+"']");
			//console.dir($ticket);
			$ticket.addClass("showing" ).css({display:'table-row'} );
			$(".qty",$ticket ).html(ticket.qty);

			seats = "";
			$seats = $(".seats",$ticket);
			$.each(ticket.seats,function(k,v){
				seats+= v.row_physical+ v.column_physical+" ";
			});
			$seats.html(seats);

		}
	});
	if(countTickets > 0){
		$("#buyResume .btnSelectTicket" ).css({display:'none'});
		$("#buyResume .btnSelectSeats" ).css({display:'inline-block'});
	}else{
		$("#buyResume .btnSelectTicket" ).css({display:'inline-block'});
		$("#buyResume .btnSelectSeats" ).css({display:'none'});
	}
	if(getTotalSeatsSeleted() > 0){
		$("#buyResume .btnSelectSeats" ).css({display:'none'});
	}else if(countTickets>0){
		$("#buyResume .btnSelectSeats" ).css({display:'inline-block'});
	}
}

function updateSeatGrid(){
	$("#SeatLayout .status-selected" ).removeClass("status-selected" ).addClass("status-0");
	$.each(Tickets,function(code,ticket){
		$.each(ticket.seats,function(i,seat){
			place_Class = ".place-"+seat.row+"" +"-"+seat.column+"-"+seat.area_category+"-"+seat.area_number;
			console.log(place_Class);
			$place = $(place_Class );
			$place.removeClass("status-0" ).addClass("status-selected");
			if($place.hasClass("status-1")){
				$place.removeClass("status-1" ).on("click",seatClick);
			}
		});
	});
}


/*function updateSeatsResume(){
	$("#buyResume .tickets-details .showing").each(function(){
		$seats = $(".seats",$(this));
		if(Tickets[$(this ).data("code")].seats){
			seats = "";
			$.each(Tickets[$(this ).data("code")].seats,function(k,v){
				seats+= v.row_physical+ v.column_physical+" ";
			});
			$seats.html(seats);
		}
	});
}*/

function buildTicketsSeats(){
	$.each(Tickets,function(k,v){
		Tickets[k].seats = [];
	});
	$.each(ticketsSelected,function(i,place){
		//console.dir(place);
		done = false;
		$.each(Tickets,function(k,v){
			if(v.qty > v.seats.length && !done){
				//pieces = place.split("-");
				Tickets[k].seats.push({
					row_physical:place.row_physical,
					row:place.row,
					column_physical:place.column_physical,
					column:place.column,
					area_category:place.area_category,
					area_number:place.area_number
				});
				done = true;
			}
		});
	});
}

function setSeatsSelected(){
	if(BuySeat){
		$.each(BuySeat,function(i,seat){
			//console.dir(area);
			place_Class = ".place-"+seat.row+"" +"-"+seat.column+"-"+seat.area_category+"-"+seat.area_number;
			$place = $(place_Class );
			$place.removeClass("status-0" ).addClass("status-selected");
			if($place.hasClass("status-1")){
				$place.removeClass("status-1" ).on("click",seatClick);
			}
			ticketsSelected.push(seat);
		});
		//console.dir(ticketsSelected);
		buildTicketsSeats();
		updateSeatsInputs();
	}
}

function detectCardType(number) {
	var re = {
		electron: /^(4026|417500|4405|4508|4844|4913|4917)\d+$/,
		maestro: /^(5018|5020|5038|5612|5893|6304|6759|6761|6762|6763|0604|6390)\d+$/,
		dankort: /^(5019)\d+$/,
		interpayment: /^(636)\d+$/,
		unionpay: /^(62|88)\d+$/,
		visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
		mastercard: /^5[1-5][0-9]{14}$/,
		amex: /^3[47][0-9]{13}$/,
		diners: /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/,
		discover: /^6(?:011|5[0-9]{2})[0-9]{12}$/,
		jcb: /^(?:2131|1800|35\d{3})\d{11}$/
	};
	if (re.electron.test(number)) {
		return 'ELECTRON';
	} else if (re.maestro.test(number)) {
		return 'MAESTRO';
	} else if (re.dankort.test(number)) {
		return 'DANKORT';
	} else if (re.interpayment.test(number)) {
		return 'INTERPAYMENT';
	} else if (re.unionpay.test(number)) {
		return 'UNIONPAY';
	} else if (re.visa.test(number)) {
		return 'VISA';
	} else if (re.mastercard.test(number)) {
		return 'MASTERCARD';
	} else if (re.amex.test(number)) {
		return 'AMEX';
	} else if (re.diners.test(number)) {
		return 'DINERS';
	} else if (re.discover.test(number)) {
		return 'DISCOVER';
	} else if (re.jcb.test(number)) {
		return 'JCB';
	} else {
		return undefined;
	}
}