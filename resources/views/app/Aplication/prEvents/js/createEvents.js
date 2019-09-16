/**validates*/
function validateF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.valCamposGuardarDatos = function(){
        if(!$.sy_validateForm("#frmDatos")){
            return;
        }
        
        // send datos a guardar
        objAjaxF.sendDataSave();
    };
    
}

/**eventos*/
function eventF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventDateTime = function(){
        $('.datepicker').pickadate({
            selectYears: 2
        });
        
        $('.timepicker').pickatime({
            default: 'now', // Set default time: 'now', '1:30AM', '16:30'
            fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
            twelvehour: false, // Use AM/PM or 24-hour format
            donetext: 'OK', // text for done-button
            cleartext: 'Clear', // text for clear-button
            canceltext: 'Cancel', // Text for cancel-button,
            container: undefined, // ex. 'body' will append picker to body
            autoclose: false, // automatic close timepicker
            ampmclickable: true, // make AM PM clickable
            aftershow: function(){} //Function for after opening timepicker
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventGuardarDatos = function(){
        $("#aSetEventSaveData").click(function(){
            objValidateF.valCamposGuardarDatos();
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventUploadImg = function(){
        $('#filImagenThumb').uploaderFiles({
            url:$.sy_pathUrl("sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric"),
            arrExt:['png','jpg']
        });
        $('#filImagenReal').uploaderFiles({
            url:$.sy_pathUrl("sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric"),
            arrExt:['png','jpg']
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventModal = function(){
        $("#gpsMaps").click(function(){
            
            $("#divModalMapa").modal({
                dismissible:false
            });
            $('#divModalMapa').modal("open");
            
            google.maps.event.trigger(map, "resize");
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventCampos = function(){
        
        // titulo
        $("#txtTituloBig").blur(function(){
            $("#txtTitArticulo").val($("#txtTituloBig").val());
            $("#txtTitArticulo").change();
        });
        
        // titulo
        $("#txtLink").blur(function(){
            var url = $("#txtLink").val();
            var url_t = url.substring(url.indexOf("://")+3);
            url = (url.substring(0,url.indexOf("://")+3)+""+url_t.substring(0,url_t.indexOf("/")));
            $("#txtWebsitePagina").val(url);
            $("#txtWebsiteArticulo").val($("#txtLink").val());
            
            $("#txtWebsitePagina").change();
            $("#txtWebsiteArticulo").change();
        });
        
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.eventSendUpdFacebook = function(){
        $("#aSendUpdateUrlFacebook").click(function(){
            objAjaxF.sendDataUpdUrlFacebook();
        });
    };
    
}
/**ajax**/
function ajaxF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.getDataSports = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('aplication/events/read/getdatasports'),
            success: function(params,response){
                if(response.error == false){
                    $.sy_setCombo({
                        idcombo:"#selDeporte",
                        datos:response.msg.rows,
                        afterCombo:function(){
                            $('#selDeporte').material_select();
                        }
                    });
                }
            }
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.sendDataSave = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('aplication/events/create/savedataevent'),
            data:$("#frmDatos").serialize(),
            success: function(params,response){
                if(response.error == false){
                    document.getElementById("frmDatos").reset();
                    // abrimos modal y buscamos informacion
                    $("#divModalFacebook").modal({
                        dismissible:false
                    });
                    $('#divModalFacebook').modal("open");
                    // buscamos la informacion del post facebook
                    objAjaxF.getInfoPostFacebook(response.msg.id_evento);
                }
            }
        });
    };
    
    /**
     * 
     * @param {type} idevent
     * @returns {undefined}
     */
    this.getInfoPostFacebook = function(idevent){
        $("#hdnidevento").val(idevent);
        
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('aplication/events/read/getdataevent'),
            data:{idevent:idevent},
            success: function(params,response){
                if(response.error == false){
                    var str = "";
                    
                    str += "# "+response.msg.rows[0].title_big+"<br/><br/>";
                    str += "** Evento **<br/>";
                    str += response.msg.rows[0].title_short+"<br/><br/>";
                    str += "** Mas informaci&oacute;n **<br/>";
                    str += response.msg.rows[0].link_more_information+"<br/><br/>";
                    str += "** Fecha **<br/>";
                    str += response.msg.rows[0].date_start+" a las: "+response.msg.rows[0].time_start.substring(0,5)+"<br/><br/>";
                    str += "** Lugar **<br/>";
                    str += response.msg.rows[0].stage_name+"<br/>";
                    str += response.msg.rows[0].stage_address+"<br/>";
                    str += response.msg.rows[0].stage_address_2+"<br/><br/>";
                    str += "** Ciudad **<br/>";
                    str += response.msg.rows[0].city+"<br/><br/>";
                    str += "** Valor **<br/>";
                    str += "$ "+response.msg.rows[0].price+"<br/><br/>";
                    str += "==========> Descarga nuestra APP Movil #MiParcheDeportivo y te avisaremos de #EventosDeportivos similares ";
                    str += " https://play.google.com/store/apps/details?id=com.eventosdeportes.mpd.miparchedeportivo";
                    str += " <==========<br/><br/>";
                    str += "#MiParcheDeportivo #ValleDelCauca #Cali #LigasDeportivas";
                    str += " #Deportes #YoAmoElDeporte #YoHagoDeporte #DeporteMiPasion";
                    str += " @indervalleoficial";
                    str += " @SecDeporteCali";
                    str += " #"+response.msg.rows[0].name_sports;
                    
                    $("#divPostFacebook").html(str);
                }
            }
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.sendDataUpdUrlFacebook = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('aplication/events/update/updateeventofacebook'),
            data:{
                idevento:$("#hdnidevento").val(),
                url_facebook:$("#txtUrlFacebook").val()
            },
            beforeSend:function(){
                $('#divModalFacebook').modal("close");
            },
            success: function(params,response){
                if(response.error == false){
                    
                }
            }
        });
    };
    
}
/**process*/
function processF(){
    
    
}
/**main*/
function main(){
    this.__construct = function(){
        objEventF = new eventF();
        objAjaxF = new ajaxF();
        objProcessF = new processF();
        objValidateF = new validateF();
        
        // ini event timepicker
        objEventF.setEventDateTime();
        // get data sports
        objAjaxF.getDataSports();
        // set evento guardar datos
        objEventF.setEventGuardarDatos();
        // event upload
        objEventF.setEventUploadImg();
        // event modal
        objEventF.setEventModal();
        // evento campos iguales
        objEventF.setEventCampos();
        // evento send upd facebook
        objEventF.eventSendUpdFacebook();
    };
}

var objValidateF = null;
var objEventF = null;
var objAjaxF = null;
var objProcessF = null;

$(document).ready(function(){
    try{
        var objMain = new main();
        objMain.__construct();
    }catch(ex){
        $.sys_error_handler(ex);
    }
});

