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
    this.ajaSearchDataGrid = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('aplication/events/read/getdatagrid'),
            alertError:false,
            success: function(params,response){
                objProcessF.getDataGridPrincipal(response.msg.rows);
            }
        });
    };
    
}
/**process*/
function processF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.getDataGridPrincipal = function(rows){
        $.sy_setDataTable({
            data:rows,
            target:"#divGrillaPrincipal",
            columns:['','id','Tipo','Titulo','Estado','Creado'],
            actions:{
                0:{
                    _events:{
                        _icon:"edit",
                        _click:function(params){
                            location.href = "update/"+params.rowData.id;
                        }
                    }
                }
            },
            columnsCustom:{
                1:{className:"center"},
                2:{className:"center"},
                3:{className:"center"},
                4:{className:"center"},
                5:{className:"center"},
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
        
        // evento guardar
        objAjaxF.ajaSearchDataGrid();
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

