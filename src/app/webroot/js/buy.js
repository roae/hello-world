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


});

function seatSelection(){
	$container = $(".seatsSelection");
	$(".status-0",$container ).on("click",function(){
		if(ticketsSelected.length <= countTickets && countTickets){
			$(".seatCheck",$(this) ).attr("checked",true);
			$(this).removeClass("status-0");
			$(this).addClass("status-selected");
			var place = /place-\d+-\d+/.exec($(this ).attr("class"))[0];
			ticketsSelected.push(place);
			//console.log(ticketsSelected.length);
			//console.log(countTickets);
			if(ticketsSelected.length > countTickets){
				console.log(ticketsSelected[0]);
				$("."+ticketsSelected[0] ).removeClass("status-selected");
				$("."+ticketsSelected[0] ).addClass("status-0");
				$(".seatCheck",$("."+ticketsSelected[0] ) ).attr("checked",false);

				ticketsSelected.splice(0,1);
			}

		}else if(countTickets == 0){
			$(".message .content",$container ).html("<p>"+__("no-tickets-selected-yet")+"</p><a href='#tickets' class='btn'>"+__("select-tickets")+"</a>");
			$(".message",$container).show("fast");
		}

	});
}

function setSeatsSelected(){
	console.dir(BuySeat);
	$.each(BuySeat,function(k,area){
		console.dir(area);
		$.each(area.grid, function(k,v){
			if(v != "0"){
				$place = $(".place-"+v );
				$(".seatCheck",$place).attr("checked",true);
				$place.removeClass("status-0" ).addClass("status-selected");
				ticketsSelected.push("place-"+v);
			}
		});
	});
}