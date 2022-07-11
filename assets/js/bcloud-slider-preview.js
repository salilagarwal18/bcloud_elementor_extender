jQuery(document).ready(function ($) {
    elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/slider', bcloud_render_slider_in_preview, 10, 4 );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', bcloud_slider_update_value );
});

function bcloud_render_slider_in_preview(inputField, item, i, settings ){
    //console.log(item);
    result = '<input type="range" class="bcloud-slider" min="' + item.slider_min + '" max="' + item.slider_max + '" step="' + item.slider_step + '" value="' + item.slider_max / 2 + '">' 
            + '<label class="bcloud-slider-value">' + item.slider_max / 2 + '</label>';
    return result;
}