// single YUI instance
var Y = YUI();
Y.modules = [];

// core : control module life cycle
Y.add("core", function (Y) {
    var Core = function () {
        var moduleData = {};
        return {
            register: function (moduleId, creator) {
                moduleData[moduleId] = {
                    creator : creator,
                    instance: null
                }
            },
            /**
             * Starts the given module, calling it's init() method and optionally onviewload()
             * @param {string} moduleId The ID of the module to start
             * @return {void} 
             */
            start: function (moduleId) {
                moduleData[moduleId].instance = moduleData[moduleId].creator(new Sandbox(this));
                moduleData[moduleId].instance.init();
            },
            stop: function (moduleId) {
                var data = moduleData[moduleId];
                if (data.instance) {
                    data.instance.destroy();
                    data.instance = null;
                }
            },
            startAll: function () {
                for (var moduleId in moduleData) {
                    if (moduleData.hasOwnProperty(moduleId)) {
                        this.start(moduleId);
                    }
                }
            },
            stopAll: function () {
                for (var moduleId in moduleData) {
                    if (moduleData.hasOwnProperty(moduleId)) {
                        this.stop(moduleId);
                    }
                }
            }
        }
    };
    Y.Core = Core;
}, "3.1.0", {"requires" : ["node"]});
