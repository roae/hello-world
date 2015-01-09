/**
 * jQuery.UploadInput Plugin jQuery
 * @version 0.1
 * Lleva el control de la subida de archivos
 *
 * @autor Efrain Rochin Aramburo
 * @date 14 - Mayo - 2013
 */
;(function($) {

	$.fn.UploadI = function() {
		return this.each(function(){
			var $this=$(this);
			var $btnChooseFiles;
			var $btnUploadHelp;
			var $btnCancelAll;
			var $btnDeleteAll;
			var $queue;
			var $input=$(":file",$this);
			// Contador de archivos cargando
			var loading=0;

			// arreglo q guarda los archivos que vienen de data
			var filesLoaded;

			// numero de archivos cargados
			var loaded=0;

			var queueSize=0;

			var inputId=0;

			var _Layout,_Item,_Animation;

			var folder="/tmp";

			var filesInQueue=[];

			var matches = $input.attr('name').match(/data\[(.*)\]\[(.*)\]/);
			var model = matches[1];
			var alias = matches[2];

			eval('var config = '+$input.data("config").replace(/\\(.)/g,'$1')+';');
			if(typeof $input.data("loaded") == "string" || $input.data("loaded") instanceof String){
				eval('filesLoaded = '+$input.data("loaded").replace(/\\(.)/g,'$1')+';');
			}else{
				var filesLoaded=$input.data("loaded");
			}

			if(filesLoaded==null){ // si no se han cargado archivos
				filesLoaded=false;
			}else if(filesLoaded.id){ // si solo vienen un archivo
				filesLoaded={0:filesLoaded};
			}

			// Creaccion de la interface
			if(config.limit==1){
				_Layout=window[config.skin].Single.layout;
				_Item=window[config.skin].Single.item;
				_Animation=window[config.skin].Single.animation;
			}else{
				_Layout=window[config.skin].Multiple.layout;
				_Item=window[config.skin].Multiple.item;
				_Animation=window[config.skin].Multiple.animation;
			}
			//console.dir(config.allowed);

			$this.createAppend(_Layout);

			$('.label',$this).append($("label",$this).remove());
			$btnChooseFiles=$(".ChooseFiles",$this);
			$btnUploadHelp=$(".UploadHelp",$this);
			$btnCancelAll=$(".CancelAllUploads",$this);
			$btnDeleteAll=$(".DeleteAllUploads",$this);
			$statusBar=$(".statusBar",$this);
			$queue=$(".UploadiQueue",$this);

			if(config.sortable){
				$('.sortable',$this).sortable({placeholder: 'placeholder',opacity: 0.9});
			}

			$input.closest('form').submit(function(){ // Alert que aparece cuando se intenta hacer submit y aun se estan cargando los archivos
				if(loading){
					alert(__("loading_files"));
					return false;
				}
				return true;
			});

			$btnChooseFiles.click(function(){
				if(loaded+loading < config.limit){
					$input.click();
				}else{
					if(!$this.find(".notifications .limit").length){
						notification(__("se-alcanzo-el-limite-de-archivos-permitidos"),"error limit");
					}
				}

				return false;
			})

			$btnCancelAll.click(function(){
				$input.UploadFiles("abort");
				loading=0;
				$this.find(".UploadiQueue .uploading").animate(_Animation.hidde.properties,_Animation.hidde.options);
				_refreshStatus();
			});

			$btnDeleteAll.click(function(){
				if(!$this.find(".UploadiConfirm").length){
					var $confirm=$.create([
						//'div',{'class':'UploadiOverlay'},[],
						'div',{'class':'UploadiConfirm'},[
							'span',{'class':'text'},__("delete_all_uploads"),
							'div',{'class':'UploadiConfirmButtons'},[
								'button',{'class':'btn acept'},__("yes"),
								'button',{'class':'btn cancel'},__("no"),
							]
						]
					]);
					$confirm.fadeIn("slow");
					$confirm.find(".cancel").click(function(){
						$confirm.slideUp("slow",function(){$confirm.remove()});
						return false;
					});
					$confirm.find(".acept").click(function(){
						$(".UploadiQueueItem.completed", $this).each(function(index){
							$id=jQuery('.hidden',jQuery(this)).find('[name$="[id]"]');
							$('.deleted',$this).append($id.remove().attr('value',-$id.val()));
							loaded--;
							_refreshStatus();
							$(this).delay(index * 100).animate(_Animation.hidde.properties,_Animation.hidde.options,function(){$(this).remove()});
						});
						$btnDeleteAll.addClass('deleteDisabled');
						$confirm.slideUp("slow",function(){$confirm.remove()});
						return false;
					});
					$this.find(".UploadToolbar").after($confirm);
					filesInQueue=[];
				}
				return false;

			});

			//console.dir(filesLoaded);

			// se agregan los archivos que vienen de la BD

			if(filesLoaded){
				$.each(filesLoaded,function(i,file){

					var src,ID;
					if(!file.id){
						src=folder+"/~"+file.temp+"."+file.extension;
						ID=file.tempId;
					}else{
						src=file.thumb;
						ID=_randomCode();
					}
					var $item=$.create(_Item);

					var byteSize = Math.round(file.size / 1024 * 100) * .01;
					var suffix = 'KB';
					if (byteSize > 1000) {
						byteSize = Math.round(byteSize *.001 * 100) * .01;
						suffix = 'MB';
					}
					var sizeParts = byteSize.toString().split('.');
					if (sizeParts.length > 1) {
						byteSize = sizeParts[0] + '.' + sizeParts[1].substr(0,2);
					} else {
						byteSize = sizeParts[0];
					}

					$item.attr("id",model+alias+ID).addClass("completed");
					$item.find(".fileName").text(file.name+"."+file.extension);
					$item.find(".fileSize").text(byteSize + suffix);
					$item.find(".thumb").attr("src",src);
					$item.data("ID",loaded);
					$item.data("inputId",inputId);
					$item.createAppend(
						'div',{'class':'data'},[
							'div',{'class':'input text'},[
								'label',{'for':alias+inputId+'name'},__("file-name"),
								'input',{'class':'name','type':'text','value':file.name,'name':'data['+alias+']['+inputId+'][name]','id':alias+inputId+'name'},[],
							],
							'div',{'class':'input text'},[
								'label',{'for':alias+inputId+'alt'},__("img-alt-attr"),
								'input',{'class':'name','type':'text','value':file.alt,'name':'data['+alias+']['+inputId+'][alt]','id':alias+inputId+'alt'},[],
							],
							'div',{'class':'input textarea'},[
								'label',{'for':alias+inputId+'description'},__("file-description"),
								'textarea',{'class':'description','name':'data['+alias+']['+inputId+'][description]','id':model+alias+'description'},file.description,
							],
							'input',{'class':'extension hidden','value':file.extension,'name':'data['+alias+']['+inputId+'][extension]'},[],
							'input',{'class':'size hidden','value':file.size,'name':'data['+alias+']['+inputId+'][size]'},[],
							'input',{'class':'temp hidden','value':file.temp,'name':'data['+alias+']['+inputId+'][temp]'},[],
							'input',{'class':'mime hidden','value':file.mime,'name':'data['+alias+']['+inputId+'][mime]'},[],
							'input',{'class':'ID hidden','value':ID,'name':'data['+alias+']['+inputId+'][tempId]'},[],
							'button',{'class':'btn btn_primary aceptEdit','onclick':'return false;'},__("acept"),
						]
					);

					if(file.id){
						$item.find(".hidden").append($('<input/>',{'value':file.id,'name':'data['+alias+']['+inputId+'][id]'}))
					}

					$item.find(".deleteItem").click(function(){
						$id=$item.find(".hidden").find('[name$="[id]"]');
						$(".deleted",$this).append($id.remove().attr('value',-$id.val()));
						filesInQueue.splice($item.data("inputId"),1);
						loaded--;
						_refreshStatus();
						$item.animate(_Animation.hidde.properties,_Animation.hidde.options,function(){$(this).remove()});
						return false;
					});

					$item.find(".editItem").click(function(){
						$item.addClass("editingItem");
					});

					$item.find(".aceptEdit").click(function(){
						$(this).closest(".editingItem").removeClass("editingItem");
					});
					$btnDeleteAll.removeClass("deleteDisabled");
					$queue.append($item);
					inputId++;
					loaded++;
					file.name+="."+file.extension;
					filesInQueue.push(file);

				});
				filesLoaded={}; // se limpiar la variable para ahorrar memoria
				_refreshStatus();
			}

			function _addFile(i,file){
				//console.dir(file)
				//console.log(file.name);
				if(loaded+loading == config.limit){ // si se alcanzo el limite de archivos permitidos
					if(!$this.find(".notifications .limit").length){
						if(config.limit==1){
							notification(__("solo-se-puede-subir-un-archivo"),"error limit");
						}else{
							notification(__("se-alcanzo-el-limite-de-archivos-permitidos"),"error limit");
						}
					}
					return false;
				}else if($.inArray(file.type,_jsonToArray(config.allowed))<0){ // si es de un tipo de archivo que no se esta permitido
					if(!$this.find(".notifications .denied").length){
						notification(__("tipo-archivo-no-permitido"),"error denied");
					}
					return false;
				}else if(file.size > (1048576 * config.max_file_size)) { // si el archivo es mas grande (mb) de lo permitido
					notification(file.name+" "+__("demasiado-grande"),"error");
					return false;
				}else if(!_unico(file.name)){ // si ya existe otro archivo con el mismo nombre
					if(!$this.find(".notifications .duplicated").length){
						notification(__("no-archivos-mismo-nombre"),"error duplicated");
					}
					return false;
				}else{ // si no hay ningún problema con el archivo
					var byteSize = Math.round(file.size / 1024 * 100) * .01;
					var suffix = 'KB';
					if (byteSize > 1000) {
						byteSize = Math.round(byteSize *.001 * 100) * .01;
						suffix = 'MB';
					}
					var sizeParts = byteSize.toString().split('.');
					if (sizeParts.length > 1) {
						byteSize = sizeParts[0] + '.' + sizeParts[1].substr(0,2);
					} else {
						byteSize = sizeParts[0];
					}

					var $item=$.create(_Item);

					$item.find(".fileName").text(file.name);
					$item.find(".fileSize").text(byteSize + suffix);
					$item.attr("id","UploadiQueueItem"+i).data("ID",i).addClass("uploading");

					$item.find(".cancelItem").click(function(){
						$input.UploadFiles("abort",i);
						$item.animate(_Animation.hidde.properties,_Animation.hidde.options);
						loading--;
						filesInQueue.splice(loaded+i,1);
						_refreshStatus();
					});

					/*
						si el archivo es una imagen y el navegador suporta FileReader,
						presenta la previsualizacion en la lista de archivos
					*/
					if (typeof FileReader !== "undefined" && (/image/i).test(file.type)) {
						reader = new FileReader();
						reader.onload = function (evt) {
							//theImg.src = evt.target.result;
							$(".thumb",$item).attr("src",evt.target.result);
						};

						reader.readAsDataURL(file);
					}else if(!(/image/i).test(file.type)){
						var extension=file.name.substring(file.name.lastIndexOf(".")+1);
						icons=/doc|docx|zip|tar|rar|xls|xlsx|pdf|txt|ppt|pptx|swf$/;
						$(".thumb",$item).attr("src",(icons).test(extension) ? '/media/img/'+extension+".png" : '/media/img/unknown.png');

					}

					$(".UploadiQueue",$this).append($item);
					$item.animate(_Animation.appear.properties,_Animation.appear.options);
					filesInQueue.push(file);
					loading++;
					if(loaded+loading == config.limit){
						$btnChooseFiles.addClass("chooseDisabled");
					}
					_refreshStatus();
				}
				return true;
			}

			function _unico(name){
				for(var i=0; i<filesInQueue.length; i++){
					if(filesInQueue[i].name == name){
						return false;
					}
				}
				return true;
			}

			function _refreshStatus(){
				var status="";
				if(loading>0){
					$btnCancelAll.removeClass("cancelDisabled");
					$('.statusBar',$this).addClass("loadingFiles");
					if(loaded){
						$btnDeleteAll.removeClass("deleteDisabled");
						status+=__('files_loaded')+": "+loaded+" | "+__('files_loading')+": "+loading;
					}else{
						status+ __('files_loading')+": "+loading;
					}
					$this.removeClass("uploadingFiles");
				}else{
					loading=0;
					if(loaded){
						$btnDeleteAll.removeClass("deleteDisabled");
						status+=__('files_loaded')+": "+loaded;
					}else{
						status+=__('no_files_loaded');
					}
					$('.statusBar',$this).removeClass("loadingFiles");
					$btnCancelAll.addClass("cancelDisabled");
					$this.removeClass("uploadingFiles");
				}
				if(loaded+loading >= config.limit){
					$btnChooseFiles.addClass("chooseDisabled");
				}else{
					$btnChooseFiles.removeClass("chooseDisabled");
				}
				$('.statusBarText',$this).html(status);
			}

			function notification(message,klass){
				//$this.find(".messages").aClass(type).find(".text").html(message).show();
				var $notification= $.create([
					'div',{'class':"message "+klass},[
						'span',{'class':'text'},message,
						'span',{'class':'close icon-remove'},[],
					]
				]);
				$this.find(".notifications").append($notification.fadeIn("slow"));
				$notification.find(".close").click(function(){
					$notification.stop().slideUp(function(){$(this).remove();});
				});
				$notification.delay(4000).slideUp(function(){$(this).remove()});
			}



			$input.UploadFiles({
				dropElement:$(this),
				url:'/media/files/add/model:'+model+"/alias:"+alias,
				paramname:'data['+model+']['+alias+']',
				withCredentials:true,
				refresh:20,
				error: function(err, file) {
					switch(err) {
						case 'BrowserNotSupported':
							alert(__('browser_does_not_support_html5_drag_and_drop'))
							break;
						case 'TooManyFiles': // user uploaded more than 'maxfiles'
							break;
						case 'FileTooLarge':
							// program encountered a file whose size is greater than 'maxfilesize'
							// FileTooLarge also has access to the file which was too large
							// use file.name to reference the filename of the culprit file
							//console.dir(file);
							break;
						case 'FileTypeNotAllowed': // The file type is not in the specified list 'allowedfiletypes'
						default:
							break;
					}
				},
				allowedfiletypes: config.allowed,   // filetypes allowed by Content-Type.  Empty array means no restrictions
				maxfiles: config.limit,
				maxfilesize: config.max_file_size,    // max file size in MBs
				queuefiles: 5,
				dragOver: function(e) {
					$this.addClass("dragOver");
				},
				dragLeave: function(e) {
					$this.removeClass("dragOver");
				},
				docOver: function(e) {
					$this.addClass("docOver");
				},
				docLeave: function(e) {
					$this.removeClass("docOver");
				},
				drop: function(){
					$(".MediaUploader.docOver").removeClass("docOver").removeClass("dragOver");
				},
				onSelect: _addFile,
				uploadStarted: function(i, file, len){
					//loading++;
					//$this.addClass("uploadingFiles");
					//$this.find(".statusBar").addClass("loadingFiles");
					//$this.find(".statusBarText").html((loaded) ? __('files_loaded')+": "+loaded+ " | "+__('files_loading')+": "+loading : __('files_loading')+": "+loading);
					//$btnCancelAll.removeClass("cancelDisabled");
					_refreshStatus();
				},
				uploadFinished: function(i, file, response, time) {
					var ID=_randomCode();
					eval(unescape(response));
					loading--;
					if(typeof($file)!== "undefined"){
						var $item=$("#UploadiQueueItem"+i);
						$item.createAppend(
							'div',{'class':'data'},[
								'div',{'class':'input text'},[
									'label',{'for':alias+inputId+'name'},__("file-name"),
									'input',{'class':'name','type':'text','value':$file.data.name,'name':'data['+alias+']['+inputId+'][name]','id':alias+inputId+'name'},[],
								],
								'div',{'class':'input text'},[
									'label',{'for':alias+inputId+'alt'},__("img-alt-attr"),
									'input',{'class':'name','type':'text','name':'data['+alias+']['+inputId+'][alt]','id':alias+inputId+'alt'},[],
								],
								'div',{'class':'input textarea'},[
									'label',{'for':alias+inputId+'description'},__("file-description"),
									'textarea',{'class':'description','name':'data['+alias+']['+inputId+'][description]','id':alias+inputId+'description'},[],
								],
								'input',{'class':'extension hidden','value':$file.data.extension,'name':'data['+alias+']['+inputId+'][extension]'},[],
								'input',{'class':'size hidden','value':$file.data.size,'name':'data['+alias+']['+inputId+'][size]'},[],
								'input',{'class':'temp hidden','value':$file.data.temp,'name':'data['+alias+']['+inputId+'][temp]'},[],
								'input',{'class':'mime hidden','value':$file.data.mime,'name':'data['+alias+']['+inputId+'][mime]'},[],
								'input',{'class':'ID hidden','value':ID,'name':'data['+alias+']['+inputId+'][tempId]'},[],
								'button',{'class':'btn button_h aceptEdit','onclick':'return false;'},__("acept"),
							]
						);
						$item.attr("id","UploadiQueueItem"+ID);
						$item.data("inputId",inputId);

						$item.find(".deleteItem").click(function(){
							$("#UploadiQueueItem"+ID).animate(_Animation.hidde.properties,_Animation.hidde.options,function(){$(this).remove()});
							filesInQueue.splice($item.data("inputId"),1);
							loaded--;
							_refreshStatus();
						});

						$item.find(".editItem").click(function(){
							$item.addClass("editingItem");
						});

						$item.find(".aceptEdit").click(function(){
							$(this).closest(".editingItem").removeClass("editingItem");
						});

						$item.find(".UploadiProgressBar").animate({'width':"100%"},500,function(){
							$("#UploadiQueueItem"+ID).addClass("completed").removeClass("uploading");
						});
						$item.find(".percentage").html("100%");
						inputId++;
						loaded++;
						_refreshStatus();

					}else{
						notification(file.name+": "+__("demasiado-grande-para-ser-procesado"),"error");
						$("#UploadiQueueItem"+i).animate(_Animation.hidde.properties,_Animation.hidde.options,function(){$("#UploadiQueueItem"+i).remove()});
					}
				},
				progressUpdated: function(i, file, progress) {
					$("#UploadiQueueItem"+i).find(".UploadiProgressBar").animate({'width':progress+"%"},500);
					$("#UploadiQueueItem"+i).find(".percentage").html(progress+"%");
				},
				globalProgressUpdated: function(progress) {
					// progress for all the files uploaded on the current instance (percentage)
					// ex: $('#progress div').width(progress+"%");
					//console.log(progress);
				},
				speedUpdated: function(i, file, speed) {
					// speed in kb/s
					//console.log(i+" - "+speed);
					$("#UploadiQueueItem"+i).find(".speed").html(speed.toFixed(2)+" kb/s");
				},
				afterAll: function() {
					// runs after all files have bee
					_refreshStatus();
				}
			});

			var _mime_types={
				'application/msword':'doc',

			};

		});
		function _randomCode(){
			var randomstring = '';
			var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZ";
			var string_length = 6;
			for (var i=0; i<string_length; i++) {
					var rnum = Math.floor(Math.random() * chars.length);
					randomstring += chars.substring(rnum,rnum+1);
			}
			return randomstring;
		}
		function _jsonToArray(obj){
				var a=[];
				$.each(obj,function(i,v){
					a.push(v);
				})
				return a;
			}
	}
})(jQuery);

window['UploadDefaultSkin']={
	'Multiple':{
		'layout':[
			'div',{'class':'UploadToolbar'},[
				'span',{'class':'label'},[],
				'button',{'class':'btn ChooseFiles btn_primary'},[
					'span',{'class':'btnIcon icon-cloud-upload'},[],
					'span',{'class':'btnText'},__('choose_files'),
				],
				'button',{'class':' btn UploadHelp'},[
					'span',{'class':'btnIcon'},[],
				],
				'button',{'class':'btn btn_danger CancelAllUploads cancelDisabled','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-remove'},[],
					'span',{'class':'btnText'},__('cancel_uploads'),
				],
				'button',{'class':'btn btn_danger DeleteAllUploads deleteDisabled','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-trash'},[],
					'span',{'class':'btnText'},__('delete_uploads'),
				],
			],
			'div',{'class':'notifications'},[],
			'ul',{'class':'UploadiQueue sortable clearfast'},[],// Aqui dentro va el template de cada archivo

			'div',{'class':'statusBar'},[
				'span',{'class':'statusBarText'},[]
			],
			'div',{'class':'hidden deleted'},[]
		],
		'item':[
			'li',{'class':'UploadiQueueItem'},[
				'span',{'class':'moveOrder'},[],
				'span',{'class':'loadingIndicator'},[
					'img',{'class':'thumb'},[]
				],
				'span',{'class':'nameEllipsis'},[
					'span',{'class':'fileName'},[],
				],
				'span',{'class':'fileSize'},[],
				'span',{'class':'uploadingInfo'},[
					'span',{'class':'percentage'},[],
					'span',{'class':'speed'},[],
				],
				'span',{'class':'UploadiProgress'},[
					'span',{'class':'UploadiProgressBar'},[]
				],
				'button',{'class':'btn cancelItem btn_danger','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-remove'},[],
				],
				'button',{'class':'btn deleteItem btn_danger','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-trash'},[],
				],
				'button',{'class':'btn editItem btn_primary','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-pencil'},[],
				],
			]
		],
		'animation':{
			'appear':{
				'properties':{opacity:1.0},
				'options':{duration:500}
			},
			'hidde':{
				'properties':{opacity:0.0,height:0},
				'options':{duration:500,complete:function(){$(this).remove()}}
			}
		}
	},
	'Single':{
		'layout':[
			'div',{'class':'marco'},[],
			'span',{'class':'label'},[],
			'button',{'class':'btn ChooseFiles btn_primary ','onclick':'return false;'},[
				'span',{'class':'btnIcon icon-cloud-upload'},[],
				'span',{'class':'btnText'},__('choose_files'),
			],
			'button',{'class':' btn UploadHelp'},[
				'span',{'class':'btnIcon'},[],
			],
			'div',{'class':'UploadiQueue clearfast'},[],// Aqui dentro va el template de cada archivo
			'div',{'class':'notifications'},[],
			'div',{'class':'hidden deleted'},[]
		],
		'item':[
			'div',{'class':'UploadiQueueItem'},[
				'span',{'class':'loadingIndicator'},[
					'img',{'class':'thumb'},[]
				],
				'span',{'class':'nameEllipsis'},[
					'span',{'class':'fileName'},[],
				],
				'button',{'class':'btn cancelItem btn_danger','onclick':'return false;'},[
					'span',{'class':'btnIcon icon-remove'},[],
				],
				'span',{'class':'UploadiProgress'},[
					'span',{'class':'UploadiProgressBar'},[],
					'span',{'class':'fileSize'},[],
					'span',{'class':'uploadingInfo'},[
						'span',{'class':'percentage'},[],
						'span',{'class':'speed'},[],
					],
				],
				'div',{'class':'buttons'},[
					'button',{'class':'btn deleteItem btn_danger','onclick':'return false;'},[
						'span',{'class':'btnIcon icon-trash'},[],
						'span',{'class':'text'},__("delete"),
					],
					'button',{'class':'btn editItem btn_primary','onclick':'return false;'},[
						'span',{'class':'btnIcon icon-pencil'},[],
						'span',{'class':'text'},__("edit"),
					],
				],
			]
		],
		'animation':{
			'appear':{
				'properties':{opacity:1.0},
				'options':{duration:0}
			},
			'hidde':{
				'properties':{opacity:0.0},
				'options':{duration:0,complete:function(){$(this).remove()}}
			}
		}
	}
}