/**validates*/
function validateF(){
    
    
}
/**eventos*/
function eventF(){
    
    
}
/**ajax**/
function ajaxF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.loadDataGridPrincipal = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('clients/management/read/getdataall'),
            preload:false,
            alertError:false,
            success: function(params,response){
                objProcessF.setDataTableGrillaPrincipal(response.msg.rows);
            }
        });
    };
    
}
/**process*/
function processF(){
    
    /**
     * 
     * @param {type} rows
     * @returns {undefined}
     */
    this.setDataTableGrillaPrincipal = function(rows){
        $.sy_setDataTable({
            data:rows,
            target:"#divGrillaPrincipal",
            columns:['id','Nombre','Tel&eacute;fono','Estado','Creado por','Creado el','Actualizado por','Ultima Actualización'],
        });
    };
    
}
/**main*/
function main(){
    this.__construct = function(){
        objEventF = new eventF();
        objAjaxF = new ajaxF();
        objProcessF = new processF();
        objValidateF = new validateF();

        // carga las fichas tecnicas
        objAjaxF.loadDataGridPrincipal();
    }
}

var objValidateF = null;
var objEventF = null;
var objAjaxF = null;
var objProcessF = null;

$(document).ready(function(){
    var objMain = new main();
    objMain.__construct();
});

