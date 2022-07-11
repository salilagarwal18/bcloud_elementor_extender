jQuery(document).ready(function ($) {
    $('.bcloud-slider').on('input', function () {
        $(this).siblings('.bcloud-slider-value').text($(this).val());
    });
    $('.bcloud-slider').each(function(){
        $(this).siblings('.bcloud-slider-value').text($(this).val());
    })
});