var Y = YUI();

Y.add("sandbox", function () {
    var Sandbox = function (id) {
        this.id = id;
    }
    var proto = Sandbox.prototype;
    proto.broadcast = function (name, data) {
        Y.log("broadcast('" + name + "') is executed.", "info", "Sandbox"); 
        Y.Core._match(name, this.id, data);
    };
    proto.listen = function (eventType) {
        Y.log("listen('" + eventType + "') is executed.", "info", "Sandbox"); 
        Y.Core._addListener(this.id, eventType);
    };
    proto.getViewNode = function () {
        Y.log("getViewNode() for module #" + this.id + " is executed.", "info", "Sandbox"); 
        return Y.one("#" + this.id);
    };
    Y.Sandbox = Sandbox;
});

Y.add("core", function () {
    var registerModules = [];
    var listeners = {};
    var Core =  {
        _match: function (name, callerId, data) {
            var modules = [];
            for (var i in listeners) {
                for (var x in listeners[i]) {
                    if (listeners[i][x] === name) {
                        if (typeof registerModules[i].onmessage !== "undefined") {
                            registerModules[i].onmessage(name, callerId, data);    
                            modules.push(i);
                        }
                    }
                }    
            }    
            Y.log("_match() is executed successfully, " + modules.length + " modules are influenced: " + modules.join(","), "info", "Core");
        },
        _addListener: function (id, name) {
            if (typeof listeners[id] === "undefined") {
                listeners[id] = [];
            }
            for (var i = 0, j = listeners.length; i < j; i++) {
                if (listeners[id][i] === name) {
                    // added already
                    return false;
                    break;
                }
            }
            listeners[id].push(name);
            //Y.log(listeners);
        },
        register: function (id, fn) {
            Y.log("register() initialized.", "info", "Core"); 
            registerModules[id] = fn;    
            if (typeof fn.init === "undefined") {
                Y.log("register() : Module init function is not defined.", "warn", "Core"); 
                return;
            }
            var sandbox = new Y.Sandbox(id);
            fn.init(sandbox);
            if (typeof fn.onviewload === "undefined") {
                Y.log("register() : Module onviewload function is not defined.", "warn", "Core"); 
                return;
            }
            Y.on("contentready", fn.onviewload, "#" + id, fn);
        }       
    };    
    Y.Core = Core;
}, "3.1.1");
Y.use("*");
