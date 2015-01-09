
;(function($) {

	jQuery.event.props.push("dataTransfer");

	var default_opts = {
			dropElement: '',
			url: '',
			refresh: 1000,
			paramname: 'userfile',
			allowedfiletypes:[],
			maxfiles: 25,           // Ignored if queuefiles is set > 0
			maxfilesize: 1,         // MB file size limit
			queuefiles: 0,          // Max files before queueing (for large volume uploads)
			queuewait: 200,         // Queue wait time if full
			data: {},
			headers: {},
			drop: empty,
			change: empty,
			dragStart: empty,
			dragEnter: empty,
			dragOver: empty,
			dragLeave: empty,
			docEnter: empty,
			docOver: empty,
			docLeave: empty,
			beforeEach: empty,
			afterAll: empty,
			rename: empty,
			error: function(err, file, i, status) {
				alert(err);
			},
			uploadStarted: empty,
			uploadFinished: empty,
			progressUpdated: empty,
			globalProgressUpdated: empty,
			speedUpdated: empty
		},
		errors = ["BrowserNotSupported", "TooManyFiles", "FileTooLarge", "FileTypeNotAllowed", "NotFound", "NotReadable", "AbortError", "ReadError"],
		doc_leave_timer, stop_loop = false;

	var UploadFiles = function(element , options){
		var opts = $.extend({}, default_opts, options),
				global_progress = [];


		var files_count = 0,files= [];
		// Define queues to manage upload process
		var workQueue = 0;
		var processingQueue = 0;
		var doneQueue = [];
		var filesDone = 0, filesRejected = 0;
		var fileIndex = 0;
		var xhrs= [];

		this.methods={
			abort: _abort
		};

		if(typeof opts.dropElement == 'string' || opts.dropElement instanceof String){
			opts.dropElement=$(opts.dropElement);
		}
		opts.dropElement.on('drop', drop).on('dragstart', opts.dragStart).on('dragenter', dragEnter).on('dragover', dragOver).on('dragleave', dragLeave);
		$(document).on('drop', docDrop).on('dragenter', docEnter).on('dragover', docOver).on('dragleave', docLeave);

		$(element).change(function(e) {
			opts.change(e.target.files, e);
			if(!select(e.target.files)) return false;
			upload(e.target.files);
		});

		function drop(e) {
			if( opts.drop.call(this, e.dataTransfer.files,e) === false ) return false;
			if (e.dataTransfer.files === null || e.dataTransfer.files === undefined || e.dataTransfer.files.length === 0) {
				opts.error(errors[0]);
				return false;
			}
			if(!select(e.dataTransfer.files)) return false;
			upload();

			e.preventDefault();
			return false;
		}
		function select(selectedFiles){
			if (!selectedFiles) {
				opts.error(errors[0]);
				return false;
			}
			var full=false;
			var allowed,done=false;;
			for(var i=0; i<selectedFiles.length; i++){
				if(opts.onSelect(files_count,selectedFiles[i])){
					files_count++;
					done=true;
					files.push(selectedFiles[i]);
				}

			}
			return done; // regresa true si se agrega al menos un archivo

		}

		function _abort(index){
			if(typeof index === "undefined"){ // abortar todos.
				for(var i=0; i<xhrs.length; i++){
					xhrs[i].abort();
				}
				files_count = 0,files= [];
				workQueue = 0;
				processingQueue = 0;
				doneQueue = [];
				filesDone = 0, filesRejected = 0;
				fileIndex = 0;
			}else if(xhrs[index]){
				xhrs[index].abort();
				processingQueue--;
				files_count--;
			}else if(files[index]){
				files.splice(index,1);
				files_count--;
			}
		}

		function upload() {// Respond to an upload
			stop_loop = false;
			workQueue=files_count - filesRejected;

			// Helper function to enable pause of processing to wait
			// for in process queue to complete
			var pause = function(timeout) {
				setTimeout(process, timeout);
				return;
			};

			// Process an upload, recursive
			var process = function() {
				//console.log(fileIndex);

				if (stop_loop) {
					return false;
				}

				// Check to see if are in queue mode
				if (opts.queuefiles > 0 && processingQueue >= opts.queuefiles) {
					return pause(opts.queuewait);
				} else {
					workQueue--;
					processingQueue++;
				}

				try {
					if (beforeEach(files[fileIndex]) !== false) {
						if (fileIndex === files_count) {
							return;
						}
						var reader = new FileReader();

						reader.index = fileIndex;


						reader.onerror = function(e) {
								switch(e.target.error.code) {
										case e.target.error.NOT_FOUND_ERR:
												opts.error(errors[4]);
												return false;
										case e.target.error.NOT_READABLE_ERR:
												opts.error(errors[5]);
												return false;
										case e.target.error.ABORT_ERR:
												opts.error(errors[6]);
												return false;
										default:
												opts.error(errors[7]);
												return false;
								};
						};

						reader.onloadend = !opts.beforeSend ? send : function (e) {
							opts.beforeSend(files[fileIndex], fileIndex, function () { send(e); });
						};

						reader.readAsBinaryString(files[fileIndex]);
						fileIndex++;

					} else {
						filesRejected++;
					}
				} catch (err) {
					processingQueue--;
					opts.error(errors[0]);
					return false;
				}

				// If we still have work to do,
				if (workQueue) {
					process();
				}
			};

			var send = function(e) {

				var fileIndex = ((typeof(e.srcElement) === "undefined") ? e.target : e.srcElement).index;

				// Sometimes the index is not attached to the
				// event object. Find it by size. Hack for sure.
				if (e.target.index === undefined) {
					e.target.index = getIndexBySize(e.total);
				}

				var xhr = new XMLHttpRequest(),
						upload = xhr.upload,
						file = files[e.target.index],
						index = e.target.index,
						start_time = new Date().getTime(),
						boundary = '------multipartformboundary' + (new Date()).getTime(),
						global_progress_index = global_progress.length,
						builder,
						newName = rename(file.name),
						mime = file.type;

				if (opts.withCredentials) {
					xhr.withCredentials = opts.withCredentials;
				}

				if (typeof newName === "string") {
					builder = getBuilder(newName, e.target.result, mime, boundary);
				} else {
					builder = getBuilder(file.name, e.target.result, mime, boundary);
				}

				upload.index = index;
				upload.file = file;
				upload.downloadStartTime = start_time;
				upload.currentStart = start_time;
				upload.currentProgress = 0;
				upload.global_progress_index = global_progress_index;
				upload.startData = 0;
				upload.addEventListener("progress", progress, false);
				//xhr.addEventListener("progress", progress, false);

				// Allow url to be a method
				if (jQuery.isFunction(opts.url)) {
						xhr.open("POST", opts.url(), true);
				} else {
					xhr.open("POST", opts.url, true);
				}

				xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + boundary);

				// Add headers
				$.each(opts.headers, function(k, v) {
					xhr.setRequestHeader(k, v);
				});

				xhr.sendAsBinary(builder);

				global_progress[global_progress_index] = 0;
				globalProgress();

				opts.uploadStarted(index, file, files_count);

				xhr.onload = function() {
					var serverResponse = null;

					if (xhr.responseText) {
						try {
							serverResponse = jQuery.parseJSON(xhr.responseText);
						}
						catch (e) {
							serverResponse = xhr.responseText;
						}
					}

					var now = new Date().getTime(),
							timeDiff = now - start_time,
							result = opts.uploadFinished(index, file, serverResponse, timeDiff, xhr);

					filesDone++;
					processingQueue--;

					// Add to donequeue
					doneQueue.push(fileIndex);


					// Make sure the global progress is updated
					global_progress[global_progress_index] = 100;
					globalProgress();

					if (filesDone === (files_count - filesRejected)) {
						afterAll();
					}
					if (result === false) {
						stop_loop = true;
					}


					// Pass any errors to the error option
					if (xhr.status < 200 || xhr.status > 299) {
						opts.error(xhr.statusText, file, fileIndex, xhr.status);
					}
				};
				xhrs.push(xhr);
			};

			// Initiate the processing loop
			process();
		}
		function getBuilder(filename, filedata, mime, boundary) {
			var dashdash = '--',
					crlf = '\r\n',
					builder = '';

			if (opts.data) {
				var params = $.param(opts.data).replace(/\+/g, '%20').split(/&/);

				$.each(params, function() {
					var pair = this.split("=", 2),
							name = decodeURIComponent(pair[0]),
							val  = decodeURIComponent(pair[1]);

					builder += dashdash;
					builder += boundary;
					builder += crlf;
					builder += 'Content-Disposition: form-data; name="' + name + '"';
					builder += crlf;
					builder += crlf;
					builder += val;
					builder += crlf;
				});
			}

			builder += dashdash;
			builder += boundary;
			builder += crlf;
			builder += 'Content-Disposition: form-data; name="' + opts.paramname + '"';
			builder += '; filename="' + filename + '"';
			builder += crlf;

			builder += 'Content-Type: ' + mime;
			builder += crlf;
			builder += crlf;

			builder += filedata;
			builder += crlf;

			builder += dashdash;
			builder += boundary;
			builder += dashdash;
			builder += crlf;
			return builder;
		}
		function progress(e) {
			if (e.lengthComputable) {
				var percentage = Math.round((e.loaded * 100) / e.total);
				//console.log(percentage);
				if (this.currentProgress !== percentage) {

					this.currentProgress = percentage;
					opts.progressUpdated(this.index, this.file, this.currentProgress);

					global_progress[this.global_progress_index] = this.currentProgress;
					globalProgress();

					var elapsed = new Date().getTime();
					var diffTime = elapsed - this.currentStart;
					if (diffTime >= opts.refresh) {
						var diffData = e.loaded - this.startData;
						var speed = diffData / diffTime; // B per second
						opts.speedUpdated(this.index, this.file, speed / 1024);
						this.startData = e.loaded;
						this.currentStart = elapsed;
					}
				}
			}
		}
		function globalProgress() {
			if (global_progress.length === 0) {
				return;
			}

			var total = 0, index;
			for (index in global_progress) {
				if(global_progress.hasOwnProperty(index)) {
					total = total + global_progress[index];
				}
			}

			opts.globalProgressUpdated(Math.round(total / global_progress.length));
		}
		function getIndexBySize(size) {
			for (var i = 0; i < files_count; i++) {
				if (files[i].size === size) {
					return i;
				}
			}

			return undefined;
		}
		function rename (name) {
			return opts.rename(name);
		}
		function beforeEach (file) {
			return opts.beforeEach(file);
		}
		function afterAll() {
			files_count = 0,files= [];
			workQueue = 0;
			processingQueue = 0;
			doneQueue = [];
			filesDone = 0, filesRejected = 0;
			fileIndex = 0;
			return opts.afterAll();
		}
		function dragEnter(e) {
			clearTimeout(doc_leave_timer);
			e.preventDefault();
			opts.dragEnter.call(this, e);
		}
		function dragOver(e) {
			clearTimeout(doc_leave_timer);
			e.preventDefault();
			opts.docOver.call(this, e);
			opts.dragOver.call(this, e);
		}
		function dragLeave (e) {
			clearTimeout(doc_leave_timer);
			opts.dragLeave.call(this, e);
			e.stopPropagation();
		}
		function docDrop (e) {
			e.preventDefault();
			opts.docLeave.call(this, e);
			return false;
		}
		function docEnter(e) {
			clearTimeout(doc_leave_timer);
			e.preventDefault();
			opts.docEnter.call(this, e);
			return false;
		}
		function docOver(e) {
			clearTimeout(doc_leave_timer);
			e.preventDefault();
			opts.docOver.call(this, e);
			return false;
		}
		function docLeave(e) {
			doc_leave_timer = setTimeout((function(_this) {
				return function() {
					opts.docLeave.call(_this, e);
				};
			})(this), 200);
		};
	};


	$.fn.UploadFiles = function(opc) {
			if(typeof opc === "string"){
				var methods = $(this).data('UploadFiles').methods;
				if(methods[opc]){
					return methods[opc].apply(this,Array.prototype.slice.call(arguments,1));
				}else{
					$.error("El metodo "+opc+" no existe en UploadFiles");
				}
			}
			if($(this).data('Uploadfiles')) return $(this);
			$(this).data("UploadFiles",new UploadFiles($(this),opc));
			return $(this);
	};

	function empty() {}

	try {
		if (XMLHttpRequest.prototype.sendAsBinary) {
				return;
		}
		XMLHttpRequest.prototype.sendAsBinary = function(datastr) {
			function byteValue(x) {
				return x.charCodeAt(0) & 0xff;
			}
			var ords = Array.prototype.map.call(datastr, byteValue);
			var ui8a = new Uint8Array(ords);
			this.send(ui8a.buffer);
		};
	} catch (e) {}

})(jQuery);