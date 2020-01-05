var objPrimary = (function(){
    
    // object validate
    var validateF = function(){
        
        /**
        * 
        * @returns {undefined}
        */
        this.validateSendData = function(){
           if(!$.sy_validateForm('#frmDatos')){
               return;
           }
           
           // validamos los file
           if(document.getElementById("error-filImagen1")){
               //swal("Problemas con la imagen 1, vuelve a cargarla", "", "warning");
               //return;
           }
           if(document.getElementById("error-filImagen2")){
               //swal("Problemas con la imagen 2, vuelve a cargarla", "", "warning");
               //return;
           }
           
           // enviamos los datos al servidor
           (new ajaxF()).sendData();
        }; // END function
    
    }; // END OBJECT VALIDATE
    
    // objects event
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            // evento guardar
            this.setEventSave();
            // evento cargar imagenes
            this.setEventUploadImg();
            // evento autocomplete place
            this.txtPuestoVotacion();
            // evento verificar num cedula
            this.verificarCedula();
            // evento cambia city
            this.setChangeCity();
        
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            // carga combo city
            (new processF()).setComboCities();
        }; // END function
        
        /**
        * 
        * @returns {undefined}
        */
        this.setEventSave = function(){
           $('#btnGuardar').click(function(){
               (new validateF()).validateSendData();
           });
        }; // END function
        
        /**
        * 
        * @returns {undefined}
        */
        this.setEventUploadImg = function(){
           $('#filImagen1').uploaderFiles({
               url:$.sy_pathUrl("sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric")
           });
           $('#filImagen2').uploaderFiles({
               url:$.sy_pathUrl("sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric")
           });
        }; // END function
    
        /**
        * 
        * @returns {undefined}
        */
        this.txtPuestoVotacion = function(){
            $("#txtPuestoVotacion").devbridgeAutocomplete({
                //lookup: countries,
                serviceUrl:(new ajaxF()).getPlaceByAutoComplete(),
                type:'GET',
                params:{
                    city:$("#selCiudad").val(),
                    dpto:$("#selDepartamento").val()
                    
                },
                noCache:true,
                //callback just to show it's working
                onSelect: function (suggestion) {
                    $("#hdnPuestoVotacion").val(suggestion.id);
                    $("#hdnPuestoVotacionMesas").val(suggestion.tables_count);
                    $("#hdnPuestoVotacionMesasUsed").val(suggestion.used_tables);
                    $("#txtMesaVotacion").prop("disabled",null);
                },
                onSearchStart:function(params){
                    $("#hdnPuestoVotacion, #hdnPuestoVotacionMesas, #hdnPuestoVotacionMesasUsed").val("");
                    params.city = $("#selCiudad").val();
                    $("#txtMesaVotacion").val("").prop("disabled","disabled");
                },
                formatResult:function(data,b,c){
                    var html =  '<div>';
                        html += '   <h6>>> '+data.place_name+'</h6>';
                        html += '   <span style="font-size:6pt;">'+data.cities_name+' / '+data.state_name+'</span><br/>';
                        if(data.comune_name != ""){
                            html += '   <span style="font-size:6pt;font-family:verdana;">Comuna: '+data.comune_number+' - '+data.comune_name+'</span>';
                        }
                        html += '   <i style="font-size:6pt;">Zona: '+data.zone+' - Puesto: '+data.place_number+'</i><br/>';
                        html += '   <i style="font-size:6pt;">Dir: '+data.address+'</i>';
                        html += '</div>';
                    return html;
                },
                minChars:3,
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Sin resultados',
            });
            
        }; // END function
    
        /**
         * evento trigger verificar cedula repetida ingresada
         * 
         * @returns {undefined}
         */
        this.verificarCedula = function(){
            $("#txtNumeroIdentificacion").blur(function(){
                // consultamos en la bd si ya se ha ingresado este numero d cedula
                (new ajaxF()).verifyCedulaDB($(this).val());
            });
        }; // END fucntion
        
        /**
         * 
         * @returns {undefined}
         */
        this.setMesasDialog = function(){
            $("#txtMesaVotacion").click(function(){
                $(this).focusout();
                (new processF()).setDialogMesasVotacion();
            });
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setChangeCity = function(){
            $("#selCiudad").change(function(){
                $("#txtPuestoVotacion, #txtMesaVotacion").val("");
                $("#hdnPuestoVotacion, #hdnPuestoVotacionMesas, #hdnPuestoVotacionMesasUsed").val("");
                $("#txtMesaVotacion").prop("disabled","disabled");
            });
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setChkMesasSelected = function(){
            $("#btnSelectedChk").click(function(){
                (new processF()).setSelectedChkInInput()
            });
        }; // END function
        
    }; // END OBJECT EVENTS
    
    // objects ajax
    var ajaxF = function(){
        
        /**
         * 
         * @returns {unresolved}
         */
        this.getPlaceByAutoComplete = function(){
           return public_path+$.sy_pathUrl('params/places/read/autocomplete');
        }; // END function
    
        /**
         * 
         * @returns {undefined}
         */
        this.sendData = function(){
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('collaborators/witnesses/create/data'),
                data:$('#frmDatos').serialize(),
                success: function(params,response){
                    if(response.error == false){
                        swal("Registro creado correctamente!", ";)", "success");
                        document.getElementById('frmDatos').reset();
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                }
            });
        }; // END function
        
        /**
         * 
         * @param {type} cc
         * @returns {undefined}
         */
        this.verifyCedulaDB = function(cc){
            if(cc == ""){
                return;
            }
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('collaborators/witnesses/read/data'),
                data:{cc:cc},
                alertError:false,
                success: function(params,response){
                    if(response.msg.rows.length == 0){
                        // Materialize.toast(message, displayLength, className, completeCallback);
                        Materialize.toast('Cedula disponible para ingresar! ;)', 3000);
                    }else{
                        $("#txtNumeroIdentificacion").val("");
                        swal("Esta cedula ya se ingreso al sistema", "Ingrese otra!", "warning");
                    }
                }
            });
        }; // END function
    
    }; // END OBJECT AJAX
    
    // object process
    var processF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.setDialogMesasVotacion = function(){
            $.sy_alertDialog({
                message:function(){
                    var strchk = "";
                    var arrMesasUsed = $("#hdnPuestoVotacionMesasUsed").val().split(",");
                    var arrMesasSele = $("#txtMesaVotacion").val().split(",");
                    for(var i=0; i<$("#hdnPuestoVotacionMesas").val() ;i++){
                        if(i>0){
                            strchk += "&nbsp;&nbsp;&nbsp;";
                        }
                        
                        var cls_chk = "";
                        if($.inArray((parseInt(i)+1).toString(),arrMesasUsed) != -1){
                            cls_chk = "disabled='disabled'";
                        }
                        if($.inArray((parseInt(i)+1).toString(),arrMesasSele) != -1){
                            cls_chk = "checked='checked'";
                        }
                        
                        strchk += '<input type="checkbox" class="filled-in" id="chk-'+((parseInt(i)+1))+'" value="'+((parseInt(i)+1))+'" '+cls_chk+'/>';
                        strchk += '<label for="chk-'+((parseInt(i)+1))+'">Mesa #'+(parseInt(i)+1)+'</label>';
                    }
                    return  ""+
                            "<div>"+
                            "   "+strchk+
                            "</div>"+
                            "<div>"+
                            '   <button id="btnSelectedChk" type="button" class="btn btn-custom blue">Finalizar selección</button>'+
                            "</div>"+
                            "";
                },
                tittle:"Mesas del puesto"
            });
            
            // set evento finalizar seleccion
            (new eventF()).setChkMesasSelected();
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setSelectedChkInInput = function(){
            var chk = "";
            for(var i=1; true ;i++){
                if(!document.getElementById("chk-"+i)){
                    break;
                }
                if($(document.getElementById("chk-"+i)).is(":checked")){
                    chk += $(document.getElementById("chk-"+i)).val()+",";
                }
            }
            // set chk selected
            $("#txtMesaVotacion").val((chk).substring(0,(chk).length-1)).change();
            // close dialog
            $(document.getElementById('myModalAlertDialog_customAD')).modal("close");
        }; // END function
        
        /**
         * 
         * @param {type} iduser
         * @returns {String}
         */
        this.getIdCities = function(iduser){
            if(
				iduser == 1  || iduser == 60 ||
				iduser == 61 || iduser == 62 ||
				iduser == 63 || iduser == 64 ||
				iduser == 65 || iduser == 66 ||
				iduser == 67 || iduser == 68 ||
				iduser == 69 || iduser == 70 ||
				iduser == 71 || iduser == 72 ||
				iduser == 73 || iduser == 74 ||
				iduser == 75
			){
                var strC = "";
                strC += '<option value="" selected>Seleccione...</option>';
                strC += '<option value="4">Alcalá</option>';
                strC += '<option value="7">Andalucía</option>';
                strC += '<option value="10">Ansermanuevo</option>';
                strC += '<option value="13">Argelia</option>';
                strC += '<option value="16">Bolívar</option>';
                strC += '<option value="19">Buenaventura</option>';
                strC += '<option value="22">Buga</option>';
                strC += '<option value="25">Bugalagrande</option>';
                strC += '<option value="28">Caicedonia</option>';
                strC += '<option value="1">Cali</option>';
                strC += '<option value="40">Calima - El Darién</option>';
                strC += '<option value="31">Candelaria</option>';
                strC += '<option value="34">Cartago</option>';
                strC += '<option value="37">Dagua</option>';
                strC += '<option value="43">El Águila</option>';
                strC += '<option value="46">El Cairo</option>';
                strC += '<option value="49">El Cerrito</option>';
                strC += '<option value="52">El Dovio</option>';
                strC += '<option value="55">Florida</option>';
                strC += '<option value="58">Ginebra</option>';
                strC += '<option value="61">Guacarí</option>';
                strC += '<option value="64">Jamundí</option>';
                strC += '<option value="67">La Cumbre</option>';
                strC += '<option value="70">La Unión</option>';
                strC += '<option value="73">La Victoria</option>';
                strC += '<option value="79">Palmira</option>';
                strC += '<option value="82">Pradera</option>';
                strC += '<option value="85">Restrepo</option>';
                strC += '<option value="88">Riofrío</option>';
                strC += '<option value="91">Roldanillo</option>';
                strC += '<option value="94">San Pedro</option>';
                strC += '<option value="97">Sevilla</option>';
                strC += '<option value="100">Toro</option>';
                strC += '<option value="103">Trujillo</option>';
                strC += '<option value="106">Tuluá</option>';
                strC += '<option value="109">Ulloa</option>';
                strC += '<option value="112">Versalles</option>';
                strC += '<option value="115">Vijes</option>';
                strC += '<option value="118">Yotoco</option>';
                strC += '<option value="121">Yumbo</option>';
                strC += '<option value="124">Zarzal</option>';
                
                return strC;
            }
            
            switch(parseInt(iduser)){
                case 2: return '<option value="4">Alcalá</option>';
                case 3: return '<option value="7">Andalucía</option>';
                case 4: return '<option value="10">Ansermanuevo</option>';
                case 5: return '<option value="13">Argelia</option>';
                case 6: return '<option value="16">Bolívar</option>';
                case 7: 
                case 54:
				case 59:
                    return '<option value="19">Buenaventura</option>';
                case 8: return '<option value="22">Buga</option>';
                case 9: return '<option value="25">Bugalagrande</option>';
                case 10: return '<option value="28">Caicedonia</option>';
                case 11:
                case 12:
                case 13:
                case 14:
                case 55:
                case 56:
				case 76:
				case 77:
				case 78:
				case 79:
				case 80:
				case 81:
				case 82:
				case 83:
				case 84:
				case 85:
				case 86:
				case 87:
				case 88:
				case 89:
				case 90:
				case 91:
				case 92:
                    return '<option value="1">Cali</option>';
                case 15:
                case 16:
                    return '<option value="40">Calima - El Darién</option>';
                case 17: return '<option value="31">Candelaria</option>';
                case 18: return '<option value="34">Cartago</option>';
                case 19: return '<option value="37">Dagua</option>';
                case 20: return '<option value="43">El Águila</option>';
                case 21:
                case 22:
                    return '<option value="46">El Cairo</option>';
                case 23:
                case 24:
                    return '<option value="49">El Cerrito</option>';
                case 25: return '<option value="52">El Dovio</option>';
                case 26: 
                case 27: 
                    return '<option value="55">Florida</option>';
                case 28: return '<option value="58">Ginebra</option>';
                case 29: return '<option value="61">Guacarí</option>';
                case 30: return '<option value="64">Jamundí</option>';
                case 31: return '<option value="67">La Cumbre</option>';
                case 32: return '<option value="70">La Unión</option>';
                case 33: return '<option value="73">La Victoria</option>';
                //case iduser: return '<option value="76">Obando</option>';
                case 34: return '<option value="79">Palmira</option>';
                case 35: return '<option value="82">Pradera</option>';
                case 36: return '<option value="85">Restrepo</option>';
                case 37: return '<option value="88">Riofrío</option>';
                case 38: return '<option value="91">Roldanillo</option>';
                case 39: return '<option value="94">San Pedro</option>';
                case 40:
                    return '<option value="97">Sevilla</option>';
                case 41: return '<option value="100">Toro</option>';
                case 42:
                case 43:
                    return '<option value="103">Trujillo</option>';
                case 44:
                case 45:
                    return '<option value="106">Tuluá</option>';
                case 46: return '<option value="109">Ulloa</option>';
                case 47:
                case 48:
                    return '<option value="112">Versalles</option>';
                case 49:
                case 50:
                    return '<option value="115">Vijes</option>';
                case 51: return '<option value="118">Yotoco</option>';
                case 52: return '<option value="121">Yumbo</option>';
                case 53:
                case 57:
                    return '<option value="124">Zarzal</option>';
				case 93:
					return '<option value="1">Cali</option>'+'<option value="97">Sevilla</option>';
            }
        }; // END function
        
        /**
         * carga combo ciudad
         * 
         * @returns {undefined}
         */
        this.setComboCities = function(){
            $("#selCiudad").html(this.getIdCities($("#hdnIdUs").val()));
            $("#selCiudad").material_select();
        }; // END function
        
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
