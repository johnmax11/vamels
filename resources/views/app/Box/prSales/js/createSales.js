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
    this.setTotalRow = function(){
        $('.format-number').blur(function(){
            objProcessF.setTotalRowCalculo();
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setCalendarFechaVenta = function(){
        
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
    
    /**
     * 
     * @returns {undefined}
     */
    this.setAutocompleteCliente = function(){
        //Initialize Select2 Elements
        $("#selCliente").select2({
            minimumInputLength: 2,
            ajax:{
                url:objAjaxF.getClienteByAutoComplete(),
                dataType: 'json',
                type: "GET",
                data: function (term) {
                    return {
                        q: term.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.msg.rows, function (item) {
                            return {
                                text: item.phone+' - '+item.name,
                                name: item.name,
                                id: item.id,
                                phone:item.phone,
                            }
                        })
                    };
                },
            },
            templateResult: objProcessF.getTemplateResultCliente, // omitted for brevity, see the source of this page
        });
        $(".select2-selection__rendered").mouseover(function(){
            setTimeout(function(){
                $('.ui-tooltip').remove();
            },1);
            
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.iniSelectSearch = function(ind){
        $('#selCodigo-'+ind).on("select2:selecting", function(e) {
            //var arrN = e.currentTarget.id.split("-");
            // set precio
            $('#txtVrVenta-'+ind).val(e.params.args.data.value_sell);
            objProcessF.setTotalRowCalculo();
            // what you would like to happe
            setTimeout(function(){
                $('.select2-selection__rendered').removeAttr('title');
            },1000);
        });
        //Initialize Select2 Elements
        $("#selCodigo-"+ind).select2({
            minimumInputLength: 2,
            ajax:{
                url:objAjaxF.getProductsByAutoComplete(),
                dataType: 'json',
                type: "GET",
                data: function (term) {
                    return {
                        q: term.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.msg.rows, function (item) {
                            return {
                                text: item.prefix+' '+item.code_product+' - '+item.name,
                                name: item.name,
                                id: item.id,
                                prefix: item.prefix,
                                value_sell:item.value_sell,
                                code_product:item.code_product,
                                inventory:item.inventory
                            }
                        })
                    };
                },
            },
            templateResult: objProcessF.getTemplateResult, // omitted for brevity, see the source of this page
        });
        $(".select2-selection__rendered").mouseover(function(){
            setTimeout(function(){
                $('.ui-tooltip').remove();
            },1);
            
        });
    };
    
    /**
     * 
     * @param {type} ind
     * @returns {undefined}
     */
    this.iniEventRemoveRow = function(ind){
        $('#lblRemove-'+ind).click(function(){
            var obj = $(this).parent().parent();
            $(obj).remove();
            
            // totaizamos d nuevo
            objProcessF.setTotalRowCalculo();
        });
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setEventPlusIcon = function(){
        $('#lblPlus-0').click(function(){
            // agregamos una fila nueva
            objProcessF.setNewFilaItem();
        });
    };
    
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
    this.setEventClientNew = function(){
        $('#lblPlusCliente').click(function(){
            location.href = public_path+"app/clients/management/create";
        });
    };
    
}
/**ajax**/
function ajaxF(){
    
    /**
     * 
     * @returns {undefined}
     */
    this.getUsers = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('sys_account/profile/read/getusers'),
            success: function(params,response){
                // combo pais datos basicos
                $.sy_setCombo({
                    datos:response.msg.rows,
                    idcombo:"#selVendedor",
                });
            }
        });
    };
    
    /**
     * 
     * @returns {ajaxF.getProductsByAutoComplete.createSalesAnonym$3}
     */
    this.getProductsByAutoComplete = function(){
        return public_path+$.sy_pathUrl('inventory/products/read/getproductsbycode');
    };
    
    this.getClienteByAutoComplete = function(){
        return public_path+$.sy_pathUrl('clients/management/read/getbyautocomplete');
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.sendData = function(){
        $.sy_ajaxFrm({
            url:$.sy_pathUrl('box/sales/create/savedata'),
            data:$('#frmDatos').serialize(),
            success: function(params,response){
                document.getElementById('frmDatos').reset();
                setTimeout(function(){
                    location.reload();
                },1000);
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
    this.setTotalRowCalculo = function(){
        var totalGen = 0;
        for(var i=0;i < parseInt($('#hdnContRows').val());i++){
            if(document.getElementById('txtVrVenta-'+i)==null){
                continue;
            }
            var vrVenta = $('#txtVrVenta-'+i).val().replace(/,/gi, "");
                vrVenta = (isNaN(vrVenta)==true||vrVenta=='' ?0:vrVenta);
                
            var vrDisc = $('#txtDescuento-'+i).val().replace(/,/gi, "");
                vrDisc = (isNaN(vrDisc)==true||vrDisc=='' ?0:vrDisc);
                
            // set total row
            var totalR = (vrVenta - vrDisc);
            
            // set total row
            $('#lblTotal-'+i).html($.sy_number_format(totalR));
            
            // sum total general
            totalGen = (totalGen + totalR);
        }
        // set total general footer
        $('#lblTotalFooter').html($.sy_number_format(totalGen));
    };
    
    /**
     * 
     * @param {type} result
     * @returns {$}
     */
    this.getTemplateResult = function(result){
        if (result.loading) 
            return result.text;
        var html =  '<div>'+
                    '   <span style="font-size:8pt;">'+result.prefix+' '+result.code_product+'</span>'+
                    '   <h4>'+result.name+'</h4>'+
                    '   <u>$ '+result.value_sell+'</u> - Inv: '+result.inventory+
                    '</div>';
        //return html;
        return $(html);
    };
    
    /**
     * 
     * @returns {result.text|$}
     */
    this.getTemplateResultCliente = function(result){
        if (result.loading) 
            return result.text;
        var html =  '<div>'+
                    '   <span style="font-size:8pt;">'+result.phone+'</span>'+
                    '   <h4>'+result.name+'</h4>'+
                    '</div>';
        //return html;
        return $(html);
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.setNewFilaItem = function(){
        var ind = $('#hdnContRows').val();
        var str = objProcessF.getHtmlRow(ind);
        
        // agregar fila a row
        $('#divFooter').before(str);
        
        // add eventos
        objEventF.iniSelectSearch(ind);
        // number format campos
        objEventF.setNumberFormat();
        // event totaliza
        objEventF.setTotalRow();
        // add evento remove
        objEventF.iniEventRemoveRow(ind);
        
        // add cont
        $('#hdnContRows').val(parseInt(ind)+1);
    };
    
    /**
     * 
     * @param {type} ind
     * @returns {String}
     */
    this.getHtmlRow = function(ind){
        var str = "";
            str += '<div id="divRow'+ind+'" class="row">';
            str += '    <div class="col-sm-1" style="text-align: center;">';
            str += '        <label id="lblNumeroRow-'+ind+'" class="form-control">'+(parseInt(ind)+1)+'</label>';
            str += '    </div>';
            str += '    <div class="col-sm-4">';
            str += '        <select id="selCodigo-'+ind+'" name="selCodigo-'+ind+'" class="form-control select2 validate" style="width: 100%;">';
            str += '            <option value="">...</option>';
            str += '        </select>';
            str += '    </div>';
            str += '    <div class="col-sm-2">';
            str += '        <div class="input-group">';
            str += '            <label for="txtVrVenta-'+ind+'" class="input-group-addon">';
            str += '                <span class="">$</span>';
            str += '            </label>';
            str += '            <input type="text" id="txtVrVenta-'+ind+'" name="txtVrVenta-'+ind+'" class="form-control format-number validate" disabled="disabled"/>';
            str += '        </div>';
            str += '    </div>';
            str += '    <div class="col-sm-2">';
            str += '        <div class="input-group">';
            str += '            <label for="txtDescuento-'+ind+'" class="input-group-addon">';
            str += '                <span class="">$</span>';
            str += '            </label>';
            str += '            <input type="text" id="txtDescuento-'+ind+'" name="txtDescuento-'+ind+'" class="form-control format-number validate" value="0"/>';
            str += '        </div>';
            str += '    </div>';
            str += '    <div class="col-sm-2" style="text-align: center;">';
            str += '        <div class="input-group">';
            str += '            <label for="lblTotal-'+ind+'" class="input-group-addon">';
            str += '                <span class="">$</span>';
            str += '            </label>';
            str += '            <label id="lblTotal-'+ind+'" class="form-control label-default">0</label>';
            str += '        </div>';
            str += '    </div>';
            str += '    <div class="col-sm-1" style="text-align: center;">';
            str += '        <label id="lblRemove-'+ind+'" class="fa fa-remove remove-row-item" style="cursor:pointer;"></label>';
            str += '    </div>';
            str += '</div>';
            
            return str;
    };
}
/**main*/
function main(){
    this.__construct = function(){
        objEventF = new eventF();
        objAjaxF = new ajaxF();
        objProcessF = new processF();
        objValidateF = new validateF();
        
        // ini calendario
        objEventF.setCalendarFechaVenta();
        // consultamos los vendedores
        objAjaxF.getUsers();
        // number format campos
        objEventF.setNumberFormat();
        // event totaliza
        objEventF.setTotalRow();
        // select 2
        objEventF.iniSelectSearch(0);
        // ini selecte clente
        objEventF.setAutocompleteCliente();
        // event plus
        objEventF.setEventPlusIcon();
        // evento guardar
        objEventF.setEventSave();
        // ini event crear cliente
        objEventF.setEventClientNew();
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

