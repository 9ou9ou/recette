$(document).ready(function() {
    $('li').mouseleave(function() {
        var current = $(this);
        console.log(current);
        $('li').each(function(index) {
            $(this).addClass("hovered-stars");
            if (index == current.index()) {
                return false;
            }
        });
    });
    $('li').mouseleave(function() {
        $(this).removeClass("hovered-stars");
    });
    $('li').click(function() {
        $(this).removeClass("clicked-stars");
        $('.hovered-stars').addClass("cliked-stars");
    });
});