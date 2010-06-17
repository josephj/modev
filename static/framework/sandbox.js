// sandbox : module api interface, security guard
Y.add("sandbox", function (Y) {
    var Sandbox = function (node) {
        this.node = node;
    };
    Sandbox.prototype.one = function (query) {
        return this.node.one(query);
    };
});
