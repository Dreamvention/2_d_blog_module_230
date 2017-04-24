$(document).ready(function($){
    var $grid_item = $('.bm-grid-item[animate]');

    //hide timeline blocks which are outside the viewport
    $grid_item.each(function(){
        if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
            $(this).find('.bm-grid-item-point, .bm-grid-item-body').addClass('hidden');
        }
    });

    //on scolling, show/animate timeline blocks when enter the viewport
    $(window).on('scroll', function(){
        $grid_item.each(function(){
            var animate = $(this).attr('animate');
            if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.bm-grid-item-body').hasClass('hidden') ) {
                $(this).find('.bm-grid-item-point, .bm-grid-item-body').removeClass('hidden').addClass(animate);
            }
        });
    });
});