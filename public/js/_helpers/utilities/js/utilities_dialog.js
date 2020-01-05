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
            bttnYes:true,
            message:"Desea realmente realizar este proceso",
            tittle:"Confirme si...",
            yes:function(){},
            no:function(){},
            openEvent:function(){},
            closeEvent:function(){}
        }, args);
        var html = "";
        html += '<!-- Modal -->';
        html += '<div class="modal modal-fixed-footer" id="myModalConfirmDialog_'+args.id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
        html += '   <div class="modal-content" role="document">';
        html += '       <h6 class="modal-title" id="myModalLabel" style="">'+args.tittle+'</h6>';
        html += '       <hr/>';
        html += '       <div class="modal-body">';
        html += '           <span style="font-family:verdana;">'+args.message+'</span>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="modal-footer">';
        if(args.bttnYes == true){
            html += '       <button type="button" id="bttnYesDC_'+args.id+'" class="bttnYesDC_'+args.id+' modal-action modal-close waves-effect waves-green btn-flat" data-dismiss="modal">'+args.lblYes+'</button>';
        }
        html += '       <button type="button" id="bttnNoDC_'+args.id+'" class="bttnNoDC_'+args.id+'  modal-action modal-close waves-effect waves-green btn-flat" data-dismiss="modal">'+args.lblNo+'</button>';
        html += '   </div>';
        html += '</div>';
        //
        $(document.getElementById('myModalConfirmDialog_'+args.id)).remove();
        $('body').append(html);
        
        // open
        $(document.getElementById('myModalConfirmDialog_'+args.id)).modal({
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
        
        // et event yes
        $(document.getElementById('bttnYesDC_'+args.id)).click(function(){
            args.yes();
        });
        $(document.getElementById('bttnNoDC_'+args.id)).click(function(){
            args.no();
            $(document.getElementById('myModalConfirmDialog_'+args.id)).modal("close");
        });
        
        // open
        $(document.getElementById('myModalConfirmDialog_'+args.id)).modal("open");
        return $(document.getElementById('myModalAlertDialog_'+args.id));
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
        html += '   <div class="modal-content '+args.sizeClass+'" role="document">';
        html += '       <h6 class="modal-title" id="myModalLabel" style="">'+args.tittle+'</h6>';
        html += '       <hr/>';
        html += '       <div class="modal-body">';
        html += '           <span id="body-'+args.id+'" style="font-family:verdana;">'+(typeof args.message === 'function'?args.message():args.message)+'</span>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="modal-footer">';
        html += '      <button type="button" id="bttnClose_'+args.id+'" class="bttnClose_'+args.id+' modal-action modal-close waves-effect waves-green btn-flat" data-dismiss="modal"> '+args.lblClose+'</button>';
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
        $(document.getElementById('myModalAlertDialog_'+args.id)).modal("open");
        return $(document.getElementById('myModalAlertDialog_'+args.id));
    }
});

