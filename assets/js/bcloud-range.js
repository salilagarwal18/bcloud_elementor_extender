jQuery(document).ready(bcloud_range_update_value());


function bcloud_range_update_value(){
    console.log('i was runned-bcloud_range_update_value')
    jQuery('.bcloud-range-field').on('input', function () {
        jQuery(this).siblings('.bcloud-range-value').text(jQuery(this).val());
    });
    jQuery('.bcloud-range-field').each(function(){
        jQuery(this).siblings('.bcloud-range-value').text(jQuery(this).val());
    })
}