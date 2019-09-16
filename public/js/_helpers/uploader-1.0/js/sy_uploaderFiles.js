/****/
(function ($){
    $.fn.uploaderFiles = function(args){
        args = jQuery.extend({
            url:$.sy_pathUrl("sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric"),
            sizeMax:10,//megas,
            autoUpload:true,
            arrExt:['gif','png','jpg','jpeg','docx','doc','ppt','pptx','xls','xlsx','pdf'],
            $_transferStart:function(){console.log('start upload');},
            $_updateProgress:function(){console.log('update progress')},
            $_transferFailed:function(){console.log('upload failed')},
            $_transferCanceled:function(){console.log('upload canceled')},
            $_transferComplete:function(){console.log('upload completed')},
            $_transferAfterCompleteReady:function(){console.log('upload completed sin errores')},
            $_transferAfterCompleteError:function(){console.log('upload completed con errores')},
            data:{},
            autoHide: false,
            hideTimeout:false,
            
            _obj:null
        }, args);
        
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        _triggerUpload = function(obj){
            var name = _getName(obj);
            var formData = new FormData();
            formData.append("_token", _token);
            formData.append("nameField", $(obj).attr('id'));
            formData.append("file", name);
            // Loop through each of the selected files.
            for (var i = 0; i < obj[0].files.length; i++) {
              // Check the file type.
              //if (!file.type.match('image.*')) {continue;}
              // Add the file to the request.
              formData.append(obj[0].name+'[]', obj[0].files[i]); 
            }
            /*append data extra**/
            formData = _appendDataExtra(formData);
            
            //args.objUpload = obj;
            
            /**object xhr*/
            _sendXhr(formData);
        }
        
        /**
         * 
         * @param {type} formData
         * @returns {undefined}
         */
        _sendXhr = function(formData){
            var xhr = new XMLHttpRequest();
            xhr.open("POST", public_path+''+args.url, true);
            
            if(document.addEventListener){
                xhr.upload.addEventListener("loadstart", _transferStart, false);
                xhr.upload.addEventListener("progress", _updateProgress, false);
                xhr.addEventListener("load", _transferComplete, false);
                xhr.upload.addEventListener("error", _transferFailed, false);
                xhr.upload.addEventListener("abort", _transferCanceled, false);
            }else{
                xhr.upload.attachEvent("onLoadstart", _transferStart);
                xhr.upload.attachEvent("onProgress", _updateProgress);
                xhr.attachEvent("onLoad", _transferComplete);
                xhr.upload.attachEvent("onError", _transferFailed);
                xhr.upload.attachEvent("onAbort", _transferCanceled);
            }
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            //xhr.setRequestHeader("X-File-Name", encodeURIComponent(name));
            //xhr.setRequestHeader("Content-Type", "application/octet-stream");
            xhr.send(formData);
        }
        
        /**
         * 
         * @param {type} evt
         * @returns {undefined}
         */
        _updateProgress = function (evt) {
            if (evt.lengthComputable) {
                var percentComplete = (parseFloat(evt.loaded / evt.total)*100).toFixed(2);
                /**remove preload*/
                $('#iPreload-'+$(_getObj()).prop('id')).html(percentComplete+'%');
                
            } else {
                // Unable to compute progress information since the total size is unknown
            }
        }

        /**
         * 
         * @param {type} evt
         * @returns {undefined}
         */
        _transferComplete = function(evt) {
            /**remove preload*/
            $('#imgPreload-'+$(_getObj()).prop('id')).remove();
            /**/
            args.$_transferComplete(evt,evt.target.responseText);
            if (evt.target.readyState==4 && evt.target.status==200){
                // File(s) uploaded
                args.$_transferAfterCompleteReady(evt,evt.target.responseText);
                _customMsgStatus(evt.target.responseText);
            } else {
                args.$_transferAfterCompleteError(evt,evt.target.responseText);
                var stError = _getMsjError(evt);
                _addDivStatus("orange darken-3","Error al cargar el archivo",stError);
                
            }
            /**agregamos icono notificacion*/
            
        }
        
        /**
         * 
         * @param {type} msg
         * @returns {Boolean}
         */
        _customMsgStatus = function(msg){
            if (msg == undefined) {
                alert('Error: Ocurrio un error en el response del request');
                return false;
            }else{
                msg = JSON.parse(msg);
                if(msg.msg!=null){
                    if(msg.error == true) {
                        _addDivStatus(
                            'red',
                            (msg.msg.msgResponseFirst!=undefined?msg.msg.msgResponseFirst:"Error!"),
                            $.sy_configureDivDetalleStatus(msg.msg.msgResponseDetails)
                        );
                    }else{
                        if (msg.error == false) {
                            var strM = (msg.msg.cfiles==1?
                                            (msg.msg.msgResponseFirst!=undefined?
                                            msg.msg.msgResponseFirst
                                            :"Yeah! Tu tranquilo el archivo cargo correctamente"):
                                            (msg.msg.msgResponseFirst!=undefined?
                                            msg.msg.msgResponseFirst
                                            :"Yeah! Los archivos se cargar&oacute;n sin problemas!")
                                        );
                            _addDivStatus('teal',strM,"");
                        }else{
                            if(msg.error == null) {
                                _addDivStatus(
                                        'orange darken-3',
                                        "Uyyy! Algo no finalizo muy bien",
                                        $.sy_configureDivDetalleStatus(msg.msg.msgResponseDetails)
                                    );
                            }
                        }
                    }
                }
            }
        }
        
        /**
         * 
         * @param {type} evt
         * @returns {undefined}
         */
        _transferStart = function(evt){
            /***/
            args.$_transferStart(evt);
            /**set image de preload*/
            $('#imgPreload-'+$(_getObj()).prop('id')).remove();
            if($(_getObj()).next().children().children().length>0){
                $(_getObj()).next().children().children().append('<span id="imgPreload-'+$(_getObj()).prop('id')+'" >&nbsp;<i class="fa fa-cog fa-spin fa-lg" style="vertical-align: baseline;"></i>&nbsp;<i id="iPreload-'+$(_getObj()).attr('id')+'">0%</i></span>');
            }else{
                $(_getObj()).after('<span id="imgPreload-'+$(_getObj()).prop('id')+'" >&nbsp;<i class="fa fa-cog fa-spin fa-lg"></i>&nbsp;<i id="iPreload-'+$(_getObj()).prop('id')+'">0%</i></span>');
            }
        }

        /**
         * 
         * @param {type} evt
         * @returns {undefined}
         */
        _transferFailed = function(evt) {
            /**remove preload*/
            $('#imgPreload-'+$(_getObj()).attr('id')).remove();
            args.$_transferFailed(evt);
        }

        /**
         * 
         * @param {type} evt
         * @returns {undefined}
         */
        _transferCanceled = function(evt) {
            /**remove preload*/
            $('#imgPreload-'+$(_getObj()).attr('id')).remove();
            args.$_transferCanceled(evt);
        }

        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        _setEventObj = function(obj){
            $(obj).change(function(){
                /**validamos*/
                _setObj(this);
                var obj2 = [this];
                args.objUpload = this;
                
                if(_validateUpload(obj2)){
                    if(args.autoUpload){
                        /*trigger de upload file*/
                        _triggerUpload(obj2);
                    }else{
                        /**uploade despues*/
                    }
                }
            });
        }
        
        /**
         * 
         * @author john jairo cortes garcia <john.cortes@syslab.so>
         * @param {type} obj
         * @returns {Boolean}
         */
        _validateUpload = function(obj){
            var boolValidate = true;
            for (var i = 0; i < obj[0].files.length; i++) {
                /**validamos el size*/
                if(!_validateSize(obj[0].files[i])){
                    boolValidate = false;
                }
                /**validamos la extension*/
                if(!_validateExtension(obj[0].files[i])){
                    boolValidate = false;
                }
                
            }
            return boolValidate;
        };
        
        /**
         * 
         * @author john jairo cortes garcia <john.cortes@syslab.so>
         * @param {type} objFile
         * @returns {Boolean}
         */
        _validateSize = function(objFile){
            if(parseFloat(((objFile.size/1024))/1024) > parseFloat(args.sizeMax)){
                _addDivStatus(
                        "orange darken-3",
                        "Imposible cargar el archivo",
                        'Archivo('+objFile.name+')('+(((objFile.size/1024))/1024).toFixed(2)+'Mb) es mas grande de lo permitido: Max='+args.sizeMax+'Mb'
                    );
                return false;
            }
            return true;
        };
        
        /**
         * 
         * @author john jairo cortes garcia <john.cortes@syslab.so>
         * @param {type} objFile
         * @returns {Boolean}
         */
        _validateExtension = function(objFile){
            var ext = objFile.name.split('.').pop().toLowerCase();
            if($.inArray(ext, args.arrExt) == -1) {
                _addDivStatus(
                        "orange darken-3",
                        "Imposible cargar el archivo",
                        'Extension permitidas: ['+args.arrExt+']'
                    );
                return false;
            }
            return true;
        }

        /**
         * 
         * @param {type} obj
         * @returns {unresolved}
         */
        _getName = function(obj){
            // get input value and remove path to normalize
            return $(obj).val().replace(/.*(\/|\\)/, "");
        }; 
        
        /**
         * 
         * @param {type} evt
         * @returns {String}
         */
        _getMsjError = function(evt){
            switch(parseInt(evt.target.readyState)){
                case 0: return "El request nunca se inicio";
                case 1: return "No se logro enviar los datos";
                case 2: return "Ya se conecto y se logro recibir respuesta";
                case 3: return "Se logro procesar el request";
                case 4:
                    switch(parseInt(evt.target.status)){
                        case 200: return "El proceso finalizo correctamente";
                        case 404: return "El destino de la peticion no existe";
                        case 500: return "Ocurrio un error del lado del servidor";
                    }
            }
            return "Sin respuesta";
        }
        
        /**
         * 
         * @param {type} cla
         * @param {type} msjP
         * @param {type} msjS
         */
        _addDivStatus = function(cla,msjP,msjS){
            $('#divStateUpload-'+$(_getObj()).prop('id')).remove();
            var stE = '';
            stE += "<div id='divStateUpload-"+$(_getObj()).prop('id')+"' class='card-panel "+cla+"'>";
            stE += "    <h6 class='text-darken-2 white-text'>";
            //stE += "        <a href='javascript:void(0)' class='close' data-dismiss='"+cla+"'>&times;</a>";
            stE += "        <strong>¡Atenci&oacute;n!</strong> <span class='text-darken-2 white-text'>"+msjP+"</span>";
            if(msjS!=undefined && msjS!=null && msjS!=false){
                stE += "    <br/><a href='javascript:void(0)' onclick='$(\"#divMsgPrincipal_detalles\").toggle();' class='text-darken-2 white-text'>((( ver detalles )))</a>";
            }
            stE += "    </h6>";
            if(msjS!=undefined && msjS!=null && msjS!=false){
                if(!document.getElementById('divStateUpload_detalles-'+$(_getObj()).prop('id'))){
                    stE += "    <div class='row text-darken-2 white-text' id='divMsgPrincipal_detalles' style='display:none;'>";
                    stE += "        "+msjS;
                    stE += "    </div>";
                }else{
                    $('#divStateUpload_detalles-'+$(_getObj()).prop('id')).append(msjS);
                }
            }
            stE += "</div>";
            /**add*/
            if(!document.getElementById('divStateUpload-'+$(_getObj()).prop('id'))){
                if($(_getObj()).next().length>0){
                    $(_getObj()).next().after(stE); 
                }else{
                    $(_getObj()).after(stE); 
                }
            }else{
                
            }
            setTimeout(function(){
                $('#divStateUpload-'+$(_getObj()).prop('id')).remove();
            },10000);
        };
        
        _setObj = function(obj){
            args._obj = obj;
        };
        
        _getObj = function(){
            return args._obj;
        };
        
        /**
         * agregar datos extra al formdata
         * 
         * @author john jairo cortes garcia <john.cortes@syslab.so>
         * @created_at 05-04-2015
         * @param {type} formData
         * @returns {unresolved}
         */
        _appendDataExtra = function(formData){
            $.each(args.data,function(index,value){
                formData.append(index,value);
            });
            return formData;
        }
        
        /***/
        function main(obj){
            this._setEventObj(obj);
        }
        /* ini uploader**/
        main(this);
    };
   
})(jQuery);