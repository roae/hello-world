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
		}
	});

	/**
	 * Funcionamiento de la seleccion de tickets
	 */

	$(".ticketsSelection .plus" ).on("click",function(e){
		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		cantidad = $cantidad.data("qty");
		precio = $(".price",$parent ).data("price");
		$(".message",$container ).hide("fast");
		if(countTickets < countTicketsLimit){
			cantidad++;
			countTickets++;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			subtotal = precio*cantidad;
			$(".subtotal",$parent ).html("$"+subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
			totalPayment+=precio;
			$(".ticketsSelection .total .value" ).html("$"+totalPayment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"))
		}else{

		}
	});

	$(".ticketsSelection .less" ).on("click",function(e){
		$parent = $(this ).closest("tr");
		$cantidad = $(".cantidad",$parent);
		cantidad = $cantidad.data("qty");
		precio = $(".price",$parent ).data("price");
		if(cantidad){
			cantidad--;
			countTickets--;
			$cantidad.data("qty",cantidad);
			$cantidad.html(cantidad);
			subtotal = precio*cantidad;
			$(".subtotal",$parent ).html("$"+subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
			totalPayment-=precio;
			$(".ticketsSelection .total .value" ).html("$"+totalPayment.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"))

		}
	});

	$(document ).on("click",".closeMessage",function(){
		$(this ).closest(".message" ).hide("fast");
	})

});

function seatSelection(){
	$container = $(".seatsSelection");
	$(".status-0",$container ).on("click",function(){
		if(ticketsSelected.length <= countTickets && countTickets){
			$(this).removeClass("status-0");
			$(this).addClass("status-selected");
			ticketsSelected.push(/place-\d+-\d+/.exec($(this ).attr("class"))[0]);
			if(ticketsSelected.length > countTickets){
				$("."+ticketsSelected[0] ).removeClass("status-selected");
				$("."+ticketsSelected[0] ).addClass("status-0");
				ticketsSelected.splice(0,1);
			}

		}else if(countTickets == 0){
			$(".message .content",$container ).html("<p>"+__("no-tickets-selected-yet")+"</p><a href='#tickets' class='btn'>"+__("select-tickets")+"</a>");
			$(".message",$container).show("fast");
		}

	});
}