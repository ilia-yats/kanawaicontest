(function($) {
    $(document).ready(function(){
        var _default = new DefaultClass();
            _default.init();  
    });
    
    function DefaultClass() {
        
        var self = this,
            options = {
                translation: plugin_translation_i18n()
            };
        
        this.init = function() {
            alert('This alert is an example of how translations in js works. Example code in "plugin_folder/view/js/main.js". Example translation of word "yes" - "'+options.translation.yes+'"');
        };
        
    }
})( jQuery );
