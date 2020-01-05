var objPrimary = (function(){
    
    // object validate
    var validateF = function(){
        
    }; // END OBJECT VALIDATE
    
    // objects event
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            
            // evento cargar imagenes alcaldia
            this.setEventUploadImg("filImagenALC");
            // evento cargar imagenes gobernacion
            this.setEventUploadImg("filImagenGOB");
            // evento cargar imagenes concejo
            this.setEventUploadImg("filImagenCOO");
            // evento cargar imagenes asamblea
            this.setEventUploadImg("filImagenASA");
            // evento cargar imagenes jal
            this.setEventUploadImg("filImagenJAL");
            // event dropify
            this.setInitDropify("filImagenALC");
            this.setInitDropify("filImagenGOB");
            this.setInitDropify("filImagenCOO");
            this.setInitDropify("filImagenASA");
            this.setInitDropify("filImagenJAL");
            
            if($("#hdnArrImg").val() != "null"){
                // set event in array
                var jsonArr = JSON.parse($("#hdnArrImg").val());
                var nr = jsonArr.length;
                for(var i=0; i<nr ;i++){
                    var corp = jsonArr[i].corporation;
                    var indImg = jsonArr[i].id_image_task;
                    
                    // evento cargar imagenes
                    this.setEventUploadImg("filImagen"+corp+indImg);
                    // event dropify
                    this.setInitDropify("filImagen"+corp+indImg);
                    // disabled
                    $("#filImagen"+corp+indImg).attr("disabled","disabled");
                }
            }
        
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            
            // init tabs
            this.setIniTabs();
            
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setIniTabs = function(){
            $('#tabs').tabs();
        }; // END FUNCTION
        
        /**
         * 
         * @returns {undefined}
         */
        this.setInitDropify = function(id){
            console.log("drop->"+id);
            var drEvent = $('#'+id).dropify({
                messages: {
                    'default': 'Toca para cargar imagen',
                    'replace': 'Puede quitar la imagen si lo desea',
                    'remove':  'Quitar',
                    'error':   'Ooops, something wrong happended.'
                },
                error: {
                    'fileSize': 'The file size is too big ({{ value }} max).',
                    'minWidth': 'The image width is too small ({{ value }}}px min).',
                    'maxWidth': 'The image width is too big ({{ value }}}px max).',
                    'minHeight': 'The image height is too small ({{ value }}}px min).',
                    'maxHeight': 'The image height is too big ({{ value }}px max).',
                    'imageFormat': 'Extensiones permitidas ({{ value }}).'
                }
            });
            drEvent.on('dropify.afterClear', function(event, element){
                if(!$(this).next().hasClass("no-quitar")){
                    $(event.currentTarget).parent().parent().remove();
                    Materialize.toast('Imagen borrada!', 2000);
                    
                    // enviamos la paeticion a la base de datos
                    (new ajaxF()).deleteImageTask($(this).attr("id-image"));
                }else{
                    $(this).next().removeClass("no-quitar");
                }
            });
        }; // END FUNCTION
        
        /**
        * 
        * @returns {undefined}
        */
        this.setEventUploadImg = function(id){
            $('#'+id).fileupload({
                formData:{
                    _token:_token,
                    nameField:id,
                    _token_update:$("#_token_update").val()
                },
                url:public_path+$.sy_pathUrl("collaborators/tasks/upload/filegeneric"),
                type:'POST',
                
            });
            $('#'+id).fileupload().bind('fileuploadsend', function (e, data) {
                $('#'+id).attr({"disabled":"disabled"});
                $('#'+id).next().hide();

                Materialize.toast('Inicio carga de imagen', 1000);
            });
            $('#'+id).fileupload().bind('fileuploadfail', function (e, data) {
                swal("Error! Al cargar la imagen", "Intente de nuevo cargar la imagen", "warning");
                $('#'+id).next().addClass("no-quitar");
                $('#'+id).next().trigger("click")
            });
            $('#'+id).fileupload().bind('fileuploaddone', function (e, data) {
                var json = data._response.result;
                    
				if(json.error == false){
					Materialize.toast('Imagen cargada con exito! :)', 2000);
					var idObj = $('#'+id).attr("id");
					$('#'+id).attr({"id-image":json.msg.rows});
					$('#'+id).attr({"disabled":"disabled"});
					$('#'+id).next().show();
					$('#'+id).removeAttr("name");
					//$('#'+id).attr({"name":idObj+""+json.msg.rows});
					
					$('#'+id).next().click(function(){
						$(event.currentTarget).parent().parent().remove();
						Materialize.toast('Imagen borrada!', 2000);
						// enviamos la paeticion a la base de datos
						(new ajaxF()).deleteImageTask(json.msg.rows);
					});
					
					$('#'+id).attr({"id":idObj+""+json.msg.rows});
					
					// set dropify
					(new eventF()).setInitDropify(idObj+""+json.msg.rows);

					(new processF()).addDivImageDropify($(this), idObj);
				}else{
					swal("Error! Al cargar la imagen", "Intente de nuevo cargar la imagen - Err2", "warning");
					location.reload();
				}
            });
            
            $('#'+id).fileupload().bind('fileuploadprogressall', function (e, data) {
                var objTexto = $('#'+id).next().next();
                // dropify-infos
                objTexto = $(objTexto).children()[1];
                // dropify-infos-inner
                objTexto = $(objTexto).children()[0];
                // dropify-filename
                objTexto = $(objTexto).children()[0];
                // dropify-filename-inner
                objTexto = $(objTexto).children()[1];
                
                var htmlText = "";
                if(!document.getElementById("prel-"+id)){
                    htmlText = $(objTexto).html();
                }
                
                var percentComplete = (parseFloat(data.loaded / data.total)*100).toFixed(2);
                $(objTexto).html("<h4 id='prel-"+id+"'>"+percentComplete+"%</h4>");
                
            });
        }; // END function
    
        
        
    }; // END OBJECT EVENTS
    
    // objects ajax
    var ajaxF = function(){
        
        /*
         * 
         * @param {type} idtask
         * @returns {undefined}
         */
        this.deleteImageTask = function(idtask){
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('collaborators/tasks/delete/'+idtask),
                preload:false,
                alertError:false,
                success: function(params,response){}
            });
        }; // END function
        
    
    }; // END OBJECT AJAX
    
    // object process
    var processF = function(){
        
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        this.addDivImageDropify = function(obj, id){
            var str = "";
                str += '<div class="col s12 m12">';
                str += '    <input type="file" id="'+id+'" name="'+id+'" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>'
                str += '</div>';
            $(obj).parent().parent().parent().prepend(str);
            
            // init dropify
            (new eventF()).setInitDropify(id);
            // evento cargar imagenes
            (new eventF()).setEventUploadImg(id);
        }; // END FUNCTION
        
    }; // END OBJECT PROCESS
    
    // main
    var main = function(){
        this.__construct = function(){
            
            // suscrbie eventes
            (new eventF()).suscribeEvents();
            // trigger events
            (new eventF()).triggerEvents();
        }; // END function
        
    }; // END OBJECT
    
    return {
        main:main
    };
})();

// init javascript
$(function(){
    try{(new objPrimary.main()).__construct();}catch(ex){$.sys_error_handler(ex);}
});
