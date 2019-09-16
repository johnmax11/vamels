/**validates*/
function validateF_ML(){
    
    
}
/**eventos*/
function eventF_ML(){
    
    
    
}
/**ajax**/
function ajaxF_ML(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.getMenuByRoleUser = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('sys_process/menu/read/getmenui'),
            preload:false,
            target_paint_error:"#liMenuIzqContainer",
            success: function(params,response){
                $("#liMenuIzqContainer").html(response.msg.html).removeClass("center");
                $('.collapsible').collapsible();
                $('#'+response.msg.module+'-1').trigger("click");
                
            }
        });
    };
    
}
/**process*/
function processF_ML(){
}
/**main*/
function main_ML(){
    this.__construct = function(){
        objEventF_ML = new eventF_ML();
        objAjaxF_ML = new ajaxF_ML();
        objProcessF_ML = new processF_ML();
        objValidateF_ML = new validateF_ML();
        
        // iniciamos process various
        objAjaxF_ML.getMenuByRoleUser();
    };
}

var objValidateF_ML = null;
var objEventF_ML = null;
var objAjaxF_ML = null;
var objProcessF_ML = null;

$(document).ready(function(){
    try{
        var objMain_ML = new main_ML();
        objMain_ML.__construct();
    }catch(ex){
        $.sys_error_handler(ex);
    }
});

