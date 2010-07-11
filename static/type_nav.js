miii.module.types["nav"] = {
    init: function (api) {
        var dModule = api.getNode();
        var oMenu = new YAHOO.widget.MenuBar(dModule, {
            autosubmenudisplay: true,
            hidedelay: 750,
            lazyload: true
        });
    },
    onmessage: function (name, label, type) {
        
    }
};
