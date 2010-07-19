/*global YUI */
YUI.add("core", function (Y) {
    var registeredModules = [],
        listeners = {},
        match = function (eventName, callerId, callerData) {
            var modules = [],
                i,
                x;
            for (i in listeners) {
                for (x in listeners[i]) {
                    if (listeners[i][x] === eventName) {
                        if (typeof registeredModules[i].onmessage !== "undefined") {
                            registeredModules[i].onmessage(eventName, callerId, callerData);    
                            modules.push(i);
                        }
                    }
                }    
            }    
            Y.log("_match() is executed successfully, " + modules.length + " modules are influenced: " + modules.join(","), "info", "Core");
        },
        addListener = function (id, name) {
            var i, j;
            if (typeof listeners[id] === "undefined") {
                listeners[id] = [];
            }
            for (i = 0, j = listeners.length; i < j; i += 1) {
                if (listeners[id][i] === name) {
                    return false;
                }
            }
            listeners[id].push(name);
        },
        register = function (moduleId, o) {
            Y.log("register() initialized.", "info", "Core"); 
            var sandbox;
            registeredModules[moduleId] = o;    
            if (typeof o.init === "undefined") {
                Y.log("register() : Module init function is not defined.", "warn", "Core"); 
                return;
            }
            sandbox = new Y.Sandbox(moduleId);
            o.init(sandbox);
            if (typeof o.onviewload === "undefined") {
                Y.log("register() : Module onviewload function is not defined.", "warn", "Core"); 
                return;
            }
            Y.on("contentready", o.onviewload, "#" + moduleId, o);
        };       
    Y.Core = {
        register: register,
        _match: match,
        _addListener: addListener
    };
}, "3.1.1");
