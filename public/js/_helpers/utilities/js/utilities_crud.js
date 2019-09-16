jQuery.extend({
    
    /**
     * 
     * @param {type} args
     * @returns {undefined}
     */
    sy_insert_bttn_crud : function(args){
        args = jQuery.extend({
            bttnPlus:{
                visible:true
            },
            bttnList:{
                visible:true
            },
        }, args);
        
        // verificamos en q vista esta
        if($('#_action_selected').val()=='create' || $('#_action_selected').val()=='update'){
            $('#bttnPlus-'+$('#_action_selected').val()).remove();
            $('#bttnList-'+$('#_action_selected').val()).remove();
            
            // verificamos si no existe el div
            if(!document.getElementById('divBttnActionsCrud')){
                $('.jumbotron-small').after("<div id='divBttnActionsCrud' class='container-fluid'><div id='tbl-"+$('#_action_selected').val()+"_length'></div></div>");
                $('#tbl-'+$('#_action_selected').val()+'_length').html(
                    '<span class="dataTables_length">'+
                    '   <div id="divOcultoMenu" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" style="float:left!important;margin-bottom: 0px !important;margin-right: 0px !important;margin-top: 0px !important;padding: 0px 0px; !important">'+

                    '   </div>'+
                    '</span>'+
                    '<span class="dataTables_length collapse navbar-collapse">'+
                    '   <div id="divVisibleMenu" class="nav navbar-nav">'+

                    '   </div>'+
                    '</span>'
                );
            }
            
            // insertamos btttn
            /////  llamamos a function d utilities
            $.sy_getBttnPlus($('#_action_selected').val(),'primary');
            $.sy_getBttnList($('#_action_selected').val(),'default');
        }
    }
});
var set = null;
$('#_action_selected').ready(function(){
    if(window.location.pathname != "/syslab/public/app"){
        set = setInterval(function(){
            if($('#_action_selected').val() != "" && $('#_action_selected').val() != undefined){
                $.sy_insert_bttn_crud();
                clearInterval(set);
            }
        },'500');
    }
});

