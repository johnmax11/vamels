/****/
jQuery.extend({
    /**
     * funcion par acargar preload
     * 
     * @returns {undefined}
     */
    sy_preload : function(args) {
        /***/
        args = jQuery.extend({
            preload:true,
            target_preload : null,
            texto_preload:""
        }, args);
        if(args.preload){
            // verificamos si es el preload general
            if(args.target_preload == null){
                // cargamos el preload
                $("body").removeClass("loaded");
                $("#divTxtPreloader").html(args.texto_preload);
            }else{
                // pintamos el preload en sitio concreto
            }
        }
    },
    
    /**
     * 
     * @returns {undefined}
     */
    sy_centerModal : function(obj) {
        $(obj).css('display', 'block');
        var $dialog = obj;//$(obj).find(".modal-dialog");
        var offset = ($(window).height() - $dialog.height()) / 2;
        var offset_medio = ($(window).width() - $dialog.width()) / 2;
        // Center modal vertically in window
        $dialog.css("margin-top", offset);
        $dialog.css("margin-top", offset_medio);
    },
    
    /**
     * llama un proceso ajax controlado
     * 
     * @param {type} args
     * @returns {undefined}
     */
    sy_ajaxFrm : function(args){
        /***/
        args = jQuery.extend({
            preload:true,
            messagePreload:null,
            type : $.sy_getTypeHead(args.url),
            dataType : 'json',
            alertError:true,
            target_paint_error:'#divStatusResponse',/*div destino donde se debe pintar el error*/
            cleanForm:true,
            target_preload:null,
            timeoutMessage:null,
            beforeSend:function(){(args.preload?$.sy_preload({preload:args.preload,target_preload:args.target_preload,texto_preload:args.messagePreload}):false)},
            error:function(jqXHR,textStatus,errorThrown){$.sy_errorResponse(jqXHR,args.alertError,args.target_paint_error);},
            success:function(){},
            params:{},
            data:{},
        }, args);
        //removemos div d errrores
        if(args.alertError == true)
            $('#divMsgPrincipal').remove();
        // verificamos token
        if(_token == ''){
            _token = $('#_token').val();
        }
        if(typeof (args.data) == 'object'){
            args.data._token = _token;
        }else{
            if(typeof (args.data) == 'string'){
                args.data += '&_token='+_token;
            }
        }
        
        // verifiy update
        if(document.getElementById('_token_update')){
            if($('#_token_update').val() == '' || $('#_token_update').val() == null || $('#_token_update').val() == undefined){
                $.sy_addDivError(
                    "red",
                    "Inconsistencia en el Token de Actualizaci&oacute;n! Refresque la pagina, Click en <a href='javascript:location.reload();'><i class='fa fa-refresh'></i></a>",
                    $.sy_configureDivDetalleStatus([{type:"error",general:[]}])
                );
                return;
            }else{
                if(typeof (args.data) == 'object'){
                    args.data._token_update = $('#_token_update').val();
                }else{
                    if(typeof (args.data) == 'string'){
                        args.data += '&_token_update='+$('#_token_update').val();
                    }
                }
            }
        }
        
        $.ajax({
            url: public_path+''+args.url,
            type:args.type,
            data: args.data,
            dataType:args.dataType,
            beforeSend:function(){
                args.beforeSend();
                if(args.preload){
                }
            },
            error:function(jqXHR,textStatus,errorThrown){
                if(args.preload){}
                args.error(jqXHR,textStatus,errorThrown);
            },
            success: function(msg){
                // validamos si el preload esta abierto
                if(args.preload){
                    /*cierra el preload*/
                    $.sy_closePreload();
                }
                if($.sy_validateResponse({
                    response:msg,
                    alertError:args.alertError,
                    target_paint_error:args.target_paint_error
                })){
                    eval('var as_f = ('+args.success+')');
                    as_f(args.params,msg);
                    // clear form
                    if(args.cleanForm == true){
                        //$.sy_clean_form();
                    }
                }else{
                    
                }
                
                // verificamos si hay q borrar el mensaje d respuesta
                if(args.timeoutMessage != null){
                    setTimeout(function(){
                        $(args.target_paint_error).html("");
                    },args.timeoutMessage);
                }
            }
        });
    },
    
    /**
     * 
     * @param {type} idobj
     * @returns {jQuery}
     */
    sy_preloadField : function(idobj){
        var html_o = $(idobj).clone();
        var id = $(idobj).attr('id');
        var obj_padre = $(idobj).parent();
        obj_padre.html(
            '<div id="divClone'+id+'" class="input-group">'+
            '</div>'
        );
        $(html_o).appendTo('#divClone'+id);
        $(
            '    <label class="input-group-addon btn">'+
            '        <span class="fa fa-cog fa-spin"></span>'+
            '    </label>'
        ).appendTo('#divClone'+id);
        return html_o;
    },
    
    /**
     * 
     * @param {type} idobj
     * @returns {undefined}
     */
    sy_blankPreloadField : function(idobj){
        var obj_c = $(idobj).clone();
        $(idobj).parent().parent().html('').append(obj_c);
    },
    
    /**
     * determina el tipo de cabezar a usar
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 24-05-2015
     * @param {type} url
     * @returns {String}
     */
    sy_getTypeHead : function(url){
        var arrUrl = url.split("/");
        /**verificamos a cual pertenece*/
        if(jQuery.inArray("create",arrUrl) != -1){
            return "PUT";
        }else{
            if(jQuery.inArray("read",arrUrl) != -1){
                return "GET"
            }else{
                if(jQuery.inArray("update",arrUrl) != -1){
                    return "POST"
                }else{
                    if(jQuery.inArray("delete",arrUrl) != -1){
                        return "DELETE"
                    }else{
                        if(jQuery.inArray("upload",arrUrl) != -1){
                            return "POST"
                        }else{
                            if(jQuery.inArray("download",arrUrl) != -1){
                                return "GET";
                            }else{
                                return "POST";
                            }
                        }
                        
                    }
                }
            }
        }
    },

    /**
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @date 28-03-2015
     * @param {type} ObjErr
     * @returns {undefined}
     */
    sy_errorResponse : function(objErr,showError,target_paint_error) {
        if(showError == false){
            return;
        }
        var strError = "";
        if(objErr != undefined){
            switch(parseInt(objErr.readyState)){
                case 0: strError = "El request nunca se inicio"; break;
                case 1: strError = "No se logro enviar los datos"; break;
                case 2: strError = "Ya se conecto y se logro recibir respuesta"; break;
                case 3: strError = "Se logro procesar el request"; break;
                case 4:
                    switch(parseInt(objErr.status)){
                        case 200: 
                            if(objErr.responseText != ""){
                                try {
                                    JSON.parse(objErr.responseText);
                                    strError = "Eureka! todo finalizo bien en el proceso iniciado"; 
                                } catch (e) {
                                    strError = "Ooops! Parece ser que el servidor no respondio con el formato indicado(FNT) :("; 
                                }
                            }else{
                                strError = "Ooops! Parece ser que el servidor no respondio con el formato indicado :("; 
                            }
                            break;
                        case 404: strError = "Oyee! Quisiste ir hacia un lugar que no existe - (E-404)"; break;
                        case 405: strError = "Nah Nah, metodo HTTP no permitido - (E-405)"; break;
                        case 500: strError = "Ooops! Cosas que suceden, verificaremos de que se trata, mientras tanto tu tranquilo ,recarga el programa e intenta de nuevo :) ;) - (E-500)"; break;
                    }
                    break;
                default:
                    strError = "Uyyy! no hubo respuesta, verificaremos que paso ;)";
            }
        }else{
            strError = "Oopps! Ocurrio algo desconocido, recarga el programa e intentalo de nuevo :)";
        }
        $.sy_closePreload();
        $.sy_addDivError(
            "red",
            "ERROR - "+strError+" <a href='javascript:void(0)' class='' onclick='location.reload();'>Recargar <i class='fa fa-refresh'></i></a>",
            null,
            target_paint_error
        );
    },
    
    /**
     * cierra el preload
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @date 30-03-2015
     * @param {type} id_div_pre
     * @returns {undefined}
     */
    sy_closePreload : function(id_div_pre){
        $('body').addClass('loaded');
    },
    
    /**
     * 
     * @param {type} div
     * @returns {Boolean}
     */
    sy_hasScrollBar : function(div){
        return $(div).get(0).scrollHeight > $(div).get(0).clientHeight;
    },
    
    /**
     * valida el response del request
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.sos>
     * @date 28-03-2015
     * @param {type} msg
     * @param {type} id_div_pre
     * @param {type} ind_anim
     * @param {type} alertError
     * @returns {undefined|Boolean}
     */
    sy_validateResponse : function(args) {
        args = jQuery.extend({
            alertError : true,
            target_paint_error:"#divStatusResponse"
        }, args);
        
        // validamos si hay msg de respuesta
        if (args.response == undefined) {
            alert('Error: Ocurrio un error en el response del request');
            return false;
        }else{
            /** validamos si existe codigo a ejecutar javascript */
            if (args.response.msg.code != null) {
               alert(args.response.msg.msj);
               eval(args.response.msg.code);
               return;
            }
            if(args.alertError == true && args.response.msg != null){
                if(args.response.type_msg != "not-alert"){
                    if(args.response.type_msg == "error"){
                        $.sy_addDivError(
                            'red',
                            (args.response.msg.msgResponseFirst==undefined?
                            "Ooops! No eres tu soy yo, relajate, recarga la pagina y vuelve a intentarlo":
                            args.response.msg.msgResponseFirst),
                            $.sy_configureDivDetalleStatus(args.response.msg.msgResponseDetails),
                            args.target_paint_error
                        );
                    }else{
                        if(args.response.type_msg == "success") {
                            $.sy_addDivError(
                                    'teal',
                                    (args.response.msg.msgResponseFirst==undefined?
                                    "Tu tranquilo, todo finalizo correctamente :)":
                                    args.response.msg.msgResponseFirst),
                                    $.sy_configureDivDetalleStatus(args.response.msg.msgResponseDetails),
                                    args.target_paint_error
                            );
                        }else{
                            if(args.response.type_msg == "warning") {
                                $.sy_addDivError(
                                    'orange darken-3',
                                    (args.response.msg.msgResponseFirst==undefined?
                                    "Uyyy! Algo no finalizo muy bien":
                                    args.response.msg.msgResponseFirst
                                    ),
                                    $.sy_configureDivDetalleStatus(args.response.msg.msgResponseDetails),
                                    args.target_paint_error
                                );
                            }
                        }
                    }
                    $('html, body').animate({scrollTop: '0px'}, 0);
                } // end not-alert
            }
        }
        
        return true;
    },
    
    /**
     * 
     * @returns {String}
     */
    sy_getHtmlPreload : function(){
        return ''+
            '<div class="preloader-wrapper big active">'+
                '<div class="spinner-layer spinner-blue">'+
                  '<div class="circle-clipper left">'+
                    '<div class="circle"></div>'+
                  '</div><div class="gap-patch">'+
                    '<div class="circle"></div>'+
                  '</div><div class="circle-clipper right">'+
                    '<div class="circle"></div>'+
                  '</div>'+
                '</div>'+
                '<div class="spinner-layer spinner-red">'+
                  '<div class="circle-clipper left">'+
                    '<div class="circle"></div>'+
                  '</div><div class="gap-patch">'+
                    '<div class="circle"></div>'+
                  '</div><div class="circle-clipper right">'+
                    '<div class="circle"></div>'+
                  '</div>'+
                '</div>'+
                '<div class="spinner-layer spinner-yellow">'+
                  '<div class="circle-clipper left">'+
                    '<div class="circle"></div>'+
                  '</div><div class="gap-patch">'+
                    '<div class="circle"></div>'+
                  '</div><div class="circle-clipper right">'+
                    '<div class="circle"></div>'+
                  '</div>'+
                '</div>'+
                '<div class="spinner-layer spinner-green">'+
                  '<div class="circle-clipper left">'+
                    '<div class="circle"></div>'+
                  '</div><div class="gap-patch">'+
                    '<div class="circle"></div>'+
                  '</div><div class="circle-clipper right">'+
                    '<div class="circle"></div>'+
                  '</div>'+
                '</div>'+
            '</div>'+
        '';
    },
    
    /**
     * 
     * @returns {String}
     */
    sy_getHtmlPreloadWin10 : function(str){
        return ''+
            '<style>'+
    '.windows8 {'+
    '   position: relative;'+
    '   width: 90px;'+
    '   height: 90px;'+
    '   margin:auto !important;'+
    '    '+
    '   /*margin-top: 100px;*/'+
    '   /*margin-left: 100px;*/'+
    '}'+

    '.windows8 .wBall {'+
    '   position: absolute;'+
    '   width: 86px;'+
    '   height: 86px;'+
    '   opacity: 0;'+
    '   -moz-transform: rotate(225deg);'+
    '   -moz-animation: orbit 5.5s infinite;'+
    ''+
    '    -webkit-transform: rotate(225deg);'+
    '    -webkit-animation: orbit 5.5s infinite;'+
    ''+
    '    -ms-transform: rotate(225deg);'+
    '    -ms-animation: orbit 5.5s infinite;'+
    ''+
    '    -o-transform: rotate(225deg);'+
    '    -o-animation: orbit 5.5s infinite;'+
    ''+
    '    transform: rotate(225deg);'+
    '    animation: orbit 5.5s infinite;'+
    '}'+
    ''+
    '.windows8 .wBall .wInnerBall{'+
    '    position: absolute;'+
    '    width: 11px;'+
    '    height: 11px;'+
    'background: #e80404;'+
    '    left:0px;'+
    '    top:0px;'+
     '   '+
            '-moz-border-radius: 11px;'+
        '-webkit-border-radius: 11px;'+
            '-ms-border-radius: 11px;'+
            '-o-border-radius: 11px;'+
            'border-radius: 11px;'+
    '}'+

    '.windows8 #wBall_1 {'+
     '   -moz-animation-delay: 1.20s;'+
     '   -webkit-animation-delay: 1.20s;'+
     '       -ms-animation-delay: 1.20s;'+
     '        -o-animation-delay: 1.20s;'+
     '           animation-delay: 1.20s;'+
    '}'+

    '.windows8 #wBall_2 {'+
     '      -moz-animation-delay: 0.41s;'+
     '   -webkit-animation-delay: 0.41s;'+
     '       -ms-animation-delay: 0.41s;'+
     '        -o-animation-delay: 0.41s;'+
     '           animation-delay: 0.41s;'+
    '}'+

    '.windows8 #wBall_3 {'+
     '      -moz-animation-delay: 0.60s;'+
     '   -webkit-animation-delay: 0.60s;'+
     '       -ms-animation-delay: 0.60s;'+
     '        -o-animation-delay: 0.60s;'+
     '           animation-delay: 0.60s;'+
    '}'+

    '.windows8 #wBall_4 {'+
     '      -moz-animation-delay: 0.80s;'+
     '   -webkit-animation-delay: 0.80s;'+
     '       -ms-animation-delay: 0.80s;'+
     '        -o-animation-delay: 0.80s;'+
     '           animation-delay: 0.80s;'+
    '}'+

    '.windows8 #wBall_5 {'+
     '      -moz-animation-delay: 1.00s;'+
     '   -webkit-animation-delay: 1.00s;'+
     '       -ms-animation-delay: 1.00s;'+
     '        -o-animation-delay: 1.00s;'+
     '           animation-delay: 1.00s;'+
    '}'+

    '@-moz-keyframes orbit {'+
     '  0% {'+
     '     opacity: 1;'+
     '     z-index:99;'+
     '     -moz-transform: rotate(180deg);'+
     '     -moz-animation-timing-function: ease-out;'+
     '  }'+

     '  7% {'+
     '     opacity: 1;'+
     '     -moz-transform: rotate(300deg);'+
     '     -moz-animation-timing-function: linear;'+
     '     -moz-origin:0%;'+
     '  }'+

      ' 30% {'+
      '    opacity: 1;'+
      '    -moz-transform:rotate(410deg);'+
      '    -moz-animation-timing-function: ease-in-out;'+
      '    -moz-origin:7%;'+
      ' }'+

    '   39% {'+
    '      opacity: 1;'+
    '      -moz-transform: rotate(645deg);'+
    '      -moz-animation-timing-function: linear;'+
    '      -moz-origin:30%;'+
    '   }'+

    '   70% {'+
    '      opacity: 1;'+
    '      -moz-transform: rotate(770deg);'+
    '      -moz-animation-timing-function: ease-out;'+
    '      -moz-origin:39%;'+
    '   }'+
    ''+
    '   75% {'+
    '      opacity: 1;'+
    '      -moz-transform: rotate(900deg);'+
    '      -moz-animation-timing-function: ease-out;'+
    '      -moz-origin:70%;'+
    '   }'+
    ''+
    '   76% {'+
    '      opacity: 0;'+
    '      -moz-transform:rotate(900deg);'+
    '   }'+
    ''+
    '   100% {'+
    '      opacity: 0;'+
    '      -moz-transform: rotate(900deg);'+
    '   }'+
    '}'+
    ''+
    '@-webkit-keyframes orbit {'+
    '   0% {'+
    '      opacity: 1;'+
    '      z-index:99;'+
    '      -webkit-transform: rotate(180deg);'+
    '      -webkit-animation-timing-function: ease-out;'+
    '   }'+
    ''+
    '   7% {'+
    '      opacity: 1;'+
    '      -webkit-transform: rotate(300deg);'+
    '      -webkit-animation-timing-function: linear;'+
    '      -webkit-origin:0%;'+
    '   }'+
    ''+
    '   30% {'+
    '      opacity: 1;'+
    '      -webkit-transform:rotate(410deg);'+
    '      -webkit-animation-timing-function: ease-in-out;'+
    '      -webkit-origin:7%;'+
    '   }'+
    ''+
    '   39% {'+
    '      opacity: 1;'+
    '      -webkit-transform: rotate(645deg);'+
    '      -webkit-animation-timing-function: linear;'+
    '      -webkit-origin:30%;'+
    '   }'+
    ''+
    '   70% {'+
    '      opacity: 1;'+
    '      -webkit-transform: rotate(770deg);'+
    '      -webkit-animation-timing-function: ease-out;'+
    '      -webkit-origin:39%;'+
    '   }'+
    ''+
    '   75% {'+
    '      opacity: 1;'+
    '      -webkit-transform: rotate(900deg);'+
    '      -webkit-animation-timing-function: ease-out;'+
     '     -webkit-origin:70%;'+
     '  }'+
    ''+
     '  76% {'+
     '     opacity: 0;'+
     '     -webkit-transform:rotate(900deg);'+
     '  }'+

     '  100% {'+
     '     opacity: 0;'+
     '     -webkit-transform: rotate(900deg);'+
     '  }'+
    '}'+

    '@-ms-keyframes orbit {'+
     '  0% {'+
     '     opacity: 1;'+
     '     z-index:99;'+
     '     v-ms-transform: rotate(180deg);'+
     '     -ms-animation-timing-function: ease-out;'+
     '  }'+

     '  7% {'+
     '     opacity: 1;'+
     '     -ms-transform: rotate(300deg);'+
     '     -ms-animation-timing-function: linear;'+
     '     -ms-origin:0%;'+
     '  }'+
     '  30% {'+
     '     opacity: 1;'+
     '     -ms-transform:rotate(410deg);'+
     '     -ms-animation-timing-function: ease-in-out;'+
     '     -ms-origin:7%;'+
     '  }'+
     '  39% {'+
     '     opacity: 1;'+
     '     -ms-transform: rotate(645deg);'+
     '     -ms-animation-timing-function: linear;'+
     '     -ms-origin:30%;'+
      ' }'+

      ' 70% {opacity: 1;-ms-transform: rotate(770deg);-ms-animation-timing-function: ease-out;-ms-origin:39%;'+
      ' }'+

      ' 75% {    opacity: 1; -ms-transform: rotate(900deg); -ms-animation-timing-function: ease-out; -ms-origin:70%;'+
      ' }'+

      ' 76% {   opacity: 0;    -ms-transform:rotate(900deg); }'+

      ' 100% {opacity: 0;    -ms-transform: rotate(900deg); }'+
    '}'+

    '@-o-keyframes orbit {'+
     '  0% {     opacity: 1;     z-index:99;-o-transform: rotate(180deg);-o-animation-timing-function: ease-out;}'+

       '7% {'+
        '  opacity: 1;-o-transform: rotate(300deg);-o-animation-timing-function: linear;-o-origin:0%;'+
       '}'+

       '30% {'+
       '   opacity: 1;-o-transform:rotate(410deg);-o-animation-timing-function: ease-in-out;-o-origin:7%;'+
       '}'+

       '39% {'+
       '   opacity: 1;-o-transform: rotate(645deg);-o-animation-timing-function: linear;-o-origin:30%;'+
    '   }'+

     '  70% {'+
     '     opacity: 1;-o-transform: rotate(770deg);-o-animation-timing-function: ease-out;-o-origin:39%;'+
     '  }'+

     '  75% {'+
     '     opacity: 1;-o-transform: rotate(900deg);-o-animation-timing-function: ease-out;-o-origin:70%;'+
       '}'+

       '76% {opacity: 0;-o-transform:rotate(900deg);}'+

       '100% {-o-transform: rotate(900deg);}'+
    '}'+

    '@keyframes orbit {'+
     '  0% {opacity: 1;z-index:99;transform: rotate(180deg);animation-timing-function: ease-out;}'+

       '7% {opacity: 1;transform: rotate(300deg);animation-timing-function: linear;origin:0%;}'+

       '30% {opacity: 1;transform:rotate(410deg);animation-timing-function: ease-in-out;origin:7%;}'+

       '39% {opacity: 1;transform: rotate(645deg);animation-timing-function: linear;origin:30%;}'+

       '70% {opacity: 1;transform: rotate(770deg);animation-timing-function: ease-out;origin:39%;}'+

       '75% {opacity: 1;transform: rotate(900deg);animation-timing-function: ease-out;origin:70%;}'+

       '76% {opacity: 0;transform:rotate(900deg);}'+

       '100% {opacity: 0;transform: rotate(900deg);}'+
    '}'+

    '</style>'+

    '<div class="">'+
    '<div class="windows8">'+
    '   <div class="wBall" id="wBall_1">'+
    '       <div class="wInnerBall"></div>'+
    '   </div>'+
    '   <div class="wBall" id="wBall_2">'+
    '       <div class="wInnerBall"></div>'+
    '   </div>'+
    '   <div class="wBall" id="wBall_3">'+
    '       <div class="wInnerBall"></div>'+
    '   </div>'+
    '   <div class="wBall" id="wBall_4">'+
    '       <div class="wInnerBall"></div>'+
    '   </div>'+
    '   <div class="wBall" id="wBall_5">'+
    '       <div class="wInnerBall"></div>'+
    '   </div>'+
    '</div>'+
    (str!=null?'<div>'+str+'</div>':'')+
    '</div>';
    },/**fin funcion preload*/
    
    
    /**
     * 
     * @param {type} args
     * @returns {undefined}
     */
    sy_setMapaRutaViewloadIFrame : function(args){
        if(document.getElementById('divMapRoute')){
            $('#divMapRoute').remove();
        }
        if(!document.getElementById('divMapRoute')){
            var ma = $('#iframe_'+args.id).contents().find('body').find('#_module_alias').val();
            var va = $('#iframe_'+args.id).contents().find('body').find('#_view_alias').val();
            var ac_s = $('#iframe_'+args.id).contents().find('body').find('#_action_selected').val();
            if(ma!="" && va!=""){
                var arrLi = [];
                if($('#iframe_'+args.id).contents().find('body').find('#_li').val()!=""){
                    var arrLi = $('#iframe_'+args.id).contents().find('body').find('#_li').val().split("|");
                }
                var strH = "";
                    strH += "<div class='breadcrumb-custom'>";
                    
                    strH += "&nbsp;/&nbsp;";
                    
                    strH += "<div class='btn-group'>";
                    strH += '       <a class="header-dropdown dropdown-toggle accent-color" data-toggle="dropdown" href="#"><span class="fa fa-cubes" style="font-size: 18px;"></span><b class="caret"></b></a>';
                    strH += '       <ul class="dropdown-menu">';
                    strH += (arrLi[0]!=undefined?arrLi[0]:'');
                    strH += '       </ul>';
                    strH += "</div>";
                    
                    strH += "&nbsp;/&nbsp;";
                    
                    strH += "<div class='btn-group'>";
                    strH += '       <a class="header-dropdown dropdown-toggle accent-color" data-toggle="dropdown" href="#"><span class="fa fa-cube" style="font-size: 18px;"><b class="caret"></b></a>';
                    strH += '       <ul class="dropdown-menu">';
                    strH += (arrLi[1]!=undefined?arrLi[1]:'');
                    strH += '       </ul>';
                    strH += "</div>";
                    
                    strH += "&nbsp;/&nbsp;";
                    
                    strH += "<div class='btn-group'>";
                    strH += '       <a class="header-dropdown dropdown-toggle accent-color" data-toggle="dropdown" href="#"><span class="fa fa-cogs" style="font-size: 18px;"><b class="caret"></b></a>';
                    strH += '       <ul class="dropdown-menu">';
                    strH += (arrLi[2]!=undefined?arrLi[2]:'');
                    strH += '       </ul>';
                    strH += "</div>";
                    
                    strH += '</div>';
                $('#divLogoSmallerModal').after('<div id="divMapRoute" class="pull-left">'+strH+'</div>');
                /*evento click d links*/
                $.sy_setLinkInternoSubMenu();
            }
        }
    },
    
    /**
     * set los campos y valores ocultos del iframe
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @returns {undefined}
     */
    sy_setHiddenFieldIframe : function(){
        if($.sy_isset('gblArrDataIframe')){
            if(gblArrDataIframe != "" && gblArrDataIframe!=null){
                var jsonExtract = $.sy_html_entity_decode(gblArrDataIframe);
                /***/
                //console.log(jsonExtract);
                gblArrDataIframe = null;
                jsonExtract = JSON.parse(jsonExtract);
                // verifica si el campo existe
                if(!document.getElementById('_module_alias')){
                    $('body').append('<input type="hidden" id="_module_alias" name="_module_alias" value="'+jsonExtract.data.module_alias+'"/>');
                }else{
                    $('#_module_alias').val(jsonExtract.data.module_alias);
                }
                // verifica si el campo existe   
                if(!document.getElementById('_view_alias')){
                    $('body').append('<input type="hidden" id="_view_alias" name="_view_alias" value="'+jsonExtract.data.view_alias+'"/>');
                }else{
                    $('#_view_alias').val(jsonExtract.data.view_alias);
                }
                // verifica si el campo existe   
                if(!document.getElementById('_actions')){
                    $('body').append('<input type="hidden" id="_actions" name="_actions" value="'+jsonExtract.data.actions+'"/>');
                }else{
                    $('#_view_alias').val(jsonExtract.data.view_alias);
                }
                // verifica si el campo existe   
                if(!document.getElementById('_action_selected')){
                    $('body').append('<input type="hidden" id="_action_selected" name="_action_selected" value="'+jsonExtract.data.action_selected+'"/>');
                }else{
                    $('#_view_alias').val(jsonExtract.data.view_alias);
                }
                // verifica si el campo existe
                if(!document.getElementById('_other_data')){
                    $('body').append('<input type="hidden" id="_other_data" name="_other_data" value="'+jsonExtract.data.other_data+'"/>');
                }else{
                    $('#_other_data').val(jsonExtract.data.other_data);
                }
                /**construimos el menu de modules y views*/
                var hMenu = '';
                var hView = '';
                var hAct = '';
                $.each(jsonExtract.data.menu[0],function(index,value){
                    hMenu += "<li><a class='link-module' data-syslab='"+index+"' href='' style='"+(jsonExtract.data.module_alias==value.module_alias?'color:red;':'')+"'>";
                    hMenu += "  <span class='icon-arrow-left-3'></span>";
                    hMenu += "  "+$.sy_ucwords(value.module_alias);
                    hMenu += "</a></li>";
                    $.each(value,function(in2,va3){
                        if (typeof (va3) == 'object' && va3.module_alias == jsonExtract.data.module_alias) {
                            hView += "<li class='"+(va3.view_alias==jsonExtract.data.view_alias?'disabled':'')+"'>";
                            hView += "  <a href='javascript:void(0)' style='"+(va3.view_alias==jsonExtract.data.view_alias?'color:red;':'')+"' class='"+(va3.view_alias==jsonExtract.data.view_alias?'':'li-dropdown-menu')+"' data='"+in2+"/read'>";
                            if(va3.view_alias==jsonExtract.data.view_alias){
                                hView += "      <span class='icon-checkbox'></span>";
                            }
                            hView += "      "+$.sy_ucwords(va3.view_alias);
                            hView += "  </a>";
                            hView += "</li>";
                        }
                    });
                });
                var arrAct = jsonExtract.data.actions;
                for(var k=0;k<arrAct.length;k++){
                    hAct += "<li class='"+(arrAct[k]==jsonExtract.data.action_selected?'disabled':'')+"'>";
                    hAct += "  <a href='javascript:void(0)' style='"+(arrAct[k]==jsonExtract.data.action_selected?'color:red;':'')+"' class='"+(arrAct[k]==jsonExtract.data.action_selected?'':'li-dropdown-menu')+"' data='app/"+jsonExtract.data.module+"/"+jsonExtract.data.view+"/"+arrAct[k]+"'>";
                    if(arrAct[k]==jsonExtract.data.action_selected){
                        hAct += "      <span class='icon-checkbox'></span>";
                    }
                    hAct += "      "+$.sy_ucwords($.sy_getAliasSpanish(arrAct[k]));
                    hAct += "  </a>";
                    hAct += "</li>";
                }
                
                if(!document.getElementById('_li')){
                    $('body').append('<input type="hidden" id="_li" name="_li" value="'+hMenu+'|'+hView+'|'+hAct+'"/>');
                }else{
                    $('#_li').val(jsonExtract.data.other_data);
                }
            }
        }
    },
    
    /**
     * return el nombre en spn del action
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 14-05-2015
     * @param {type} act
     * @returns {String}
     */
    sy_getAliasSpanish : function(act){
        switch(act){
            case "create":
                return "Crear";
            case "read":
                return "Listar";
            case "update":
                return "Actualizar";
            case "delete":
                return "Borrar";
        }
        
    },
    
    /**
     * set los link internos
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @returns {undefined}
     */
    sy_setLinkInternoSubMenu : function(){
        $('.link-module').unbind('click');
        $('.link-module').click(function (e) {
            $('#modalBodyHome').modal('hide');
	    e.preventDefault();

	    var target = $(this).attr('data-syslab');
	    var $target = $('#div'+target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        //window.location.hash = target;
	    });
	});
    },
    
    /**
     * detecta q tipo d dispositivo es
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @date 31-03-2015
     * @returns {String}
     */
    sy_detectDispositivo : function(){
        var device = navigator.userAgent;

        if (
                device.match(/Iphone/i)|| device.match(/Ipod/i)|| device.match(/Android/i)|| 
                device.match(/J2ME/i)|| device.match(/BlackBerry/i)|| device.match(/iPhone|iPad|iPod/i)|| 
                device.match(/Opera Mini/i)|| device.match(/IEMobile/i)|| 
                device.match(/Mobile/i)|| device.match(/Windows Phone/i)|| 
                device.match(/windows mobile/i)|| device.match(/windows ce/i)|| 
                device.match(/webOS/i)|| device.match(/palm/i)|| device.match(/bada/i)|| 
                device.match(/series60/i)|| device.match(/nokia/i)|| device.match(/symbian/i)|| 
                device.match(/HTC/i)){ 
            return "Mobile";
        }
        else{   
            return "PC";
        }
    },
    
    /**
     * 
     * @param {type} string
     * @param {type} quote_style
     * @returns {String|Boolean}
     */
    sy_html_entity_decode : function(string, quote_style) {
        var hash_map = {},
          symbol = '',
          tmp_str = '',
          entity = '';
        tmp_str = string.toString();

        if (false === (hash_map = $.sy_get_html_translation_table('HTML_ENTITIES', quote_style))) {
          return false;
        }

        // fix &amp; problem
        // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
        delete(hash_map['&']);
        hash_map['&'] = '&amp;';

        for (symbol in hash_map) {
          entity = hash_map[symbol];
          tmp_str = tmp_str.split(entity)
            .join(symbol);
        }
        tmp_str = tmp_str.split('&#039;')
          .join("'");

        return tmp_str;
    },
    
    /**
     * 
     * @param {type} table
     * @param {type} quote_style
     * @returns {unresolved}
     */
    sy_get_html_translation_table : function(table, quote_style) {
        var entities = {},
          hash_map = {},
          decimal;
        var constMappingTable = {},
          constMappingQuoteStyle = {};
        var useTable = {},
          useQuoteStyle = {};

        // Translate arguments
        constMappingTable[0] = 'HTML_SPECIALCHARS';
        constMappingTable[1] = 'HTML_ENTITIES';
        constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
        constMappingQuoteStyle[2] = 'ENT_COMPAT';
        constMappingQuoteStyle[3] = 'ENT_QUOTES';

        useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
        useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() :
          'ENT_COMPAT';

        if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
          throw new Error('Table: ' + useTable + ' not supported');
          // return false;
        }

        entities['38'] = '&amp;';
        if (useTable === 'HTML_ENTITIES') {
          entities['160'] = '&nbsp;';
          entities['161'] = '&iexcl;';
          entities['162'] = '&cent;';
          entities['163'] = '&pound;';
          entities['164'] = '&curren;';
          entities['165'] = '&yen;';
          entities['166'] = '&brvbar;';
          entities['167'] = '&sect;';
          entities['168'] = '&uml;';
          entities['169'] = '&copy;';
          entities['170'] = '&ordf;';
          entities['171'] = '&laquo;';
          entities['172'] = '&not;';
          entities['173'] = '&shy;';
          entities['174'] = '&reg;';
          entities['175'] = '&macr;';
          entities['176'] = '&deg;';
          entities['177'] = '&plusmn;';
          entities['178'] = '&sup2;';
          entities['179'] = '&sup3;';
          entities['180'] = '&acute;';
          entities['181'] = '&micro;';
          entities['182'] = '&para;';
          entities['183'] = '&middot;';
          entities['184'] = '&cedil;';
          entities['185'] = '&sup1;';
          entities['186'] = '&ordm;';
          entities['187'] = '&raquo;';
          entities['188'] = '&frac14;';
          entities['189'] = '&frac12;';
          entities['190'] = '&frac34;';
          entities['191'] = '&iquest;';
          entities['192'] = '&Agrave;';
          entities['193'] = '&Aacute;';
          entities['194'] = '&Acirc;';
          entities['195'] = '&Atilde;';
          entities['196'] = '&Auml;';
          entities['197'] = '&Aring;';
          entities['198'] = '&AElig;';
          entities['199'] = '&Ccedil;';
          entities['200'] = '&Egrave;';
          entities['201'] = '&Eacute;';
          entities['202'] = '&Ecirc;';
          entities['203'] = '&Euml;';
          entities['204'] = '&Igrave;';
          entities['205'] = '&Iacute;';
          entities['206'] = '&Icirc;';
          entities['207'] = '&Iuml;';
          entities['208'] = '&ETH;';
          entities['209'] = '&Ntilde;';
          entities['210'] = '&Ograve;';
          entities['211'] = '&Oacute;';
          entities['212'] = '&Ocirc;';
          entities['213'] = '&Otilde;';
          entities['214'] = '&Ouml;';
          entities['215'] = '&times;';
          entities['216'] = '&Oslash;';
          entities['217'] = '&Ugrave;';
          entities['218'] = '&Uacute;';
          entities['219'] = '&Ucirc;';
          entities['220'] = '&Uuml;';
          entities['221'] = '&Yacute;';
          entities['222'] = '&THORN;';
          entities['223'] = '&szlig;';
          entities['224'] = '&agrave;';
          entities['225'] = '&aacute;';
          entities['226'] = '&acirc;';
          entities['227'] = '&atilde;';
          entities['228'] = '&auml;';
          entities['229'] = '&aring;';
          entities['230'] = '&aelig;';
          entities['231'] = '&ccedil;';
          entities['232'] = '&egrave;';
          entities['233'] = '&eacute;';
          entities['234'] = '&ecirc;';
          entities['235'] = '&euml;';
          entities['236'] = '&igrave;';
          entities['237'] = '&iacute;';
          entities['238'] = '&icirc;';
          entities['239'] = '&iuml;';
          entities['240'] = '&eth;';
          entities['241'] = '&ntilde;';
          entities['242'] = '&ograve;';
          entities['243'] = '&oacute;';
          entities['244'] = '&ocirc;';
          entities['245'] = '&otilde;';
          entities['246'] = '&ouml;';
          entities['247'] = '&divide;';
          entities['248'] = '&oslash;';
          entities['249'] = '&ugrave;';
          entities['250'] = '&uacute;';
          entities['251'] = '&ucirc;';
          entities['252'] = '&uuml;';
          entities['253'] = '&yacute;';
          entities['254'] = '&thorn;';
          entities['255'] = '&yuml;';
        }

        if (useQuoteStyle !== 'ENT_NOQUOTES') {
          entities['34'] = '&quot;';
        }
        if (useQuoteStyle === 'ENT_QUOTES') {
          entities['39'] = '&#39;';
        }
        entities['60'] = '&lt;';
        entities['62'] = '&gt;';

        // ascii decimals to real symbols
        for (decimal in entities) {
          if (entities.hasOwnProperty(decimal)) {
            hash_map[String.fromCharCode(decimal)] = entities[decimal];
          }
        }

        return hash_map;
    },
    
    /**
     * 
     * @returns {Boolean}
     */
    sy_isset : function(str){
        return window[str] !== undefined;
    },
    
    /**
     * 
     * @param {type} str
     * @returns {String}
     */
    sy_ucwords : function(str) {
        return (str + '')
          .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
          });
    },
    
    /**
     * 
     * @param {type} datos
     * @param {type} idcombo
     * @param {type} stroptini
     * @param {type} icon
     * @param {type} colName
     * @returns {Boolean}
     */
    sy_setCombo : function(args) {
        args = jQuery.extend({
            datos:[],
            optIni:null,
            icon:null,
            colName:null,
            concatColName:null,
            dataExtra:null,
            forceOptIni:false,
            params:{},
            afterCombo:function(){},
            exeFnPostOptSingle:function(){}
        }, args);
        
        if (args.datos === null || args.datos=='' || args.datos==undefined) {
            return false;
        }
        if (args.optIni === undefined || args.optIni === null) {
            args.optIni = 'Seleccione...';
        }

        var stroption = '';
        if (args.optIni != false && (args.datos.length > 1 || args.forceOptIni == true) )  {
            stroption += '<option value="" selected="selected">' + args.optIni + '</option>';
        }
        for (var i = 0; i < args.datos.length; i++) {
            var data_extra = '';
            if(args.dataExtra != null){
                var arrDExtra = args.dataExtra.split("|");
                var strDEx = "";
                for(var z=0;z < arrDExtra.length;z++){
                    eval("strDEx = strDEx + args.datos[i]."+arrDExtra[z]);
                    strDEx += "|";
                }
                strDEx = strDEx.substring(0,(strDEx.length-1));
                data_extra = "data-extra='"+strDEx+"'";
            }
            if (args.colName == null) {
                stroption += "<option value='" + args.datos[i].id + "' "+data_extra+">" + args.datos[i].name + "</option>";
            } else {
                if(args.colName != null && typeof args.colName[1] != 'object'){
                    stroption += "<option value='" + (args.colName==null?args.datos[i].id:eval('args.datos[i].'+args.colName[0])) + "'  "+data_extra+">" + (args.colName==null?args.datos[i].name:eval('args.datos[i].'+args.colName[1])) + "</option>";
                }else{
                    var concatEnd = '';
                    var objA = args.colName[1];
                    // recorremos los campos
                    for(var k=0;k<objA.length;k++){
                        eval('concatEnd += args.datos[i].'+args.colName[1][k]);
                        concatEnd += " ";
                    }
                    stroption += "<option value='" + (args.colName==null?args.datos[i].id:eval('args.datos[i].'+args.colName[0])) + "'  "+data_extra+">" + (concatEnd) + "</option>";
                }
            }
        }
        // set combo //
        $(args.idcombo).html(stroption);
        if (args.datos.length == 1) {
            if (!$(args.idcombo).is(':visible')) {
                setTimeout(
                        function() {
                            $(args.idcombo).next().children().val($(args.idcombo + ' option:selected').text());
                        },
                        500
                );
            }
            // verificamos si es una combo line single
            args.exeFnPostOptSingle(args.params);
        }

        // after carga combo
        args.afterCombo(args.params);
        
        return true;
    },
    
    /**
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param {type} objForm
     * @returns {Boolean}
     */
    sy_validateForm : function(objForm,target_paint_error) {
        objForm = $(objForm);
        var bolvalidate = true;
        var arrStr = [];
        var strmsj = "";
        $.sy_removerClassValidate(objForm);
        objForm.find('.validate').each(function() {
            if(this.nodeName == "INPUT" || this.nodeName == "SELECT"){
                var elemento = this;
                // separamos las palabras //
                arrStr = $.sy_separarPalabras(elemento.id.substring(3));
                // validamos que el campo no este vacio //
                if (jQuery.trim(elemento.value) == '') {
                    bolvalidate = false;
                    strmsj += "El campo: (" + arrStr.toUpperCase() + ") es requerido<br/>";
                    //agregamos la clase del errorr
                    if(this.nodeName == "INPUT"){
                        $(this).addClass("validate-error");
                        $(this).next().addClass("validate-error-text");
                    }else{
                        if(this.nodeName == "SELECT"){
                            $(this).prev().prev().addClass("validate-error").addClass("validate-error-text");
                        }
                    }
                }
                // validamos los tipo email
                if ($(this).attr('type') == 'email' && $.sy_test_rex($(this), $.sy_rexp_validate_email()) == false) {
                    bolvalidate = false;
                    strmsj += "En el campo : (" + arrStr.toUpperCase() + ") el email esta incorrecto, ej. example@correo.com<br/>";
                    $(this).addClass("validate-error");
                    $(this).next().addClass("validate-error-text");
                }
                //validamos type number
                if ($(this).attr('type') == 'number' && $.sy_test_rex($(this), $.sy_rex_validate_numeros_positivos_enteros()) == false) {
                    bolvalidate = false;
                    strmsj += "El campo : (" + arrStr.toUpperCase() + ") solo recibe numero(s) entero(s)<br/>";
                    $(this).addClass("validate-error");
                    $(this).next().addClass("validate-error-text");
                }
                
            } // fin if principal
        });
        //agregamos el div d error
        if(!bolvalidate){
            $.sy_addDivError("orange darken-3","Formulario con errores",strmsj,target_paint_error);
        }
        return bolvalidate;
    },
    
    /**
     * quita la clase de error
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @param {type} form
     * @returns {undefined}
     */
    sy_removerClassValidate : function(form) {
        $('#divMsgPrincipal').remove();
        $(form).find('.validate').each(function() {
            if(this.nodeName == "INPUT"){
                $(this).removeClass("validate-error");
                $(this).next().removeClass("validate-error-text");
            }else{
                if(this.nodeName == "SELECT"){
                    $(this).prev().prev()
                            .removeClass("validate-error")
                            .removeClass("validate-error-text");
                }
            }
        });
    },
    
    /**
     * separa palabra y return un string
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @param {type} str
     * @returns {String}
     */
    sy_separarPalabras : function(str) {
        var result = str.replace(/([A-Z]+)/g, ",$1").replace(/^,/, "");
        result = result.split(",");
        var strR = "";
        for(var i=0;i<result.length;i++){
            strR = (strR +(i>0?" ":"")+ result[i]);
        }
        return strR;
    },
    
    /**
     * evalua una exp regular
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @param {type} o
     * @param {type} regexp
     * @returns {Boolean}
     */
    sy_test_rex : function(o, regexp) {
        if (!(regexp.test(o.val()))) {
            return false;
        } else {
            return true;
        }
    },
    
    /**
     * valida pro medio de exp regular un email
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @returns {RegExp}
     */
    sy_rexp_validate_email : function() {
        return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
    },
    
    /**
     * rset el campo dejandolo en blanco o vacio
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @param {type} obj
     * @returns {undefined}
     */
    sy_reset_campo : function(obj) {
        obj.val('');
        obj.focus();
    },
    
    /**
     * valida nmeros positivos
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-04-2015
     * @returns {RegExp}
     */
    sy_rex_validate_numeros_positivos_enteros : function() {
        return /[0-9]+/
    },
    
    /**
     * agrrega el div del error
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param {type} cla
     * @param {type} msjP
     * @param {type} msjS
     * @returns {undefined}
     */
    sy_addDivError : function(cla,msjP,msjS,target_paint_error){
        var stE = '';
        stE += "<div id='divMsgPrincipal' class='card-panel "+cla+"'>";
        stE += "    <i class='text-darken-2 white-text material-icons right' onclick='this.parentElement.parentElement.removeChild(this.parentElement);' style='cursor:pointer;'>close</i>";
        stE += "    <h6 class='text-darken-2 white-text'><strong>¡Atenci&oacute;n!</strong></h6>";
        stE += "    <span class='text-darken-2 white-text'>"+msjP+"</span>";
        
        
        if(msjS!=undefined && msjS!=null && msjS!=false){
            stE += "    <br/><a href='javascript:void(0)' onclick='$(\"#divMsgPrincipal_detalles\").toggle();' class='text-darken-2 white-text'>((( ver detalles )))</a>";
        }
        if(msjS!=undefined && msjS!=null && msjS!=false){
            stE += "    <div class='row text-darken-2 white-text' id='divMsgPrincipal_detalles' style='display:none;'>";
            stE += "        "+msjS;
            stE += "    </div>";
        }
        stE += "</div></div>";
        /**add*/
        if(target_paint_error == "#divStatusResponse" || target_paint_error == undefined || target_paint_error == null){
            // quitamos el div interno de errores
            $("#divMsgPrincipal").remove();
            $('#divStatusResponse').html(stE);
        }else{
            if(target_paint_error != undefined && target_paint_error!=null){
                $(target_paint_error).html(stE);
            }
        }
    },
    
    /**
     * configura la respuesta a mostrar en el div
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param {type} msg
     * @returns {String}
     */
    sy_configureDivDetalleStatus : function(msg){
        if(msg == undefined){return;}
        var str = "";
        
        str += "<ul>";
        $.each(msg,function(index,value){
            str += "<li>";
            $.each(value,function(index_2,value_2){
                str += "    <div class='text-darken-2 white-text'>";
                str += "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+index+": "+value_2.message;
                str += "    </div>";
            });
            str += "</li>";
        });
        str += "</ul>";
        return (str);
    },
    
    /**
     * devuelve la url currente del programa actual donde se este ejecutando
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 12-04-2015
     * @param {type} customPath
     * @returns {String}
     */
    sy_pathUrl : function(customPath,bolThis){
        if(customPath!="" && bolThis==undefined){
            var arrUrlClean = customPath.split("/");
            if(arrUrlClean.length==2){
                arrUrlClean = window.location.pathname.split("/");
                var ind_a = 3;
                if(arrUrlClean[2] == 'app'){
                    ind_a = 2;
                }
                return arrUrlClean[ind_a]+'/'+arrUrlClean[(ind_a+1)]+'/'+arrUrlClean[(ind_a+2)]+"/"+customPath;
            }else{
                return 'app/'+customPath;
            }
        }
    },
    
    /**
     * set el data table de la grilla
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_At 19-04-2015
     * @param {type} args
     * @returns {undefined}
     */
    sy_setDataTable : function(args){
        args = jQuery.extend({
            data:[],
            createTable:true,
            id:"dtGrilla",
            columns:[],
            columnsCustom:[],
            actions:{},
            bttnPlus:{
                visible:true
            },
            bttnList:{
                visible:true
            },
            aaSorting: [],
            hiddenColumns : [],
            bttnNotRows:true,
            footerTotal:[]
        }, args);
        
        var objDt = null;
        if(args.data != null && args.data != undefined && args.data.length>0){
            /**validamos la fila de columnas*/
            if(args.columns.length>0){
                if(args.columns.length != Object.keys(args.data[0]).length){
                    alert('La cantidad de columnas no coincide con las columnas de los datos');
                    return;
                }
            }
            
            /**creamos el esqueleto de la tabla*/
            if(args.createTable){
                $(args.target).html($.sy_getTableDataTable(args.id,args.footerTotal,args.columns));
                /**agregamos las columans al thead*/
                if(args.columns.length>0){
                    $('#thead-'+args.id).html($.sy_getHtmlColTh(args.columns,false,args.actions));
                }else{
                    $('#thead-'+args.id).html($.sy_getHtmlColTh(args.data[0],true,args.actions));
                }
            }else{
                
            }
            /**option de  datatable*/
            var optJson = {
                data:(args.data),
                columns : (args.data.length>0?$.sy_getColTh(args.data[0],args.actions,args.columnsCustom):[]),
                columnDefs: [ {
                    targets: "_all",
                    createdCell: function (td, cellData, rowData, row, col) {
                        // verificamos truncate
                        if(args.actions.truncate != null && $.inArray(col,args.actions.truncate)>-1){
                            var arrIc = null;
                            if(args.actions.truncate_icons != undefined && args.actions.truncate_icons != null){
                                arrIc = args.actions.truncate_icons;
                            }
                            $.sy_setEventTruncate(td,cellData,col,arrIc);
                        }
                        
                        // verificamos bttn d actions
                        if($(td).children().attr('data-syslab')){
                            $.sy_setEventInCell($(td).children(),args.actions,{target:$(td).children(),rowData:rowData,row:row,col:col,dt:$('#tbl-'+args.id).dataTable()});
                        }
                        // verificamos si hay configuracion personalizada
                        if(typeof (cellData) == 'object'){
                            $.sy_setConfigObj(td,cellData, row, col);
                        }
                        // verificamos si hay eventos desde javascript
                        if(args.actions[col] != undefined){
                            $.sy_setEventInCell($(td),args.actions,{target:$(td),rowData:rowData,row:row,col:col,dt:$('#tbl-'+args.id).dataTable(),cellData:cellData});
                        }   
                    }
                }],
                "sDom": (args.bttnPlus.visible!=null&&args.bttnPlus.visible==true?'<" row-fluid"<"#divPlusDtTmp-'+args.id+'">lf>tip':'lftip'),
                argsCustom:args,
                aaSorting: [],
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "zeroRecords": "No hay registros encontrados",
                    "info": "Mostrando pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros para mostrar",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search":"Buscar",
                    
                    "paginate":{
                        "first":"Primero",
                        "last":"Ultimo",
                        "next":"Siguiente",
                        "previous":"Anterior"
                    }
                },
                "footerCallback":(args.footerTotal.length>0?$.sy_getCallBackTotal(args.footerTotal):null)
            };
            /**creamos el data table*/
            objDt = $('#tbl-'+args.id).dataTable(optJson);
            // hide columns args
            for(var k=0;k<args.hiddenColumns.length;k++){
                objDt.fnSetColumnVis(args.hiddenColumns[k], false,false);
            }
            /**evento resize*/
            $(window).on("resize", function () {
                setTimeout(function(){
                    for(var k=0;k<args.hiddenColumns.length;k++){
                        objDt.fnSetColumnVis(args.hiddenColumns[k], false,false);
                    }
                },200);
            });
            // creamos el html del div navegacion
            $('#divPlusDtTmp-'+args.id).after(
                /*'<span class="dataTables_length">'+
                '   <div id="divOcultoMenu" class=" navbar-left sidebar-collapse hide-on-large-only" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" style="float:left!important;margin-bottom: 0px !important;margin-right: 0px !important;margin-top: 0px !important;padding: 0px 0px; !important">'+                
                '   </div>'+
                '</span>'+*/
                '<span class="dataTables_length collapse navbar-collapse">'+
                '   <div id="divVisibleMenu-'+args.id+'" class="nav navbar-nav navbar-left">'+
                '   </div>'+
                '</span>'
            );
            /**reemplzamos el tmp*/
            if(args.bttnPlus!= null && args.bttnPlus.visible!=undefined && args.bttnPlus.visible==true){
               $.sy_getBttnPlus(args.id,'teal');
            }
            if(args.bttnList!= null && args.bttnList.visible!=undefined && args.bttnList.visible==true){
               $.sy_getBttnList(args.id,'teal');
            }
            /*event responsive*/
            /*objDt.on( 'click', 'tr.child li a', function () {
                console.log(this);
            });*/
        }else{
            var html_add = '';
            if(args.bttnNotRows == true){
                html_add = '<a id="bttnPlusS-dtGrilla" href="create" class="btn white waves-effect waves-light black-text">Crear registro <i class="material-icons left">note_add</i></a>';
            }
            $(args.target).html(
                "<div class='card-alert card orange darken-3'>"+
                "   <div class='card-content white-text'>"+
                "      <span>No hay datos para mostrar "+html_add+"</span>"+
                "   </div>"+
                "</div>"
            );
        }
        
        return objDt;
    },
    
    /**
     * get el callback en footer
     * 
     * @param {type} footerTotal
     * @returns {Function}
     */
    sy_getCallBackTotal : function(footerTotal){
        return function ( row, data, start, end, display ) {
            
            var api = this.api(), data;
            
            // recorremos los totala footer
            for(var k=0;k<footerTotal.length;k++){
                
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                    .column( footerTotal[k][0] )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
 
                // Total over this page
                pageTotal = api
                    .column( footerTotal[k][0], { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                // Update footer
                $( api.column( footerTotal[k][0] ).footer() ).html(
                    footerTotal[k][2]+$.sy_number_format(pageTotal) +' ( '+footerTotal[k][2]+ $.sy_number_format(total) +' total)'
                );
            } // fin for footer total
        }
    },
    
    /**
     * agrega parametros personzalidos al row o la col
     * 
     * @param {type} td
     * @param {type} cellData
     * @returns {undefined}
     */
    sy_setConfigObj : function(td,cellData, row, col){
        if(cellData!=null){
            $.each(cellData,function(key,value){
                if(key == 'row'){
                    $.each(value,function(key_2,value_2){
                        if(key_2 == 'style'){
                            $.each(value_2,function(key_3,value_3){
                                $(td).parent().css(key_3,value_3);
                            });
                        }
                        if(key_2 == 'value'){
                            $(td).html(value_2);
                        }
                    });
                }
                if(key == 'col'){
                    if(col == value.number){
                        $.each(value.style,function(key_3,value_3){
                            $(td).css(key_3,value_3);
                        });
                        $(td).html(value.value);
                    }
                }
            });
        }
    },
    
    /**
     * 
     * @param {type} objRpl
     * @returns {undefined}
     */
    sy_getBttnPlus : function(idDt,classLocation){
        $('#bttnPlusS-'+idDt).remove();
        $('#bttnPlusH-'+idDt).remove();
        $('#divVisibleMenu-'+idDt).append("<span class='dataTables_length'><a id='bttnPlusS-"+idDt+"' href='create' class='btn "+classLocation+" waves-effect waves-light'>Crear <i class='material-icons left'>note_add</i></a><span> &nbsp;&nbsp;&nbsp; </span>");
        $('#divOcultoMenu').append("<span class='dataTables_length'><a id='bttnPlusH-"+idDt+"'  href='create' class='btn btn-"+classLocation+" btn-xs'><i class='material-icons left'>note_add</i></a><span> &nbsp;&nbsp;&nbsp; </span>");
        //objRpl.remove();
        // set evento click
        $("#bttnPlusS-"+idDt+",#bttnPlusH-"+idDt).click(function(){
            var arrUrl = window.location.pathname.split("/");
            var ind_a = 3;
            if(arrUrl[2] == 'app'){
                ind_a = 2;
            }
            if(arrUrl.length == 6){
                location.href = arrUrl[5]+"/create";
            }else{
                if(arrUrl.length == 7){
                    location.href = "create";
                }
            }
        });
    },
    
    /**
     * se encarga d pinta el bbtn d listar
     * 
     * @param {type} objRpl
     * @returns {undefined}
     */
    sy_getBttnList : function(idDt,classLocation){
        $('#bttnListS-'+idDt).remove();
        $('#bttnListH-'+idDt).remove();
        $('#divVisibleMenu-'+idDt).append("<span class='dataTables_length'><a id='bttnListS-"+idDt+"' href='' class='btn "+classLocation+" waves-effect waves-light'>Listar <i class='material-icons left'>list</i></a><span> &nbsp;&nbsp;&nbsp; </span>");
        $('#divOcultoMenu').append("<span class='dataTables_length'><a id='bttnListH-"+idDt+"'  href='' class='btn btn-"+classLocation+" btn-xs'><i class='material-icons left'>list</i></a><span> &nbsp;&nbsp;&nbsp; </span>");
        //objRpl.remove();
        // set evento click
        $("#bttnListS-"+idDt+",#bttnListH-"+idDt).click(function(){
            var arrUrl = window.location.pathname.split("/");
            var ind_a = 3;
            if(arrUrl[2] == 'app'){
                ind_a = 2;
            }
            location.href = arrUrl[6];
        });
    },
    
    
    /**
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param {type} arrColumns
     * @returns {utilitiesAnonym$0.sy_getColumnsCustom.arrColCustom}
     */
    sy_getColumnsCustom : function(arrColumns){
        var arrColCustom = [];
        $.each(arrColumns,function(index,value){
            arrColCustom.push({data:arrColumns[index]});
        });
        return arrColCustom;
    },
    
    /**
     * retorna las columnas para una tabla
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 19-04-2015
     * @param {type} row
     * @returns {undefined}
     */
    sy_getColTh : function(row,actions,colCustom){
        var colH = [];
        var contC = 0;
        
        /**verificamos si existen actions para al inicio de las colums*/
        if(Object.keys(actions).length > 0){
            $.each(actions,function(index,value){
                if(value.position != null && value.position != undefined){
                    /**verificamos*/
                    if(value.position == "first"){
                        colH = $.sy_setColPushEvent(colH,value,index);
                        contC ++;
                    }
                }
            });
        }
        
        $.each(row,function(index,value){
            var argsExtra = "{";
            if(colCustom[contC] != undefined){
                argsExtra += '"data":"'+index+'",';
                // recorremos los extras
                $.each(colCustom[contC],function(indexArgs,valueArgs){
                    argsExtra += '"'+indexArgs+'":"'+valueArgs+'",';
                });
                argsExtra = argsExtra.substring(0,argsExtra.length-1);
                argsExtra = argsExtra +"}";
                colH.push(JSON.parse(argsExtra));
            }else{
                colH.push({data:index});
            }
            contC ++;
        });
        
        /**verificamos si existen actions para el final de las colums*/
        if(Object.keys(actions).length > 0){
            $.each(actions,function(index,value){
                if(value.position != null && value.position != undefined){
                    /**verificamos*/
                    if(value.position == "last"){
                        colH = $.sy_setColPushEvent(colH,value,index);
                    }
                }
            });
        }
        
        return colH;
    },
    
    /**
     * set los datos de la columna adicional
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 95-04-2015
     * @param {type} colH
     * @param {type} value
     * @returns {unresolved}
     */
    sy_setColPushEvent : function(colH,value,typeAct){
        colH.push({
            data:null,
            bSortable: false,
            className: "center",
            render:function(data, type, row,meta){
                /*set eventos*/
                if(type === "display"){
                    return '<a href="javascript:void(0)" data-syslab="'+typeAct+'"><i class="glyphicon '+value.icon+' text-capitalize" style="font-size: 22px;" title="'+(value.title!=undefined?value.title:'')+'"></i></a>';
                }else{
                    return "";
                }
            }
        });
        return colH;
    },
    
    /**
     * 
     * @param {type} objTarget
     * @param {type} actions
     * @param {type} params
     * @returns {undefined}
     */
    sy_setEventInCell : function(objTarget,actions,params){
        if(actions != null && actions != undefined){
            $.each(actions,function(index,value){
                // buscamos el evento
                if(params.col == index){
                    // recorremos los eventos
                    if(value._events!=null && value._events!=undefined){
                        // verificamos si hay q poner el evento
                        if($(objTarget).html() != ""){
                            $.each(value._events,function(index_2,value_2){
                                //set eventos al obj
                                switch(index_2){
                                    case "_icon":
                                        var t = typeof (value_2);
                                        if(t != 'object'){
                                            $(objTarget).attr("align","center").attr("style","text-align:center").html("<a class='syslab_a_click' style='cursor:pointer;'><i class='material-icons' style='color:blue;'>"+value_2+"</i></a>");
                                        }else{
                                            $(objTarget).attr("align","center").attr("style","text-align:center").html("<a class='syslab_a_click' style='cursor:pointer;'>"+(value_2.length==2?params.cellData:value_2[2])+"&nbsp;</span><i class='material-icons' style='color:blue;'>"+value_2[0]+"</i></a>");
                                        }
                                        break;
                                    case "_click":
                                        $(objTarget).children().click(function(){
                                            value_2(params);
                                        });
                                        break;
                                    case "_validate":
                                        if(value_2.visible == false){
                                            var arrSp = value_2.cond[0].split("$");
                                            var contpar = 0;
                                            $.each(params.rowData,function(index_par,value_par){
                                                if(contpar == arrSp[0]){
                                                    // evaluamos condicion
                                                    if(arrSp[3] == 'STRING'){
                                                        value_par = "'"+value_par+"'";
                                                        arrSp[2] = "'"+arrSp[2]+"'";
                                                        if(eval(eval("value_par+arrSp[1]+arrSp[2]"))){
                                                            $(objTarget).children().hide()
                                                        }
                                                    }else{
                                                        if(eval(eval("value_par+arrSp[1]+arrSp[2]"))){
                                                            $(objTarget).children().hide()
                                                        }
                                                    }
                                                    
                                                }
                                                contpar ++;
                                            });
                                        }else{
                                            $(objTarget).show();
                                        }
                                        break;
                                }
                            });
                        }
                    }
                }
            });
        }
    },
    
    /**
     * 
     * @param {type} objTarget
     * @param {type} cellHtml
     * @returns {undefined}
     */
    sy_setEventTruncate : function(objTarget,cellHtml,indexrow,arrIc){
        if(cellHtml.length > 30){
            var strH = '<a class="aDialogTruncate" data-syslab="'+indexrow+'" data-syslab-text="'+cellHtml+'" href="javascript:void(0)">'+cellHtml.substring(0,30)+'...</a>';
            if($.inArray(indexrow,arrIc)>-1){
                strH = '<a class="aDialogTruncate" data-syslab="'+indexrow+'" data-syslab-text="'+cellHtml+'" href="javascript:void(0)"><i class="fa fa-search"></i></a>';
            }
            $(objTarget).html(strH).attr('align','center');
            $(objTarget).children().click(function(){
                $.sy_alertDialog({
                    message:cellHtml,
                    tittle:'Informaci&oacute;n'
                });
            });
        }
    },
    
    
    /**
     * crea el htm de la fila del head
     * 
     * @author john jairo cortes garcia <john.cortes@yslab.so>
     * @created_at 19-04-2015
     * @param {type} row
     * @returns {String} 
     */
    sy_getHtmlColTh : function(row,bolC,actions){
        var r = {
            edit:{
                icon:"default",
                position:"last"
            }
        };
        var colH = "";
        colH += "<tr>";
        /**verificamos si existen actions para el inicio de las colums*/
        if(Object.keys(actions).length > 0){
            $.each(actions,function(index,value){
                if(value.position != null && value.position != undefined){
                    /**verificamos*/
                    if(value.position == "first"){
                        colH += "<th>&nbsp;</th>";
                    }
                }
            });
        }
        $.each(row,function(index,value){
            colH += "<th class='center'>"+(bolC?index:value)+"</th>";
        });
        /**verificamos si existen actions para el final de las colums*/
        if(Object.keys(actions).length > 0){
            $.each(actions,function(index,value){
                if(value.position != null && value.position != undefined){
                    /**verificamos*/
                    if(value.position == "last"){
                        colH += "<th>&nbsp;</th>";
                    }
                }
            });
        }
        
        colH += "</tr>";
        
        return colH;
    },
    
    /**
     * crea el esqueleto de la tabla
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @creaed_at 19-04-2015
     * @param {type} id
     * @returns {undefined}
     */
    sy_getTableDataTable : function(id,footer,columns){
        var str = "";
        str += "<table id='tbl-"+id+"' class='display responsive-table nowrap' cellpadding='0' cellspacing='0' border='0' width='100%'>";
        str += "    <thead id='thead-"+id+"'>";
        str += "    </thead>";
        if(footer.length>0){
            str += "<tfoot>";
            str += "    <tr>";
            if(footer.length==1){
                str += "        <th colspan='"+footer[0][0]+"' style='text-align:right'>Total:</th>";
                str += "        <th colspan='"+footer[0][1]+"' style='text-align:left'></th>";
            }else{
                for(var k=0;k<columns.length;k++){
                    var cols = '';
                    if(k == columns.length-1){
                        cols = 'colspan="2"';
                    }
                    str += "    <th style='text-align:center' "+cols+"></th>";
                }
            }
            
            str += "    </tr>";
            str += "</tfoot>";
        }
        str += "</table>";
        
        return str;
    },
    
    /**
     * 
     * @param {type} obj
     * @returns {String}
     */
    sy_stringify : function (obj) {
        var t = typeof (obj);
        if (t != "object" || obj === null) {
            // simple data type
            if (t == "string") obj = '"'+obj+'"';
            return String(obj);
        }
        else {
            // recurse array or object
            var n, v, json = [], arr = (obj && obj.constructor == Array);
            for (n in obj) {
                v = obj[n]; t = typeof(v);
                if (t == "string") v = '"'+v+'"';
                else if (t == "object" && v !== null) v = JSON.stringify(v);
                json.push((arr ? "" : '"' + n + '":') + String(v));
            }
            return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
        }
    },
    
    /**
     * 
     * @param {type} str
     * @returns {String}
     */
    sy_addslashes : function(str) {
        return (str + '')
        .replace(/[\\"']/g, '\\$&')
        .replace(/\u0000/g, '\\0');
    },
    
    /**
     * 
     * @param {type} string
     * @param {type} quote_style
     * @param {type} charset
     * @param {type} double_encode
     * @returns {unresolved}
     */
    sy_htmlspecialchars : function(string, quote_style, charset, double_encode) {
        var optTemp = 0,
          i = 0,
          noquotes = false;
        if (typeof quote_style === 'undefined' || quote_style === null) {
          quote_style = 2;
        }
        string = string.toString();
        if (double_encode !== false) { // Put this first to avoid double-encoding
          string = string.replace(/&/g, '&amp;');
        }
        string = string.replace(/</g, '&lt;')
          .replace(/>/g, '&gt;');

        var OPTS = {
          'ENT_NOQUOTES': 0,
          'ENT_HTML_QUOTE_SINGLE': 1,
          'ENT_HTML_QUOTE_DOUBLE': 2,
          'ENT_COMPAT': 2,
          'ENT_QUOTES': 3,
          'ENT_IGNORE': 4
        };
        if (quote_style === 0) {
          noquotes = true;
        }
        if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
          quote_style = [].concat(quote_style);
          for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
            if (OPTS[quote_style[i]] === 0) {
              noquotes = true;
            } else if (OPTS[quote_style[i]]) {
              optTemp = optTemp | OPTS[quote_style[i]];
            }
          }
          quote_style = optTemp;
        }
        if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
          string = string.replace(/'/g, '&#039;');
        }
        if (!noquotes) {
          string = string.replace(/"/g, '&quot;');
        }

        return string;
    },
    
    /**
     * delete row de dtatatable
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 03-05-2015
     * @param {type} params
     * @returns {undefined}
     */
    sy_removeRowDt : function(params){
        var parentEls = $( params.target ).parents()
                        .map(function() {
                            if(this.tagName=="TR")
                                return this;
                        })
                        .get();
        if($(parentEls[0]).hasClass('child')){
            params.dt.DataTable().row($(parentEls[0]).prev()).remove().draw();
        }
        params.dt.DataTable().row($(parentEls[0])).remove().draw();
    },
    
    /**
     * limpia los form q se le indiquen o busca todos los del body y los resetea
     * 
     * @param {type} params
     * @returns {undefined}
     */
    sy_clean_form : function(params){
        if(params != undefined && params.id && params.div == null){
            document.getElementById(params.id).reset();
        }else{
            if(params != undefined && params.id && params.div == true){
                $(params.id).find(':input,select,textarea').each(function(key,value){
                    $(this).val('');
                });
            }else{
                // buscamos el formulario visible del sistema y los limpiamos
                $('body').find('form').each(function(){
                    document.getElementById($(this).prop('id')).reset();
                });
            }
        }
    },
    
    sy_setMenu : function(module,accion){
        $('#'+module).addClass('active');
        $('#'+accion).addClass('active');
    },
    
    sy_getDayString : function(day){
        switch(parseInt(day)){
            case 0: return "Domingo";
            case 1: return "Lunes";
            case 2: return "Martes";
            case 3: return "Miercoles";
            case 4: return "Jueves";
            case 5: return "Viernes";
            case 6: return "Sabado";
        }
    },
    
    /**
     * 
     * @param {type} obj
     * @param {type} objModal
     * @param {type} fnPre
     * @returns {undefined}
     */
    sy_event_modal_dialog : function(obj,objModal,fnPre){
        $(obj).click(function(){
            $(objModal).modal('toggle');
            if(fnPre != undefined && fnPre !=null){
                fnPre();
            }
        });
    },
    
    sy_get_id_token_update : function(){
        return $('#_token_update').val().split("$")[1];
    },
    
    /**
     * 
     * @param {type} error
     * @returns {undefined}
     */
    sys_error_handler : function(error){
        console.log("Error name: >> "+error.name);
        console.log("Error message: >> "+error.message);
        console.log(error);
    },
    
    /**
     * 
     * @param {type} number
     * @param {type} decimals
     * @param {type} decPoint
     * @param {type} thousandsSep
     * @returns {unresolved}
     */
    sy_number_format : function(number, decimals, decPoint, thousandsSep){
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''
        var toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec)
          return '' + (Math.round(n * k) / k)
            .toFixed(prec)
        }
        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || ''
          s[1] += new Array(prec - s[1].length + 1).join('0')
        }
        return s.join(dec)
    },
   
   
});
// incluimos utilities dialog
document.write("<script type='text/javascript' src='"+public_path+"js/_helpers/utilities/js/utilities_dialog.js'></script>");
// incluimos utilities crud
document.write("<script type='text/javascript' src='"+public_path+"js/_helpers/utilities/js/utilities_crud.js'></script>");