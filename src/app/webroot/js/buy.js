// Contador de boletos
var countTickets = totalPayment = 0;
var countTicketsLimit = 10; // TODO: Poner dato en la configuracion del sitio en el CMS
var ticketsSelected = [];


$(document).on("ready",function(){

	/**
	 * Carga los asientos de la sala
	 */
	$.ajax({
		url:"/shows/seatlayout/"+$("#SeatLayout" ).data("show"),
		success:function(html){
			$("#SeatLayout" ).html(html);
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
		precio = $(".price",$parent ).data("price");
		$(".message",$container ).hide("fast");
		if(countTickets < countTicketsLimit){
			cantidad++;
			countTickets++;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			$qtyInput.attr("value",cantidad);
			updateTotals();
		}else{

		}
	});

	$(".ticketsSelection .less" ).on("click",function(e){
		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		$qtyInput = $(".qtyInput",$parent);
		cantidad = $cantidad.data("qty");
		precio = $(".price",$parent ).data("price");
		if(cantidad){
			cantidad--;
			countTickets--;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			$qtyInput.attr("value",cantidad);
			updateTotals();

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
			$cantidad = $(".cantidad",$parent);
			cantidad = $cantidad.data("qty");
			precio = $(".price",$parent ).data("price");
			subtotal = precio*cantidad;
			$(".subtotal",$parent ).html("$"+subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
			totalPayment+=subtotal;
			$(".ticketsSelection .total .value" ).html("$"+totalPayment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"))
		});
	}

	setCCTypeIcon($("#BuyCcnumber" ).val());

	$(document ).on("change",'#BuyCcnumber',function(){
		setCCTypeIcon($(this ).val());
	});


});

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
	if(ticketsSelected.length <= countTickets && countTickets){
		$(".seatCheck",$(this) ).attr("checked",true);
		$(this).removeClass("status-0");
		$(this).addClass("status-selected");
		var place = /place-\d+-\d+/.exec($(this ).attr("class"))[0];
		ticketsSelected.push(place);
		//console.log(ticketsSelected.length);
		//console.log(countTickets);
		if(ticketsSelected.length > countTickets){
			//console.log(ticketsSelected[0]);
			$("."+ticketsSelected[0] ).removeClass("status-selected");
			$("."+ticketsSelected[0] ).addClass("status-0");
			$(".seatCheck",$("."+ticketsSelected[0] ) ).attr("checked",false);

			ticketsSelected.splice(0,1);
		}

	}else if(countTickets == 0){
		$(".message .content",$container ).html("<p>"+__("no-tickets-selected-yet")+"</p><a href='#tickets' class='btn'>"+__("select-tickets")+"</a>");
		$(".message",$container).show("fast");
	}
}

function setSeatsSelected(){
	//console.dir(BuySeat);
	$.each(BuySeat,function(k,area){
		//console.dir(area);
		$.each(area.grid, function(k,v){
			if(v != "0"){
				$place = $(".place-"+v );
				$(".seatCheck",$place).attr("checked",true);
				$place.removeClass("status-0" ).addClass("status-selected");
				if($place.hasClass("status-1")){
					$place.removeClass("status-1" ).on("click",seatClick);
				}
				ticketsSelected.push("place-"+v);

			}
		});
	});
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