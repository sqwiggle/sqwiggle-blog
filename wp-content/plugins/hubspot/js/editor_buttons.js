(function() {
    tinymce.create('tinymce.plugins.hubspot', {
        init : function(ed, url) {
            ed.addCommand("wp_hubspot",function(){
                ed.windowManager.open({
                    url: url+"/../wp_hubspot_visual.php",
                    width: 450,
                    height: 420,
                    inline: 1
                })
            });
            ed.addButton('hubspot', {
                title : 'Add HubSpot Shortcodes',
                image : url+'/../images/hubspot-logo12x11.png',
//                onclick : function() {
//                     ed.selection.setContent('[hubspot]');
//                }
                cmd:"wp_hubspot"
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('hubspot', tinymce.plugins.hubspot);
})();