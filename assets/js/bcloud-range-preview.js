jQuery(document).ready(function ($) {
    elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/range', bcloud_render_range_in_preview, 10, 4 );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', bcloud_range_update_value );
});

function bcloud_render_range_in_preview(inputField, item, i, settings ){
    //console.log(item);
    result = '<input type="range" class="bcloud-range-field" min="' + item.bcloud_range_min + '" max="' + item.bcloud_range_max + '" step="' + item.bcloud_range_step +
                '" value="' + item.bcloud_range_default + '" data-before-range="' + item.bcloud_range_before +
                '" data-after-range="' + item.bcloud_range_after + '">' 
            + '<label class="bcloud-range-value">' + '</label>';
    return result;
}