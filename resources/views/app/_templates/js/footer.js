/**validates*/
function validateF_F(){
    
    
}
/**eventos*/
function eventF_F(){
    
    
}
/**ajax**/
function ajaxF_F(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.executeProcessVarious = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('sys_process/various/update/process'),
            success: function(params,response){},
            alertError:false,
            preload:false
        });
    };
    
}
/**process*/
function processF_F(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.iniSetTimeInterval = function(){
        setInterval(function(){
            objAjaxF_F.executeProcessVarious();
        },60000);
    };
    
}
/**main*/
function main_F(){
    this.__construct = function(){
        objEventF_F = new eventF_F();
        objAjaxF_F = new ajaxF_F();
        objProcessF_F = new processF_F();
        objValidateF_F = new validateF_F();
        
        // iniciamos process various
        objProcessF_F.iniSetTimeInterval();
    };
}

var objValidateF_F = null;
var objEventF_F = null;
var objAjaxF_F = null;
var objProcessF_F = null;

$(document).ready(function(){
    try{
        var objMain_F = new main_F();
        objMain_F.__construct();
    }catch(ex){
        $.sys_error_handler(ex);
    }
});

