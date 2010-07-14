Y.Core.register("photo-list", {
    api: null,
    node: null,
    previousImg: null,
    init: function (api) {
        // 注册要听取的事件
        api.listen("photo-filter-submit");
        api.listen("photo-filter-response");
        // 将 api 介面提升至全域
        this.api = api;
    },
    onviewload: function () {
        // shortcut 方便存取
        var node = this.api.getViewNode(),
            api  = this.api;
        // 绑定照片点选 click 事件
        Y.delegate("click", function (e) {
            if (this.previousImg) {
                this.previousImg.removeClass("selected");
            }
            this.previousImg = e.currentTarget;
            this.previousImg.addClass("selected");
            e.preventDefault();
            Y.log("photoClickHandler()", "info", "#photo-list");
            api.broadcast("photo-list-click", this.previousImg.get("src"));
            window.scrollTo(0, 0);
        }, node, "img", this);
        // 将 module 节点提升至全域
        this.node = node;
        this.previousImg = node.one("img");
        this.previousImg.addClass("selected");
        this.api.broadcast("photo-list-rendered", this.previousImg.get("src"));
    },
    onmessage: function (eventType, callerName, data) {
        Y.log(arguments.callee.name + " : " + eventType, "info", "#photo-list"); 
        switch (eventType) {
            case "photo-filter-response" : 
                this._updateUI(data);
                this.node.one(".bd").removeClass("loading");
            break;
            case "photo-filter-submit" :
                this.node.one(".bd").set("innerHTML", "");
                this.node.one(".bd").addClass("loading");
            break;
        }        
    },
    /* 
     * 依照取得的 Flickr Feed 进行画面的更新
     * @method _updateUI
     * @param data {Object} Flickr photo API feed
     * @private
     * @return void
     */
    _updateUI: function (data) {
        var items    = data.photos.photo,
            node     = this.node;
            bodyNode = node.one(".bd"),
            html     = [],
            i        = null,
            img      = "",
            link     = "";

        html.push("<ul class=\"clearfix\">");
        for (i in items) {
            img  = "http://farm" + items[i]["farm"] + ".static.flickr.com/" + items[i]["server"] + "/" + items[i]["id"] + "_" + items[i]["secret"]+"_s.jpg"; 
            link = "http://www.flickr.com/photos/" + items[i]["owner"] + "/" + items[i]["id"];  
            html.push("<li><a href=\"" + link + "\" title=\"" + items[i].title + "\"><img src=\"" + img + "\"></a></li>");    
        }
        html.push("</ul>");
        bodyNode.set("innerHTML", html.join(""));
        this.previousImg = node.one("img");
        this.previousImg.addClass("selected");
        this.api.broadcast("photo-list-rendered", this.previousImg.get("src"));
    }
});
