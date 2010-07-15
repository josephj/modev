/*global Y*/
Y.Core.register("navigation", function () {
    return  {
        init : function (api) {
            this.api = api;   
        },
        onviewload : function () {
            this.node = this.api.getViewNode();
            this.node.plug(Y.Plugin.NodeMenuNav);
        }
    };
}());
