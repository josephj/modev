/*global YUI */
YUI.add("sandbox", function (Y) {
    var Sandbox = function (id) {
        this.id = id;
    };
    Sandbox.prototype = {
        broadcast: function (name, data) {
            Y.log("broadcast('" + name + "') is executed.", "info", "Sandbox"); 
            Y.Core._match(name, this.id, data);
        },
        listen: function (eventType) {
            Y.log("listen('" + eventType + "') is executed.", "info", "Sandbox"); 
            Y.Core._addListener(this.id, eventType);
        },
        getViewNode: function () {
            Y.log("getViewNode() for module #" + this.id + " is executed.", "info", "Sandbox"); 
            return Y.one("#" + this.id);
        }
    };
    Y.Sandbox = Sandbox;
});
var Y = YUI();
Y.use("*");
