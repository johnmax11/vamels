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
            this.setEventChange();
            // evento save
            this.setEventSave();
            
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            
            // evento consultar datos de witnesses
            (new ajaxF()).getDataZonesByIdCity();
            
        }; // END function
        
        /**
        * 
        * @returns {undefined}
        */
        this.setEventSave = function(){
           $('#bttnSave').click(function(){
               (new validateF()).validateSendData();
           });
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.setEventChange = function(){
            // change zona
            $("#selZona").change(function(){
                // cargamos los puestos d la zona
                (new ajaxF()).getDataPuestosByIdZone();
            });
            // change puesto
            $("#selPuesto").change(function(){
                // buscamos las mesas del puesto
                (new ajaxF()).getDataMesasByIdPlace();
            });
        }; // END FUNCTION
        
    }; // END OBJECT EVENTS
    
    // objects ajax
    var ajaxF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.sendData = function(){
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('collaborators/witnesses/update/data/'+$.sy_get_id_token_update()),
                data:$('#frmDatos').serialize(),
                success: function(params,response){
                    if(response.error == false){
                        swal("Registro actualizado correctamente!", ";)", "success");
                        location.reload();
                    }
                }
            });
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.getDataZonesByIdCity = function(){
            $("#selPuesto").html("<option value=''>...</option>");
            $("#selPuesto").material_select();
            
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('consolidate/scrutiny/read/zones'),
                data:{
                    department_id:$("#selDepartamento").val(),
                    cities_id:$("#selMunicipio").val()
                },
                alertError:false,
                success: function(params,response){
                    if(response.msg != null && response.msg.rows != null){
                        $.sy_setCombo({
                            idcombo:"#selZona",
                            datos:response.msg.rows,
                            afterCombo:function(){
                                $("#selZona").material_select();
                            }
                        });
                        
                    }
                }
            }); 
        }; // END FUNCTION
        
        /**
         * cargamos los puestos
         * 
         * @returns {undefined}
         */
        this.getDataPuestosByIdZone = function(){
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('consolidate/scrutiny/read/places'),
                data:{
                    department_id:$("#selDepartamento").val(),
                    cities_id:$("#selMunicipio").val(),
                    zone:$("#selZona").val()
                },
                alertError:false,
                success: function(params,response){
                    if(response.msg != null && response.msg.rows != null){
                        $.sy_setCombo({
                            idcombo:"#selPuesto",
                            datos:response.msg.rows,
                            afterCombo:function(){
                                $("#selPuesto").material_select();
                            }
                        });
                        
                    }
                }
            }); 
        }; // END FUNCTION
        
        /**
         * 
         * @returns {undefined}
         */
        this.getDataMesasByIdPlace = function(){
            $("#divTables").html("");
            $.sy_ajaxFrm({
                url:$.sy_pathUrl('consolidate/scrutiny/read/tables'),
                data:{
                    department_id:$("#selDepartamento").val(),
                    cities_id:$("#selMunicipio").val(),
                    zone:$("#selZona").val(),
                    place:$("#selPuesto").val()
                },
                alertError:false,
                success: function(params,response){
                    if(response.msg != null && response.msg.rows != null){
                        var nrows = parseInt(response.msg.rows[0].tables_count);
                        var nrows_real = (nrows>10?10:nrows);
                        
                        var arrCa = [
                            "U1","U2","U3","U4",
                            "U5","U6","U7","U8",
                            "U9","U10","U11","U12",
                            "U13","U14","U15","U16",
                            "U17","U18","U20","U21",
                        ];
                        
                        var str = "<table>";
                        for(var j=0; j<arrCa.length ;j++){
                            str += "<tr>";
                            var str_aux = "";
                            if(nrows <= 10){
                                var str_aux = "    <td>";
                            }
                            if(nrows > 10){
                                var str_aux = "    <td rowspan=2>";
                            }
                            if(nrows > 20){
                                var str_aux = "    <td rowspan=3>";
                            }
                            if(nrows > 30){
                                var str_aux = "    <td rowspan=4>";
                            }
                            if(nrows > 40){
                                var str_aux = "    <td rowspan=5>";
                            }
                            if(nrows > 50){
                                var str_aux = "    <td rowspan=6>";
                            }
                            if(nrows > 60){
                                var str_aux = "    <td rowspan=7>";
                            }
                            if(nrows > 70){
                                var str_aux = "    <td rowspan=8>";
                            }
                            
                            str += str_aux;
                            str += "        "+arrCa[j];
                            str += "    </td>";
                            
                            for(var i=0; i<10 ;i++){
                                str += "    <td>";
                                str += "        <input id='' type='number' class='' placeholder='"+(i+1)+"'/>";
                                str += "    </td>";
                            }
                            str += "</tr>";
                            
                            if(nrows > 10){
                                nrows_real = (nrows<=20?nrows:20);
                                str += "<tr>";
                                for(var k=i; k<(nrows_real) ;k++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(k+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 20){
                                nrows_real = (nrows<=30?nrows:30);
                                str += "<tr>";
                                for(var m=k; m<(nrows_real) ;m++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(m+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 30){
                                nrows_real = (nrows<=40?nrows:40);
                                str += "<tr>";
                                for(var n=m; n<(nrows_real) ;n++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(n+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 40){
                                nrows_real = (nrows<=50?nrows:50);
                                str += "<tr>";
                                for(var o=n; o<(nrows_real) ;o++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(o+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 50){
                                nrows_real = (nrows<=60?nrows:60);
                                str += "<tr>";
                                for(var p=o; p<(nrows_real) ;p++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(p+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 60){
                                nrows_real = (nrows<=70?nrows:70);
                                str += "<tr>";
                                for(var q=o; q<(nrows_real) ;q++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(q+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            if(nrows > 70){
                                nrows_real = (nrows<=80?nrows:80);
                                str += "<tr>";
                                for(var r=o; r<(nrows_real) ;r++){
                                    str += "    <td>";
                                    str += "        <input id='' type='number' class='' placeholder='"+(r+1)+"'/>";
                                    str += "    </td>";
                                }
                                str += "</tr>";
                            }
                            
                            str += "<tr>";
                            str += "    <td colspan=21>";
                            str += "        <hr/>";
                            str += "        <hr/>";
                            str += "    </td>";
                            str += "</tr>";
                        } // FIN FOR PRINCIUPAL
                        
                        str += "</table>";
                        
                        $("#divTables").html(str);
                    }
                }
            });
        }; // END FUNCTION 
    
    }; // END OBJECT AJAX
    
    // object process
    var processF = function(){
        
        
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
