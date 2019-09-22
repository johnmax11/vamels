var objPrimary = (function(){
    
    // object validate
    var validateF = function(){};
    
    
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            
        };
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            (new ajaxF()).loadDataGridPrincipal();
        };
    };
    
    // objects ajax
    var ajaxF = function(){
        
        /**
        * 
        * @returns {undefined}
        */
        this.loadDataGridPrincipal = function(){
           $.sy_ajaxFrm({
               url:$.sy_pathUrl('box/sales/read/data'),
               preload:false,
               alertError:false,
               params:{processF:new processF()},
               success: function(params,response){
                   params.processF.setDataTableGrillaPrincipal(response.msg.rows);
               }
           });
        };
    
        /**
        * 
        * @param {type} idsale
        * @returns {undefined}
        */
        this.getDetailsProductById = function(idsale){
           $.sy_ajaxFrm({
               url:$.sy_pathUrl('box/sales/read/data/'+idsale),
               preload:false,
               alertError:false,
               success: function(params,response){
                   $('#body-customAD').html(response.msg.html);
               }
           });
        };
    
    };
    
    // objetc process
    var processF = function(){
        
        /**
        * 
        * @param {type} rows
        * @returns {undefined}
        */
        this.setDataTableGrillaPrincipal = function(rows){
           $.sy_setDataTable({
               data:rows,
               target:"#divGrillaPrincipal",
               columns:['id','Cantidad','Total','T - Dcto','Cliente','Vendedor','Fecha'],
               columnsCustom:{
                   0:{className:"center"},
                   1:{className:"center"},
                   2:{className:"center"},
                   3:{className:"center"},
                   4:{className:"center"},
                   5:{className:"center"},
                   6:{className:"center"},
               },
               actions:{
                   1:{
                       _events:{
                           _icon:['search',true],
                           _click:function(params){
                               (new processF()).openDialogDetails(params.rowData.id);
                           }
                       }
                   }
               },
               footerTotal:[[3,4,'$']]
           });
       };
    
        /**
         * 
         * @returns {undefined}
         */
        this.openDialogDetails = function(idsale){
            $.sy_alertDialog({
                params:{idsale:idsale},
                message:function(){return "<div class='center'>"+$.sy_getHtmlPreload()+"</div>";},
                tittle:"Detalle de la venta",
                openEvent:function(params){
                    (new ajaxF()).getDetailsProductById(params.idsale);
                }
            });
        };

    };
    
    // main
    var main = function(){
        this.__construct = function(){
            
            // trigger events
            (new eventF()).triggerEvents();
        };
        
    };
    
    return {
        main:main
    };
})();

// init javascript
$(function(){
    try{(new objPrimary.main()).__construct();}catch(ex){$.sys_error_handler(ex);}
});

