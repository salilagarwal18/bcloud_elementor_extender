jQuery(document).ready(function ($) {
    elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/range', bcloud_render_range_in_preview, 10, 4 );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', bcloud_range_update_value );
});

function bcloud_render_range_in_preview(inputField, item, i, settings ){
    //console.log(item);
    result = '<input type="range" class="bcloud-range-field elementor-field" min="' + item.range_min + '" max="' + item.range_max + '" step="' + item.range_step + '" value="' + item.range_max / 2 + '">' 
            + '<label class="bcloud-range-value">' + item.range_max / 2 + '</label>';
    return result;
}