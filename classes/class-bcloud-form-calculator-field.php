<?php

/**
 * Class Bcloud_Form_Calculator_Field
 * Custom elementor form Calculator field
 */
class Bcloud_Form_Calculator_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
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
        return __('Calculator', 'bcloud-elementor-extender');
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
        return 'calculator';
    }

    public function update_controls($widget)
    {
        $elementor = \ElementorPro\Plugin::elementor();

        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

        if (is_wp_error($control_data)) {
            return;
        }

        $field_controls = [
            'bcloud_calculator' => [
                'name' => 'bcloud_calculator',
                'label' => esc_html__('Formula', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_calculator_before' => [
                'name' => 'bcloud_calculator_before',
                'label' => esc_html__('Before', 'bcloud-elementor-extender'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
            'bcloud_calculator_after' => [
                'name' => 'bcloud_calculator_after',
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
        $widget->update_control('form_fields', $control_data);
    }


    public function render($item, $item_index, $form)
    {
        //var_dump($item);
        //echo '<br><br>';
        $form_settings = $form->get_settings_for_display();
        $formula = $item['bcloud_calculator'];
        //var_dump($formula);
        $formula_parts = explode(' ', $formula);
        //var_dump($formula_parts);
        $formula_parts = array_filter($formula_parts, function($value) { return !is_null($value) && $value !== ''; });
        //var_dump($formula_parts);
        $form_fields = $form_settings['form_fields'];
        //echo '<br><br>';
        //var_dump($form_fields);
        $formula_field_id = $item['custom_id'];
        $all_field_custom_ids = array();
        $all_field_custom_ids_values = array();
        foreach($form_fields as $form_field){
            if ($form_field['custom_id'] != $formula_field_id){
                array_push($all_field_custom_ids, $form_field['custom_id']);
                if ( $form_field['field_type'] == 'range' ){
                    $all_field_custom_ids_values[$form_field['custom_id']] = $form_field['bcloud_range_default'];
                }
                else{
                    $all_field_custom_ids_values[$form_field['custom_id']] = $form_field['field_value'];
                }
            }
        }
        //echo '<br><br>';
        //var_dump($all_field_custom_ids);
        $eval_string = '';
        foreach($formula_parts as $formula_part){
            if (in_array($formula_part, $all_field_custom_ids)){
                $field_value = $all_field_custom_ids_values[$formula_part];
                if (is_numeric($field_value)){
                    $eval_string .= strval($field_value);
                }
                else{
                    $eval_string .= strval(0);
                }
            }
            else if(is_numeric($formula_part)){
                $eval_string .= strval($formula_part);
            }
            else {
                switch ($formula_part){
                    case '+':
                        $eval_string .= $formula_part;
                        break;
                    case '-':
                        $eval_string .= $formula_part;
                        break;
                    case '/':
                        $eval_string .= $formula_part;
                        break;
                    case '*':
                        $eval_string .= $formula_part;
                        break;
                    case '%':
                        $eval_string .= $formula_part;
                        break;
                    case '(':
                        $eval_string .= $formula_part;
                        break;
                    case ')':
                        $eval_string .= $formula_part;
                        break;
                }
            }
        }
        //echo '<br><br>';

        //var_dump($eval_string);
        $result = '';
        try {
            $result = eval('return ' . $eval_string . ';');
        }
        catch (ParseError $e) {
            echo 'Message: ' .$e->getMessage();
        }
        $form->add_render_attribute('input' . $item_index, 'type', 'hidden', true);
        $form->add_render_attribute('input' . $item_index, 'value', $result, true);
        $form->add_render_attribute('input' . $item_index, 'class', 'bcloud-calculator-input-field', true);
        //$form->add_render_attribute('input' . $item_index, 'disabled', null, true);
        $form->add_render_attribute('input' . $item_index, 'data-formula', $formula, true);
        $form->add_render_attribute('input' . $item_index, 'data-before-formula', $item['bcloud_calculator_before'], true);
        $form->add_render_attribute('input' . $item_index, 'data-after-formula', $item['bcloud_calculator_after'], true);

?>

        <input <?php $form->print_render_attribute_string('input' . $item_index); ?>>
        <label class="elementor-field-label bcloud-calculator-field"><?php echo $item['bcloud_calculator_before'] . $result . $item['bcloud_calculator_after'] ; ?></label>

<?php
        //echo '<br><br>';
        //var_dump($form_settings);

        //echo 'hello';
    }

    public function add_preview_depends()
    {
        wp_enqueue_script(
            'bcloud-calculator',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field.js',
            'jquery',
            microtime(),
            true
        );
        wp_enqueue_script(
            'bcloud-calculator-preview',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field-preview.js',
            'bcloud-calculator',
            microtime(),
            true
        );
        
        wp_enqueue_style(
            'bcloud-calculator-field',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-calculator-field.css',
            '',
            microtime()
        );
    }

    public function add_assets_depends($form)
    {
        wp_enqueue_script(
            'bcloud-calculator-field',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/js/bcloud-calculator-field.js',
            'jquery',
            microtime(),
            true
        );
        
        wp_enqueue_style(
            'bcloud-calculator-field',
            BCLOUD_ELEMENTOR_EXTENDER_URL . 'assets/css/bcloud-calculator-field.css',
            '',
            microtime()
        );
    }
}
