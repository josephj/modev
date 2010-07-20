/*global YUI */
YUI.add("core", function (Y) {
    var registeredModules = [],
        listeners = {},
        //===========================
        // Private Functions & Events
        //===========================
        /* 
         * Match event and modules which subscribes the event
         * @method match
         * @param eventName {String} Event label name
         * @param callerId {String} The ID of the module which just broadcasts
         * @param callerData {Object} The data that a broadcasting module wants to share 
         * @return void
         */
        match = function (eventName, callerId, callerData) {
            Y.log("_match(\"" + eventName + "\", \"" + callerId + "\", \"" + callerData + "\") is executed.", "info", "Core");
            var modules = [], 
                i,
                x;
            for (i in listeners) {
                for (x in listeners[i]) {
                    if (listeners[i][x] === eventName) {
                        // trigger module's onmessage event
                        if (typeof registeredModules[i].onmessage === "undefined") {
                            continue;
                        }
                        registeredModules[i].onmessage(eventName, callerId, callerData);    
                        modules.push(i);
                    }
                }    
            }    
            Y.log("_match(\"" + eventName + "\", \"" + callerId + "\", \"" + callerData + "\") is executed successfully, " + modules.length + " module(s) is(are) influenced: \"#" + modules.join(", #") + "\"", "info", "Core");
        },
        /* 
         * Let a module listen for a specific message 
         * @method addListener
         * @param moduleId {String} ID of the module which wants to listen.
         * @param msgName {String} Target message label name.
         * @private
         * @return {Boolean} false if this module has already listened target message
         */
        addListener = function (moduleId, msgName) {
            Y.log("_addListener(\"" + moduleId + "\", \"" + msgName + "\") is executed.", "info", "Core");
            var i, 
                j,
                listener;
            if (typeof listeners[moduleId] === "undefined") {
                listener = listeners[moduleId] = [msgName];
                //Y.log("_addListener(\"" + moduleId + "\", \"" + msgName + "\") is added successfully", "info", "Core");
                return true;
            }
            for (i in listener) {
                if (listener[i] === msgName) {
                    Y.log("_addListener(\"" + moduleId + "\", \"" + msgName + "\") has already existed", "info", "Core");
                    return false;
                }
            }
            listeners[moduleId].push(msgName);
            //Y.log("_addListener(\"" + moduleId + "\", \"" + msgName + "\") is added successfully", "info", "Core");
            return true;
        },
        /* 
         * Register a module to Core
         * @method register
         * @param moduleId {String} ID of the module which wants to register.
         * @param attrs {String} Methods/attributes object which the registering module has.
         * @public
         * @return {Boolean} false if target message is registered by this module
         */
        register = function (moduleId, o) {
            Y.log("register(\"" + moduleId + "\", " + o + ") is executed.", "info", "Core"); 
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
            //Y.log("register(\"" + moduleId + "\", \"" + o + "\") is executed successfully.", "info", "Core"); 
        };       
    Y.Core = {
        register: register,
        _match: match,
        _addListener: addListener
    };
}, "3.1.1");
