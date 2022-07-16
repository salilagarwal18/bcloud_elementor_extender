jQuery(document).ready(function ($) {
    elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/calculator', bcloud_render_calcultor_in_preview, 10, 4 );
    elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', bcloud_calculator_init );
});

function bcloud_render_calcultor_in_preview(inputField, item, i, settings ){
    //console.log(item);
    var result = '<input type="text" class="bcloud-calculator-field elementor-field elementor-size-sm  elementor-field-textual" disabled>';
    return result;
}