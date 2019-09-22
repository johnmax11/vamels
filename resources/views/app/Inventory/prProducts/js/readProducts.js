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
            url:$.sy_pathUrl('inventory/products/read/getdataall'),
            target_preload:'#divGrillaPrincipal',
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
            columns:['id','Prefijo','Codigo','Nombre','Vr. Compra','Vr. Venta','Creado el'],
            actions:{
                0:{
                    position:"first",
                    _events:{
                        _icon:"fa fa-edit",
                        _click:function(params){
                            location.href = "update/"+params.rowData.id;
                        }
                    }
                }
            }
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

