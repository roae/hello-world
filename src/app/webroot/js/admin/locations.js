$(document ).on("ready",function(){


	$('.premiereEndDate,.input-daterange,.premiereDate').on("changeDate",function(e){
		$( e.target ).next().val(e.format('yyyy-mm-dd'))
	});


	$(".premiere .btn" ).on("click",function(){
		$('.premiereEndDate',$(this ).parent() ).datepicker("show");
	});


	$(".locationCheckbox").change(function(){
		var $location = $(this).closest('.location');
		if($(this).is(":checked")){
			$(".commingSoon,.presale,.premiere",$location).show('fast');
			$(this ).parent().addClass("selected");
		}else{
			$(this ).parent().removeClass("selected");
			$(".commingSoon,.presale,.premiere",$location).hide('fast');

			$(".premiereEndDate",$location ).datepicker("update","" ).next().val("");

			$(".commingSoon input",$location ).attr("checked",false);

			$(".presaleCheckbox",$location ).attr("checked",false);

			$(".presale-daterange ",$location).hide('fast');

			$(".input-daterange input",$location ).val("")
			$(".input-daterange",$location ).datepicker("update","")

		}
	});

	$(".locationCheckbox" ).each(function(k,v){
		if(!$(v ).is(":checked")){
			$(".commingSoon,.presale,.premiere",$(this).closest('.location')).css({display:'none'});
		}else{
			$(this ).parent().addClass("selected");
		}
	});

	$(".commingSoonCheckbox").change(function(){
		var $commingSoon = $(this).closest('.commingSoon');
		if($(this).is(":checked")){
			$(".premierDate",$commingSoon).show('fast');
		}else{
			$(".premierDate",$commingSoon).hide('fast');

			$(".premierDate input",$commingSoon ).val("").datepicker("update","")
		}
	});

	$(".commingSoonCheckbox" ).each(function(k,v){
		if(!$(v ).is(":checked")){
			$(".premierDate",$(this).closest('.location')).css({display:'none'});
		}
	});

	$(".presaleCheckbox").change(function(){
		var $presale = $(this).closest('.presale');
		if($(this).is(":checked")){
			$(".presale-daterange",$presale).show('fast');
		}else{
			$(".presale-daterange",$presale).hide('fast');

			$(".input-daterange input",$presale ).val("")
			$(".input-daterange",$presale ).datepicker("update","")
		}
	});

	$(".presaleCheckbox" ).each(function(k,v){
		if(!$(v ).is(":checked")){
			$(".presale-daterange",$(this).closest('.location')).css({display:'none'});
		}
	});

	var dates = {
		es: {
			days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"],
			daysShort: ["Dom", "Lun", "Mar", "Mie", "Juv", "Vie", "Sab", "Dom"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			today: "Hoy",
			clear: "Borrar"
		}
	};

	var formatDate = function(date, format, language){
		if (!date)
			return '';

		var val = {
			d: date.getUTCDate(),
			D: dates[language].daysShort[date.getUTCDay()],
			DD: dates[language].days[date.getUTCDay()],
			m: date.getUTCMonth() + 1,
			M: dates[language].monthsShort[date.getUTCMonth()],
			MM: dates[language].months[date.getUTCMonth()],
			yy: date.getUTCFullYear().toString().substring(2),
			yyyy: date.getUTCFullYear()
		};
		val.dd = (val.d < 10 ? '0' : '') + val.d;
		val.mm = (val.m < 10 ? '0' : '') + val.m;
		date = [];
		var seps = $.extend([], format.separators);
		for (var i=0, cnt = format.parts.length; i <= cnt; i++){
			if (seps.length)
				date.push(seps.shift());
			date.push(val[format.parts[i]]);
		}
		return date.join('');
	}

});