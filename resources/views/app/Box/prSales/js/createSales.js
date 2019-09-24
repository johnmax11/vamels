var objPrimary = (function(){
    
    // object validate
    var validateF = function(){
        
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
        }; // END function
    
    }; // END OBJECT
    
    // objects event
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            
            // number format campos
            this.setNumberFormat();
            // event totaliza
            this.setTotalRow();
            // select 2
            this.iniSelectSearch(0);
            // ini selecte clente
            this.setAutocompleteCliente();
            // event plus
            this.setEventPlusIcon();
            // evento guardar
            this.setEventSave();
            // ini event crear cliente
            this.setEventClientNew();
        
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            // consultamos los vendedores
            (new ajaxF()).getUsers();
        
        }; // END function
        
        /**
        * 
        * @returns {undefined}
        */
        this.setTotalRow = function(){
           $('.format-number').blur(function(){
               (new processF()).setTotalRowCalculo();
           });
        }; // END function
    
        /**
        * 
        * @returns {undefined}
        */
        this.setNumberFormat = function(){
           $('.format-number').keyup(function(){
               this.value = $.sy_number_format(this.value);
           });
        }; // END function
    
        /**
        * 
        * @returns {undefined}
        */
        this.iniSelectSearch = function(ind){
           return;
           //Initialize Select2 Elements
           $("#selCodigo-"+ind).select2({
               minimumInputLength: 2,
               ajax:{
                   url:(new ajaxF()).getProductsByAutoComplete(),
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
               templateResult: (new processF()).getTemplateResult, // omitted for brevity, see the source of this page
           });
        }; // end function
        
        /**
        * 
        * @returns {undefined}
        */
        this.setAutocompleteCliente = function(){
            $("#txtCliente").devbridgeAutocomplete({
                //lookup: countries,
                serviceUrl:(new ajaxF()).getClienteByAutoComplete(),
                type:'GET',
                //callback just to show it's working
                onSelect: function (suggestion) {
                    console.log(suggestion);
                },
                onSearchStart:function(){
                    
                },
                minChars:3,
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Sin resultados',
            });
        }; // end function
    
        /**
        * 
        * @returns {undefined}
        */
        this.setEventPlusIcon = function(){
           $('#lblPlus-0').click(function(){
               // agregamos una fila nueva
               (new processF()).setNewFilaItem();
           });
        }; // END function

       /**
        * 
        * @returns {undefined}
        */
        this.setEventSave = function(){
           $('#btnGuardar').click(function(){
               (new validateF()).validateSendData();
           });
        }; // END function
        
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
               (new processF()).setTotalRowCalculo();
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

    
    }; // END OBJECT
    
    // objects ajax
    var ajaxF = function(){
        
        /**
        * 
        * @returns {undefined}
        */
        this.getUsers = function(){
           $.sy_ajaxFrm({
               url:$.sy_pathUrl('sys_accountdata/profile/read/data'),
               
               success: function(params,response){
                   // combo pais datos basicos
                   $.sy_setCombo({
                       datos:response.msg.rows,
                       idcombo:"#selVendedor",
                       colName:["users_id","first_name"],
                       afterCombo:function(){
                            $('#selVendedor').material_select();
                       }
                   });
               }
           });
        }; // END function
        
        /**
        * 
        * @returns {ajaxF.getProductsByAutoComplete.createSalesAnonym$3}
        */
        this.getProductsByAutoComplete = function(){
           return public_path+$.sy_pathUrl('inventory/products/read/getproductsbycode');
        }; // END function

        /**
         * 
         * @returns {unresolved}
         */
        this.getClienteByAutoComplete = function(){
           return public_path+$.sy_pathUrl('clients/management/read/autocomplete');
        }; // END function
    
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
        }; // END function

    
    }; // END OBJECT
    
    // object process
    var processF = function(){
        
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
        }; // END function
    
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
        }; // END function

        /**
         * 
         * @param {type} result
         * @returns {$}
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
        }; // END function

        /**
         * 
         * @returns {undefined}
         */
        this.setNewFilaItem = function(){
            var ind = $('#hdnContRows').val();
            var str = (new processF()).getHtmlRow(ind);

            // agregar fila a row
            $('#divFooter').before(str);

            // add eventos
            (new eventF()).iniSelectSearch(ind);
            // number format campos
            (new eventF()).setNumberFormat();
            // event totaliza
            (new eventF()).setTotalRow();
            // add evento remove
            (new eventF()).iniEventRemoveRow(ind);

            // add cont
            $('#hdnContRows').val(parseInt(ind)+1);
        }; // END function

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
        }; // END function
        
    }; // END OBJECT
    
    // main
    var main = function(){
        this.__construct = function(){
            
            // suscrbie eventes
            (new eventF()).suscribeEvents();
            // trigger events
            (new eventF()).triggerEvents();
        }; // END function
        
    }; // END OBJECT
    
    return {
        main:main
    };
})();

// init javascript
$(function(){
    try{(new objPrimary.main()).__construct();}catch(ex){$.sys_error_handler(ex);}
});
