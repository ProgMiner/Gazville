$(document).ready(function(){
    $("a#sidebarexpand").click(function(){
        $("#sidebar").toggleClass("expandedsidebar");
    });
    $("#sidebar > a.search").click(function(){
        var sidebar = $("#sidebar");
        if(!sidebar.hasClass("expandedsidebar"))
            sidebar.addClass("expandedsidebar");
    });
});