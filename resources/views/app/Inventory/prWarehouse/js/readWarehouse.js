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
            url:$.sy_pathUrl('inventory/warehouse/read/getdataall'),
            target_preload:"#divGrillaPrincipal",
            alertError:false,
            success: function(params,response){
                objProcessF.setDataTableGrillaPrincipal(response.msg.rows);
            }
        });
    };
    
    /**
     * 
     * @param {type} idinv
     * @returns {undefined}
     */
    this.getDetailsInventoryProductById = function(idinv){
        $.sy_ajaxFrm({
            preload:false,
            data:{idinvprod:idinv},
            url:$.sy_pathUrl('inventory/warehouse/read/getdetailstrack'),
            success: function(params,response){
                $('#body-customAD').html(response.msg.rows.html);
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
            columns:['id','Codigo','Producto','Cantidad','Vr. Compra','Vr. Venta','Actualizado'],
            footerTotal:[[4,4,''],[5,5,'$'],[6,6,'$']],
            actions:{
                0:{
                    position:"first",
                    _events:{
                        _icon:"fa fa-edit",
                        _click:function(params){
                            location.href = "update/"+params.rowData.id;
                        }
                    }
                },
                4:{
                    _events:{
                        _icon:['fa fa-search',true],
                        _click:function(params){
                            objProcessF.getDetailsInvTrack(params.rowData.id);
                        }
                    }
                }
            },
            columnsCustom:{
                1:{className:"text-center"},
                4:{className:"text-center"},
            }
        });
    };
    
    /**
     * 
     * @param {type} idinvprod
     * @returns {undefined}
     */
    this.getDetailsInvTrack = function(idinvprod){
        $.sy_alertDialog({
            params:{idinvprod:idinvprod},
            message:function(){return $.sy_getHtmlPreload();},
            tittle:"Track del inventario",
            openEvent:function(params){
                objAjaxF.getDetailsInventoryProductById(params.idinvprod);
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

