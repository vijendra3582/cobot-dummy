if ($(window).width() > 991) {
    $(window).bind("load resize", function () {
        var topOffset = $('.top-menu').height();
        var height = $(document).innerHeight() - topOffset;
        $("main").css("min-height", (height) + "px");
        $('body').css("opacity", "1");
    });
} else {
    $('body').css("opacity", "1");
}

if($(window).width()<992){
    var cc = $(".nav-menu").children().clone();
    $(".navbar-nav").prepend(cc);
}
if($(window).width() > 991){
    var chatwidth = $(".comment-wrap").innerWidth() + $(".user-menu").innerWidth();
    var topgap = $(".top-menu").innerHeight();
    $("#comment-data").width(chatwidth);
    $("#comment-data").css("top", topgap);
} else {
    var topgap = $(".top-menu").innerHeight();
    $("#comment-data").width(250);
    $("#comment-data").css("top", topgap);
}
$("#comment-data").mCustomScrollbar({
    theme: "my-theme"
});
/* $("#scrollbar").mCustomScrollbar({
    theme: "my-theme"
}); */
$("#scrollbar").mCustomScrollbar({
    theme: "my-theme"
}).mCustomScrollbar("scrollTo", "bottom", { scrollInertia: 0 });



$(".comment-btn").on("click", function(){
    $("#comment-data").fadeToggle();
})

$(document).keyup(function (e) {
    if (e.keyCode == 27) {
        if ($("#comment-data").is(':visible')) {
            $("#comment-data").fadeToggle();
        }
    }
});

$(window).click(function () {
    if ($("#comment-data").is(':visible')) {
        $("#comment-data").fadeToggle();
    }
});
$('.comment-btn').click(function (event) {
    event.stopPropagation();
});

$(".acc-label").on("click", function(){
    $(".acc-detail").fadeToggle();
})

$(".select2").select2({
});

$('.hamburger').click(function (event) {
    $(this).toggleClass('h-active');
    $(".sidebar").toggleClass("menu-active");
    $("body").toggleClass("hasmenu");
});

$('.tittle-chat').click(function (event) {
    $(this).toggleClass('title-active');
    $(this).find('i').toggleClass("fa-angle-up").toggleClass("fa-angle-down");
    $(".order-comment-list").toggleClass("active-chat");
});


