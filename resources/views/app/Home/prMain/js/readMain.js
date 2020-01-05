var objPrimary = (function(){
    
    // object validate
    var validateF = function(){};
    
    // object envents
    var eventF = function(){
        
        /**
         * 
         * @returns {undefined}
         */
        this.suscribeEvents = function(){
            
        }; // END function
        
        /**
         * 
         * @returns {undefined}
         */
        this.triggerEvents = function(){
            
        }; // END function
        
    };
    
    // objects ajax
    var ajaxF = function(){
        
        /**
        * 
        * @param {type} idsale
        * @returns {undefined}
        */
        this.getDataTestigos = function(idsale){
            $.sy_ajaxFrm({
               url:$.sy_pathUrl('collaborators/witnesses/read/statistics'),
               preload:false,
               alertError:false,
               success: function(params,response){
                   
               }
            });
        }; // END function
        
    
    }; // END OBJECT AJAX
    
    // objetc process
    var processF = function(){
        
        
    };
    
    // main
    var main = function(){
        this.__construct = function(){
            
            // trigger events
            (new eventF()).triggerEvents();
        }; // END function
        
    };
    
    return {
        main:main
    };
})();

// init javascript
$(function(){
    try{(new objPrimary.main()).__construct();}catch(ex){$.sys_error_handler(ex);}
});
