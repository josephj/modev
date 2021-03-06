/*global YUI */
YUI.add("sandbox", function (Y) {
    var Sandbox = function (id) {
        this.id = id;
    };
    Sandbox.prototype = {
        /* 
         * Module broadcasts its message to platform.
         * @method broadcast
         * @param msgName {String} Other module's message name.
         * @param data {Object} Data which module wants to share.
         * @public
         * @return void
         */
        broadcast: function (msgName, data) {
            Y.log("broadcast(\"" + msgName + "\" +) for #" + this.id + " is executed.", "info", "Sandbox"); 
            Y.Core._match(msgName, this.id, data);
        },
        /* 
         * Module listens to specific event.
         * @method listen 
         * @param msgName {String} Target message name.
         * @public
         * @return void
         */
        listen: function (msgName, callback) {
            Y.log("listen(\"" + msgName + "\") for #" + this.id + " is executed.", "info", "Sandbox"); 
            Y.Core._addListener(this.id, msgName, callback);
        },
        /* 
         * Module gets YUI Node instance.
         * @method getViewNode
         * @public
         * @return {Y.Node} Module YUI Node instance.
         */
        getViewNode: function () {
            Y.log("getViewNode() for #" + this.id + " is executed.", "info", "Sandbox"); 
            return Y.one("#" + this.id);
        }
    };
    Y.Sandbox = Sandbox;
}, "3.1.1", ["event-custom"]);
var Y = YUI();
Y.use("*");
