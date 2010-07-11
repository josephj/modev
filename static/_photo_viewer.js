Y.Core.register("photo-viewer", {
    /* 
     * 模块初始化
     * @method init
     */
    init: function (api) {
        this.api = api;
        this.api.listen("photo-list-rendered");
        this.api.listen("photo-list-click");
    },
    /* 
     * 模块内容读取完毕 (YUI onContentReady)
     * @method onviewload
     * @return void
     */
    onviewload: function () {
        var node = this.api.getViewNode();
        this.node = node;
    }, 
    onmessage: function (name, callerId, data) {
        this.node.one(".bd").set("innerHTML", "<img src='" + data.replace("_s", "") + "'>");
    }
});
