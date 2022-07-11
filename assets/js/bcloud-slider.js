jQuery(document).ready(bcloud_slider_update_value());


function bcloud_slider_update_value(){
    jQuery('.bcloud-slider').on('input', function () {
        jQuery(this).siblings('.bcloud-slider-value').text(jQuery(this).val());
    });
    jQuery('.bcloud-slider').each(function(){
        jQuery(this).siblings('.bcloud-slider-value').text(jQuery(this).val());
    })
}