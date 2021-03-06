/*global YUI */
YUI.add("core", function (Y) {
    var registeredModules = [],
        listeners = {},
        maps = [],
        //===========================
        // Private Functions & Events
        //===========================
        /* 
         * Match event and modules which subscribes the event
         * @method match
         * @param msgName {String} Event label name
         * @param callerId {String} The ID of the module which just broadcasts
         * @param callerData {Object} The data that a broadcasting module wants to share 
         * @return void
         */
        match = function (msgName, callerId, callerData) {
            Y.log("_match(\"" + msgName + "\", \"" + callerId + "\", \"" + callerData + "\") is executed.", "info", "Core");
            var modules = [], 
                i;
            // find modules which register this event
            for (i in listeners) {
                if (listeners[i].hasOwnProperty(msgName)) {
                    // prevent user handlers' error
                    try {
                        listeners[i][msgName](msgName, callerId, callerData);
                        if (typeof registeredModules[i].onmessage !== "undefined") {
                            registeredModules[i].onmessage(msgName, callerId, callerData);    
                        }
                        modules.push(i);
                    }
                    catch (e) {
                        Y.log("_match() " + e.message, "error", "Core");
                    }
                }
            }    
            Y.log("_match(\"" + msgName + "\", \"" + callerId + "\", \"" + callerData + "\") is executed successfully, " + modules.length + " module(s) is(are) influenced: \"#" + modules.join(", #") + "\"", "info", "Core");
        },
        /* 
         * Let a module listen for a specific message 
         * @method addListener
         * @param moduleId {String} ID of the module which wants to listen.
         * @param msgName {String} Target message label name.
         * @private
         * @return {String} listener ID for future use (remove, update...)
         *                
         */
        addListener = function (moduleId, msgName, handler) {
            Y.log("_addListener(\"" + moduleId + "\", \"" + msgName + "\") is executed.", "info", "Core");
            var i, 
                j,
                listener,
                listenerId;
            handler = handler || function () {};
            listenerId = Y.guid();
            if (typeof listeners[moduleId] === "undefined") {
                listeners[moduleId] = {};
            }
            listeners[moduleId][msgName] = handler;
            maps[listenerId] = listeners[moduleId][msgName];
            return listenerId;
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
        };       
    Y.Core = {
        register: register,
        _match: match,
        _addListener: addListener
    };
}, "3.1.1");
