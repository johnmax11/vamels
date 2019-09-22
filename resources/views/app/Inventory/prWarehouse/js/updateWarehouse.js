/**validates*/
function validateF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.validateSendData = function(){
        if(!$.sy_validateForm('#frmDatos')){
            return;
        }
        // enviamos los datos al servidor
        objAjaxF.sendData();
    };
    
}
/**eventos*/
function eventF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventSave = function(){
        $('#btnGuardar').click(function(){
            objValidateF.validateSendData();
        });
    };
    
}
/**ajax**/
function ajaxF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.sendData = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('inventory/warehouse/update/savedata'),
            data:$('#frmDatos').serialize(),
            success: function(params,response){
                document.getElementById('frmDatos').reset();
                location.reload();
            }
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.getDataInvProducts = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('inventory/warehouse/read/'+$.sy_get_id_token_update()+'/getinventory'),
            success: function(params,response){
                $('#txtProducto').val(response.msg.rows[0].name);
                $('#txtCantidad').val(response.msg.rows[0].cant_actual);
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
        
        // evento guardar
        objEventF.setEventSave();
        // cargamos los prefijos
        objAjaxF.getDataInvProducts();
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

