miii.module.types["article"] = {
    init: function (api) {
        var dModule = api.getNode();
        var dLink = YUD.getElementsByClassName('load-link', 'a', dModule)[0];
        var oButton = new YAHOO.widget.Button(dLink);
        var onButtonClick = function(oEvent) {
            YUE.preventDefault(oEvent);
            YUC.asyncRequest('GET', 'ajax.txt', {
                success : function(o) {
                    var dBody = YUD.getElementsByClassName('bd', 'div', dModule)[0];
                    dBody.innerHTML = o.responseText;
                    oButton.destroy();
                }
            });
        }
        oButton.addListener("click", onButtonClick); 
    }
};
