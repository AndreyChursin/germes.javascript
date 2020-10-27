if ($('div').is('.read_more')) {
    var getHeightDiv = $('.read_more').children('div');
    if ($(getHeightDiv).outerHeight() > 160) {
        $(getHeightDiv).addClass('hidden8').after('<span class="readmore">ЧИТАТЬ ДАЛЕЕ</span>');
    }
    $('span.readmore').on('click', function () {
        $(this).prev('div.hidden8').removeClass('hidden8');
        $(this).remove();
    });
}
