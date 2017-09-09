;(function($) {

    function loadUrlChunked(href, destination, hist) {
        $("#loadinglayer").css("display", "block");
        $.get(href, {contentonly: ""}, function(data) {
            if(data.indexOf("<!-- Content Only Mode -->") === 0 && data.indexOf("document.write(") < 0) {
                var metaindex = data.indexOf("<!-- <ContentOnlyMeta>");
                if(metaindex > -1 && data.indexOf("</ContentOnlyMeta> -->") > metaindex) {
                    var meta = data.substring(metaindex + 22);
                    meta = meta.substring(0, meta.indexOf("</ContentOnlyMeta> -->"));
                    meta = JSON.parse(meta);
                    for(var metakey in meta) $(metakey).html(meta[metakey]);
                }
                $(destination).html(data);
                if(hist) history.pushState(null, "", href);
            }else location.href = href;

            $("#loadinglayer").css("display", "");
        });
    }

    $(document).ready(function() {
        $(document.body).click(function(event) {
            if(event.target.tagName.toLowerCase() != "a") return;
            var href = event.target.href;
            if(href.indexOf("/") !== 0 && href.indexOf(location.protocol + "//" + location.hostname) !== 0) return;
            if(href.indexOf("/wp-admin/") > -1) return;

            loadUrlChunked(href, "#content", true);
            return false;
        });
        window.onpopstate = function(event) {
            loadUrlChunked(document.location, "#content", false);
        };
    });
})(jQuery);
