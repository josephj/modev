miii.module.types["ad"] = {
    init: function (api) {
        var dModule = api.getNode();
        var dLink = YUD.getElementsByClassName('view-link')[0];
        var dImage = dModule.getElementsByTagName('img')[0];
        var oButton = new YAHOO.widget.Button(dLink);
        var oPanel = null;
        var onButtonClick = function(oEvent) {
            YUE.stopEvent(oEvent);
            if (!oPanel) {
                var dPanel = document.createElement('div');
                dPanel.innerHTML = [
                    '<div class="hd">觀看圖片</div>',
                    '<div class="bd"><img src="' + dImage.src.replace('_m', '') + '" width="500" height="375" alt="YDN 2009 筆記本"></div>'
                ].join('');
                document.body.appendChild(dPanel);
                oPanel = new YAHOO.widget.Panel(dPanel, {
                    close: true, 
                    constraintoviewport: true,  
                    draggable: true,
                    underlay:'shadow', 
                    visible: false,
                    width:'600px',
                    context:[dImage, 'tr', 'br'] 
                });
                oPanel.render();
            }
            oPanel.show();
        }
        oButton.addListener("click", onButtonClick); 
    }, 
    onmessage: function () {

    }
};
