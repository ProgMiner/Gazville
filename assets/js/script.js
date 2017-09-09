$(document).ready(function() {
    $("#sidebar > a.expand").click(function() {
        $("#sidebar").toggleClass("expanded");
    });
    $("#sidebar > a.search").click(function() {
        var sidebar = $("#sidebar");
        if(!sidebar.hasClass("expanded"))
            sidebar.addClass("expanded");
    });
});