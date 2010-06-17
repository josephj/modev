// single YUI instance
var Y = YUI();
Y.modules = [];

// core : control module life cycle
Y.add("core", function (Y) {
    var Core = {
        init : function () {
            Y.log("Core init");
            for (var i in Y.modules) {
                if (!Y.one("#" + i)) {
                    Y.log("#" + i + " not exists");
                    continue;
                }
                Y.log("Module #" + i + " is created");
                Y.modules[i].init();
            }
        }
    };
    Y.Core = Core;
}, "3.1.0", {"requires" : ["node"]});
