Y.Core.register("photo-filter", function() {
    var _api;
    return {
        /* 
         * 模块初始化
         * @method init
         */
        init: function (api) {
            this.api = api;
            _api = this.api;
        },
        /* 
         * 模块内容读取完毕 (YUI onContentReady)
         * @method onviewload
         * @return void
         */
        onviewload: function () {
            this.node = this.api.getViewNode();
            this.node.one("form").on("submit", this._formSubmitHandler, this);
            if (this.node.one("form .keyword").get("value")) {
                this._makeRequest();    
            }
        },
        /* 
         * 更新按钮点选事件
         * @event _linkClickHandler
         * @param e {Y.Event} Event Object
         */
        _formSubmitHandler: function (e) {
            e.preventDefault();
            this.api.broadcast("photo-filter-submit");
            this._makeRequest();    
        },
        _makeRequest: function () {
            var attrs    = {},                       // 用于 url 字串取代
                callback = "",                       // Flickr 回传资料的全域变量名称
                url      = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=d498ec869768ecea276a7cb3906241d9&tags={tags}&per_page=100&sort=interestingness-desc&format=json&jsoncallback={callback}";  // Flickr API 网址

            callback = "_getData_" + parseInt(new Date().getTime(), 10);
            attrs = {
                "tags": encodeURIComponent(this.node.one("form .keyword").get("value")),
                "callback": callback
            };
            url = Y.substitute(url, attrs);
            window[callback] = this._getData;
            Y.Get.script(url, {
                onSuccess : function() {
                    window[callback] = null;
                }
            });
        },
        /* 
         * Flickr API 回传 Callback
         * @method _getData
         * @private
         */
        _getData: function (o) {
            _api.broadcast("photo-filter-response", o);
        }
    }
}());
