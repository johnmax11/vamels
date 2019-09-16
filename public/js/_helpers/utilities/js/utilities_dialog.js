jQuery.extend({
    
    /**
     * 
     * @param {type} args
     * @returns {undefined}
     */
    sy_confirmDialog : function(args){
        args = jQuery.extend({
            id:"customCD",
            lblYes:"Si",
            lblNo:"No",
            message:"Desea realmente realizar este proceso",
            tittle:"Confirme si...",
            yes:function(){},
            no:function(){},
        }, args);
        var html = "";
        html += '<!-- Modal -->';
        html += '<div class="modal fade" id="myModalConfirmDialog_'+args.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
        html += '   <div class="modal-dialog" role="document">';
        html += '       <div class="modal-content">';
        html += '           <div class="modal-header">';
        html += '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        html += '               <h4 class="modal-title" id="myModalLabel">'+args.tittle+'</h4>';
        html += '           </div>';
        html += '           <div class="modal-body">';
        html += '               <span style="font-family:verdana;">'+args.message+'</span>';
        html += '           </div>';
        html += '           <div class="modal-footer">';
        html += '               <button type="button" id="bttnYesDC_'+args.id+'" class="bttnYesDC_'+args.id+' btn btn-indervalle" data-dismiss="modal"><span class="fa fa-check-circle-o"></span> '+args.lblYes+'</button>';
        html += '               <button type="button" id="bttnNoDC_'+args.id+'" class="bttnNoDC_'+args.id+' btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle-o"></span> '+args.lblNo+'</button>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        //
        $(document.getElementById('myModalConfirmDialog_'+args.id)).remove();
        $('body').append(html);
        
        // et event yes
        $(document.getElementById('bttnYesDC_'+args.id)).click(function(){
            $(document.getElementById('myModalConfirmDialog_'+args.id)).modal({backdrop: "static"});
            args.yes();
        });
        $(document.getElementById('bttnNoDC_'+args.id)).click(function(){
            args.no();
        });
        
        // set eventos open
        $(document.getElementById('myModalConfirmDialog_'+args.id)).on('show.bs.modal', function (event) {
            console.log('-aca-');
        });
        // set event hide
        $(document.getElementById('myModalConfirmDialog_'+args.id)).on('hide.bs.modal', function (event) {
            console.log('-joonnnn');
        });
        
        // open
        $(document.getElementById('myModalConfirmDialog_'+args.id)).modal({backdrop: "static"});
    },
    
    /**
     * 
     * @param {type} args
     * @returns {undefined}
     */
    sy_alertDialog : function(args){
        args = jQuery.extend({
            id:"customAD",
            lblClose:'Cerrar',
            tittle:"Atencion!",
            params:{},
            openEvent:function(){},
            closeEvent:function(){}
        }, args);
        var html = "";
        html += '<!-- Modal -->';
        html += '<div class="modal modal-fixed-footer" id="myModalAlertDialog_'+args.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
        html += '   <div class="modal-dialog '+args.sizeClass+'" role="document">';
        html += '       <div class="modal-content">';
        html += '           <div class="modal-header">';
        html += '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        html += '               <h4 class="modal-title" id="myModalLabel">'+args.tittle+'</h4>';
        html += '           </div>';
        html += '           <div class="modal-body">';
        html += '               <span id="body-'+args.id+'" style="font-family:verdana;">'+(typeof args.message === 'function'?args.message():args.message)+'</span>';
        html += '           </div>';
        html += '           <div class="modal-footer">';
        html += '               <button type="button" id="bttnClose_'+args.id+'" class="bttnClose_'+args.id+' btn btn-indervalle" data-dismiss="modal"><span class="fa fa-check-circle-o"></span> '+args.lblClose+'</button>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        //
        $(document.getElementById('myModalAlertDialog_'+args.id)).remove();
        $('body').append(html);
        
        
        // open
        $(document.getElementById('myModalAlertDialog_'+args.id)).modal({
            dismissible:false,
            ready: function(modal, trigger) {
                console.log('-open dialog-');
                args.openEvent(args.params);
            },
            complete: function() {
                console.log('-close dialog-');
                args.closeEvent(args.params);
            }
        });
        console.log("aca");
        $(document.getElementById('myModalAlertDialog_'+args.id)).modal("open");
        return $(document.getElementById('myModalAlertDialog_'+args.id));
    }
});

