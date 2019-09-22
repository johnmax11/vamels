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
    
    /**
     * 
     * @returns {undefined}
     */
    this.setNumberFormat = function(){
        $('.format-number').keyup(function(){
            this.value = $.sy_number_format(this.value);
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
            url:$.sy_pathUrl('inventory/warehouse/create/savedata'),
            data:$('#frmDatos').serialize(),
            success: function(params,response){
                document.getElementById('frmDatos').reset();
            }
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.getProducts = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('inventory/products/read/getdataproductsbyinventory'),
            success: function(params,response){
                // combo pais datos basicos
                $.sy_setCombo({
                    datos:response.msg.rows,
                    idcombo:"#selProducto",
                });
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
        objAjaxF.getProducts();
        // format number
        objEventF.setNumberFormat();
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

