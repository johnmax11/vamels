var objPrimary = (function(){
    
    // object validate
    var validateF = function(){};
    
    // object envents
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            
            // change users si existe
            if(document.getElementById("selUsuarios")){
                
                // change users
                this.setEventChangeUsers();
            }
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            (new ajaxF()).loadDataGridPrincipal();
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setEventChangeUsers = function(){
            $("#selUsuarios").change(function(){
                (new ajaxF()).loadDataGridPrincipal();
            });
        }; // END function
    };
    
    // objects ajax
    var ajaxF = function(){
        
        /**
        * 
        * @returns {undefined}
        */
        this.loadDataGridPrincipal = function(){
            if(document.getElementById("selUsuarios") && $("#selUsuarios").val() == ""){
                $("#divGrillaPrincipal").html("");
                return;
            }
            $.sy_ajaxFrm({
               url:$.sy_pathUrl('collaborators/witnesses/read/data'),
               preload:false,
               alertError:false,
               data:{
                   municipio_id:(document.getElementById("selUsuarios")?$("#selUsuarios").val():null)
               },
               params:{processF:new processF()},
               success: function(params,response){
                   params.processF.setDataTableGrillaPrincipal(response.msg.rows);
               }
            });
        }; // END function
    
        /**
        * 
        * @param {type} idsale
        * @returns {undefined}
        */
        this.getDetailsProductById = function(idsale){
            $.sy_ajaxFrm({
               url:$.sy_pathUrl('collaborators/witnesses/read/data/'+idsale),
               preload:false,
               alertError:false,
               success: function(params,response){
                   $('#body-customAD').html(response.msg.html);
               }
            });
        }; // END function
        
        /**
         * remover registro por id
         * 
         * @param {type} id
         * @returns {undefined}
         */
        this.delQuitarRegistro = function(id){
            $.sy_ajaxFrm({
               url:$.sy_pathUrl('collaborators/witnesses/delete/'+id),
               params:{ajaxF:(new ajaxF())},
               success: function(params,response){
                   if(response.error === false && response.type_msg == "success"){
                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast('Registro eliminado con exito', 3000);
                        
                        // cargamos d nuevo los registros
                        params.ajaxF.loadDataGridPrincipal();
                    }
               }
            });
        }; // END function
        
        /**
         * 
         * @param {type} id
         * @returns {undefined}
         */
        this.updateNombresApellidosJurado = function(id, type){
            var data = null;
            if(type == 1){
                var data = {
                   p_app:$("#txtModPApellido").val(),
                   s_app:$("#txtModSApellido").val(),
                   p_nom:$("#txtModPNombre").val(),
                   s_nom:$("#txtModSNombre").val(),
				   cc:$("#txtModCedula").val(),
                   action:"validate_names"
               };
            }else{
                var opt_c = null;
                if($("#asignNo").is(":checked")){
                    opt_c = "N";
                }
                if($("#asignSi").is(":checked")){
                    opt_c = "Y";
                }
                var data = {
                    opt_c:opt_c,
                    action:"validate_jurado"
               };
            }
            $.sy_ajaxFrm({
               url:$.sy_pathUrl('collaborators/witnesses/update/data/'+id),
               params:{ajaxF:(new ajaxF())},
               preload:false,
               alertError:false,
               data:data,
               success: function(params,response){
                   if(response.error === false && response.type_msg == "success"){
                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast('Registro actualizado con exito', 3000);
                        
                        // cargamos d nuevo los registros
                        params.ajaxF.loadDataGridPrincipal();
                    }
               }
            });
        }; // END function
    
    }; // END OBJECT AJAX
    
    // objetc process
    var processF = function(){
        
        /**
        * 
        * @param {type} rows
        * @returns {undefined}
        */
        this.setDataTableGrillaPrincipal = function(rows){
           $.sy_setDataTable({
               data:rows,
               target:"#divGrillaPrincipal",
               columns:[
                   '&nbsp;',
                   '&nbsp;',
                   'id',
                   'CC',
                   '<span style="font-size:8pt;">Apellidos</span>',
                   '<span style="font-size:8pt;">Nombres</span>',
                   '<span style="font-size:8pt;">Teléfono</span>',
                   '<span style="font-size:8pt;">Detalle</span>',
                   'Img',
                   '<span style="font-size:8pt;">Creado por</span>',
               ],
               columnsCustom:{
                   0:{className:"center"},
                   1:{className:"center"},
                   3:{className:"center"},
               },
               actions:{
                   truncate:[4,5],
                    0:{
                       _events:{
                           _icon:'search',
                           _click:function(params){
                               location.href = "update/"+params.rowData.id;
                           }
                       }
                    },
                    1:{
                       _events:{
                           _icon:'delete',
                           _click:function(params){
                               $.sy_confirmDialog({
                                    "message":"Esta seguro de remover este registro?<br/><br/>Recuerde que esta acci&oacute;n NO SE PUEDE DESHACER",
                                    yes:function(){
                                        (new ajaxF()).delQuitarRegistro(params.rowData.id);
                                    },
                                });
                           }
                       }
                    }
               },
           });
        }; // END function
    
        /**
         * validar datos de cedula y jurado
         * 
         * @param {type} id
         * @param {type} obj
         * @returns {undefined}
         */
        this.getModalValidate = function(id, obj){
            var cc = $(obj).html();
            var arr_n = $(obj).next().html();
            var p_a = arr_n.split(";")[0];
            var s_a = arr_n.split(";")[1];
            var p_n = arr_n.split(";")[2];
            var s_n = arr_n.split(";")[3];
            var jurado = arr_n.split(";")[4];
            
            // get img html
            var h_img = $(obj).parent().next().next().next().next().next().html();
                h_img = h_img.replace(/<br>/gi, "");
            
            var strH = "";
                strH += "<div>";
                strH += "   <div class='row'>";
                strH += "       <div class='input-field col s12 m3'>";
                strH += "           <div style='font-familiy:10pt !important;'>"+h_img+"</div>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m3' style='font-familiy:10pt;'>";
                strH += "           Jurado: <a href='https://elecciones2019.infovotantes.co/#/home' target='_blank'>Verificar</a>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m1' style='font-familiy:8pt;'>";
                strH += "           Asig:";
                strH += "       </div>";
                strH += "       <div class='col s12 m3' style='font-familiy:8pt;'>";
                var jurY = "";
                var jurN = "";
                if(jurado != null){
                    if(jurado == "Y"){
                        jurY = "checked";
                    }else{
                        if(jurado == "N"){
                            jurN = "checked";
                        }
                    }
                }
                strH += '           <input name="radASignado" type="radio" '+jurN+' id="asignNo" class="with-gap" onclick="(new objPrimary.main()).updateNombresApellidosJurado('+id+', 2);"/>';
                strH += '           <label for="asignNo">No</label>';
                strH += '           <input name="radASignado" type="radio" '+jurY+' id="asignSi" class="with-gap"onclick="(new objPrimary.main()).updateNombresApellidosJurado('+id+', 2);"/>';
                strH += '           <label for="asignSi">Si</label>';
                strH += "       </div>";
                strH += "   </div>";
                strH += "   <div class='row'>";
				strH += "       <div class='input-field col s12 m3'>";
				strH += "           <input type='text' id='txtModCedula' value='"+cc+"'>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m2'>";
                strH += "           <input type='text' id='txtModPApellido' value='"+p_a+"'>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m2'>";
                strH += "           <input type='text' id='txtModSApellido' value='"+s_a+"'>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m2'>";
                strH += "           <input type='text' id='txtModPNombre' value='"+p_n+"'>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m2'>";
                strH += "           <input type='text' id='txtModSNombre' value='"+s_n+"'>";
                strH += "       </div>";
                strH += "       <div class='input-field col s12 m1'>";
                strH += "           <a href='javascript:void(0)' onclick='(new objPrimary.main()).updateNombresApellidosJurado("+id+", 1);'>";
                strH += "               <i class='material-icons prefix'>save</i>";
                strH += "           </a>";
                strH += "       </div>";
                strH += "   </div>";
                strH += "</div>";
            $.sy_confirmDialog({
                tittle:"Validar",
                "message":strH,
                bttnYes:false,
                lblNo:"Cerrar",
            });
        }; // END function
        
    };
    
    // main
    var main = function(){
        this.__construct = function(){
            
            // suscribe events
            (new eventF()).suscribeEvents();
            
            // trigger events
            (new eventF()).triggerEvents();
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.getModalValidate = function(id, object){
            (new processF()).getModalValidate(id, object);
        }; // END function
        
        this.updateNombresApellidosJurado = function(id, type){
            (new ajaxF()).updateNombresApellidosJurado(id, type);
        };
    };
    
    return {
        main:main
    };
})();

// init javascript
$(function(){
    try{(new objPrimary.main()).__construct();}catch(ex){$.sys_error_handler(ex);}
});
