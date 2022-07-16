<?php

/**
 * Class Bcloud_Form_Slider_Field
 * Custom elementor form Range field
 */
class Bcloud_Form_Slider_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
{

    /**
     * Get Name
     *
     * Return the action name
     *
     * @access public
     * @return string
     */
    public function get_name()
    {
        return __('Range', 'bcloud-elementor-extender');
    }

    /**
     * Get Type
     *
     * Returns the action label
     *
     * @access public
     * @return string
     */
    public function get_type()
    {
        return 'slider';
    }

    public function update_controls($widget)
    {
        $elementor = \ElementorPro\Plugin::elementor();

        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

        if (is_wp_error($control_data)) {
            return;
        }

        $field_controls = [
            'slider_min' => [
                'name' => 'slider_min',
                'label' => esc_html__('Min. Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'slider_max' => [
                'name' => 'slider_max',
                'label' => esc_html__('Max. Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'slider_step' => [
                'name' => 'slider_step',
                'label' => esc_html__('Step Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
        ];

        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }


    public function render($item, $item_index, $form)
    {
        $form->add_render_attribute('input' . $item_index, 'type', 'range', true);
        $form->add_render_attribute('input' . $item_index, 'min', $item['slider_min'], true);
        $form->add_render_attribute('input' . $item_index, 'max', $item['slider_max'], true);
        $form->add_render_attribute('input' . $item_index, 'value', intval($item['slider_max'] / 2), true);
        $form->add_render_attribute('input' . $item_index, 'step', $item['slider_step'], true);

        $form->add_render_attribute('input' . $item_index, 'class', 'bcloud-slider', true);

?>

        <input <?php $form->print_render_attribute_string('input' . $item_index); ?>>
        <label class="bcloud-slider-value"></label>
        <?php //var_dump($item); 
        ?>

<?php
    }

    public function add_preview_depends()
    {
        wp_enqueue_script(
            'bcloud-slider',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-slider.js',
            'jquery',
            microtime(),
            true
        );
        wp_enqueue_script(
            'bcloud-slider-preview',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-slider-preview.js',
            'bcloud-slider',
            microtime(),
            true
        );
    }

    public function add_assets_depends($form)
    {
        wp_enqueue_script(
            'bcloud-slider',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-slider.js',
            'jquery',
            microtime(),
            true
        );
    }
}
