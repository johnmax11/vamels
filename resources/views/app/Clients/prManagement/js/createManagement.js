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
            url:$.sy_pathUrl('clients/management/create/savedata'),
            data:$('#frmDatos').serialize(),
            success: function(params,response){
                document.getElementById('frmDatos').reset();
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

