$(document).ready(function() {
    $(document).ready(function() {
        $('button').click(function() {
            $(this).addClass('bounce-in');
            $(this).find('i').addClass('flyaway');
            $(this).find('span').text(function(span, text) {
                return "Success!";
            }).delay(1000).queue(function(next) {
                $('button').removeClass('bounce-in');
                $('button').find('i').removeClass('flyaway');
                $('button').find('span').text(function(span, text) {
                    return "Download";
                });
                next();
            });
        });
    });
    $('#demo').click(function() {
        $(this).removeClass('shadow');
        $(this).removeClass('float').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
            $('#demo').addClass('shadow');
            $('#demo').addClass('float');
        });
        $(this).addClass('flyaway').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
            $('#demo').removeClass('flyaway');
        });
    });
});