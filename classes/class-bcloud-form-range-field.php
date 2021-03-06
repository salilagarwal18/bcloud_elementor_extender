<?php

/**
 * Class Bcloud_Form_range_Field
 * Custom elementor form Range field
 */
class Bcloud_Form_Range_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
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
        return 'range';
    }

    public function update_controls($widget)
    {
        $elementor = \ElementorPro\Plugin::elementor();

        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
        //var_dump($control_data['fields']['form_fields_advanced_tab']);echo '<br><br>';
        //var_dump($control_data['fields']['field_value']);
        if (is_wp_error($control_data)) {
            return;
        }

        $field_controls = [
            'bcloud_range_min' => [
                'name' => 'bcloud_range_min',
                'label' => esc_html__('Min. Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_range_max' => [
                'name' => 'bcloud_range_max',
                'label' => esc_html__('Max. Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_range_step' => [
                'name' => 'bcloud_range_step',
                'label' => esc_html__('Step Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_range_default' => [
                'name' => 'bcloud_range_default',
                'label' => esc_html__('Default Value', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'advanced',
                'inner_tab' => 'form_fields_advanced_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_range_before' => [
                'name' => 'bcloud_range_before',
                'label' => esc_html__('Before', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_range_after' => [
                'name' => 'bcloud_range_after',
                'label' => esc_html__('After', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
        ];

        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        //$control_data['fields']['field_value']['value'] = $this->inject_field_controls($control_data['field_value']['value'], 'range');
        $widget->update_control('form_fields', $control_data);
    }


    public function render($item, $item_index, $form)
    {
        //echo (int) 'ss';
        //var_dump($item);
        $form->add_render_attribute('input' . $item_index, 'type', 'range', true);
        if (isset($item['bcloud_range_min'])){
            $form->add_render_attribute('input' . $item_index, 'min', $item['bcloud_range_min'], true);
        }
        
        if (isset($item['bcloud_range_max'])){
            $form->add_render_attribute('input' . $item_index, 'max', $item['bcloud_range_max'], true);
        }
        
        if (isset($item['bcloud_range_default'])){
            $form->add_render_attribute('input' . $item_index, 'value', $item['bcloud_range_default'], true);
            $form->add_render_attribute('input' . $item_index, 'default', $item['bcloud_range_default'], true);
        }

        if (isset($item['bcloud_range_step'])){
            $form->add_render_attribute('input' . $item_index, 'step', $item['bcloud_range_step'], true);
        }
        
        $form->add_render_attribute('input' . $item_index, 'data-before-range', $item['bcloud_range_before'], true);
        $form->add_render_attribute('input' . $item_index, 'data-after-range', $item['bcloud_range_after'], true);

        $form->add_render_attribute('input' . $item_index, 'class', 'bcloud-range-field', true);
?>

        <input <?php $form->print_render_attribute_string('input' . $item_index); ?>>
        <label class="bcloud-range-value elementor-field-label"><?php echo $item['bcloud_range_before'] . $item['bcloud_range_default'] . $item['bcloud_range_after']; ?></label>

<?php
    }

    public function add_preview_depends()
    {
        wp_enqueue_script(
            'bcloud-range',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range.js',
            'jquery',
            microtime(),
            true
        );
        wp_enqueue_script(
            'bcloud-range-preview',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range-preview.js',
            'bcloud-range',
            microtime(),
            true
        );
        
        wp_enqueue_style(
            'bcloud-range-field',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-range-field.css',
            '',
            microtime()
        );
    }

    public function add_assets_depends($form)
    {
        wp_enqueue_script(
            'bcloud-range',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-range.js',
            'jquery',
            microtime(),
            true
        );
        
        wp_enqueue_style(
            'bcloud-range-field',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-range-field.css',
            '',
            microtime()
        );
    }
    
    public function validation( $field, $record, $ajax_handler ) {
        return;
        //var_dump($field);
        //var_dump($record->get( 'form_settings' ));*/

		if ( ! empty( $field['bcloud_range_max'] ) && ( ! is_numeric($field['value']) || $field['bcloud_range_max'] < (int) $field['value'] ) ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be less than or equal to %s', 'bcloud-elementor-extender' ), $field['bcloud_range_max'] ) );
		}

		if ( ! empty( $field['bcloud_range_min'] ) && $field['bcloud_range_min'] > (int) $field['value'] ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be greater than or equal %s', 'bcloud-elementor-extender' ), $field['bcloud_range_min'] ) );
		}

	}
}
