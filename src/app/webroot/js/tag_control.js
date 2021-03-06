$(function(){
	var tagCache = {};
	var tagSelected = [];
	function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#LabelTags" ).on("click",function(){
		$("#TemrNombre" ).focus();
	});
	$("#TagNombre").on( "keydown", function( event ) {
		if ( (event.keyCode === $.ui.keyCode.TAB && $( this ).data( "uiAutocomplete" ).menu.active)
			|| (event.keyCode === $.ui.keyCode.ENTER && $( this ).data( "uiAutocomplete" ).menu.active)
		) {
			//console.log(event.keyCode);
			event.preventDefault() ;
		}else if ( event.keyCode === $.ui.keyCode.ENTER || event.keyCode === $.ui.keyCode.TAB){
			$(this).before(
				$("<label />",{'html':$(this).val()}).append($("<input />",{'type':'hidden','name':"data[Tag][][nombre]",'value':$(this).val()})).click(function(){
					$(this).remove();
				})
			);
			this.value="";
			event.preventDefault() ;
		}else if( event.keyCode === $.ui.keyCode.BACKSPACE && $(this ).val().trim() == ""){
			if($("#LabelTags label:last").hasClass("delete")){
				checkboxId = "#"+$("#LabelTags label:last" ).attr("for");
				$("#LabelTags label:last" ).remove();
				checkboxChange($(checkboxId ).attr("checked",false));
				// se elimina del arreglo de seleccionados
				tagSelected.splice(tagSelected.indexOf(parseInt($(checkboxId ).val())),1);
			}else{
				$("#LabelTags label:last").addClass("delete");
			}
		}else if($("#LabelTags label:last").hasClass("delete")){
			$("#LabelTags label:last").removeClass("delete");
		}
	} ).on("blur",function(){
			$("#LabelTags label.delete" ).removeClass("delete");
	}).autocomplete({
			minLength:1,
			delay:100,
			source:function(request,response){
				var term = request.term;
				var data = [];
				if(term in tagCache){
					_data = tagCache[term];
					// Se quitan los elementos que ya se seleccionaron
					if(tagSelected.length > 0){
						var i = 0;
						console.log(tagSelected);
						$.each(_data,function(k,v){
							if( !_.contains( tagSelected, v.id ) ){
								data.push(v);
							}
						});

					}else{
						data = _data;
					}
					tagCache[term] = _data;
					response( data );
					return;
				}
				$.post("/terms/autocomplete/"+request.term+"/",serializeTags(),function(_data, status, xhr){
					// Se quitan los elementos que ya se seleccionaron
					if(tagSelected.length > 0){
						var i = 0;
						$.each(_data,function(k,v){
							if( !_.contains( tagSelected, v.id ) ){
								data.push(v);
							}
						});

					}else{
						data = _data;
					}
					tagCache[term] = _data;
					response( data );
				},"json");
			},
			search: function(){
			},
			focus: function() {
				return false;
			},
			select: function( event, ui ) {
				$("#TagTag"+ui.item.id)[0].checked = true;
				tagSelected.push(ui.item.id);
				checkboxChange($("#TagTag"+ui.item.id));
				this.value="";
				return false;
			}
		});

	$("#checkboxTags input:checkbox").on("change",function(){
		checkboxChange($(this));
		getTagSelected();
	});

	$(document ).on("click","#LabelTags label",function(){
		var checkboxId = "#"+$(this ).attr("for");
		tagSelected.splice(tagSelected.indexOf(parseInt($(checkboxId ).val())),1);
	});

	function checkboxChange(e){
		if( e[0] != undefined){
			if(e[0].checked){
				e.closest('.checkbox').css({display:'none'});
				$("#LabelTags input[type=text]").before(e.next().clone().attr("id",'LabelTag'+e.val()));
			}else{
				e.closest('.checkbox').css({display:'block'});
				$("#LabelTag"+e.val()).remove();
			}
		}
	}

	function getTagSelected(){

		$("#checkboxTags input:checkbox:checked").each(function(i,e){
			var id = parseInt($(e).val());
			if(! _.contains(tagSelected,id)){
				tagSelected.push(id);
			}
		});

	}

	function serializeTags(){
		var serie="";
		$("#checkboxTags input:checkbox:checked").each(function(i,e){
			serie+=encodeURI($(e).attr("name"))+"="+encodeURI($(e).val())+"&";
		});

		return serie;
	}


	$("#checkboxTags input:checkbox:checked").each(function(i,e){
		checkboxChange($(e));
	});
	getTagSelected();

});