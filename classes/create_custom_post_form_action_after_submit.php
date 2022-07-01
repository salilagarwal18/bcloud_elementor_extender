<?php
/**
 * Class Create_Custom_Post_Form_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add, edit and delete a post
 * Sendy list via API 
 */
class Create_Custom_Post_Form_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'create_custom_post';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Create Custom Post', 'text-domain' );
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		if ( !current_user_can( 'edit_posts' ) ) {
			return;
		}
		$current_user = wp_get_current_user();
		$settings = $record->get( 'form_settings' );

		$post_type = 'post';
		if ( !empty( $settings['post_type'] ) ) {
			$post_type = $settings['post_type'];
		}

		// return if post type does not exists.
		if ( !post_type_exists($post_type) ){
			$ajax_handler->add_error_message('post type does not exist!');
			return;
		}

		// Get sumitetd Form data
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		//  Make sure that there is a title or post id
		if ( empty( $fields[ $settings['post_title'] ] ) && empty( $fields[ $settings['post_id'] ] ) ) {
			$ajax_handler->add_error_message('post title and post id both cannot be empty');
			return;
		}
		$post_title = $fields[ $settings['post_title'] ];

		$post_content = $fields[ $settings['post_content'] ];

		$customFieldsLen = count($settings['post_custom_fields']);
		$meta_input = array();
        for($index = 0; $index < $customFieldsLen; $index++){
            $custom_elementor_id = $settings['post_custom_fields'][$index]['custom_field_elementor_id'];
            $custom_acf_id = $settings['post_custom_fields'][$index]['custom_field_acf_id'];
			$meta_input[$custom_acf_id] = $fields[$custom_elementor_id];
        }
		$post_arg = array( 
			'post_type' => $post_type, // here is my custom post type, you can change it to your own type there.
			'meta_input' => $meta_input,
		);
		
		if ( !empty($post_content) ){
			$post_arg['post_content'] = $post_content;
		}
		
		if ( !empty($post_title) ){
			$post_arg['post_title'] = $post_title;
		}

		if ( !empty( $fields[ $settings['post_id'] ] ) ){
			$post_id = $fields[ $settings['post_id'] ];
			if (get_post_type((int)$post_id) != $post_type){
				return;
			}

			$post_obj = get_post((int)$post_id);
			if ($current_user->ID == $post_obj->post_author OR current_user_can('edit_others_posts')){
				$post_arg['ID'] = $post_id;
				wp_update_post($post_arg);
			}
		}
		else {
			$post_arg['post_status'] = 'publish'; /// make it also dynamic - added by Salil on 20 Oct 2021
			$bcloud_post_id = wp_insert_post( $post_arg );
		}
		return;
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_create_custom_post',
			[
				'label' => __( 'Create Custom Post', 'text-domain' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'post',
				'label_block' => true, // make the input field full width
				'separator' => 'before',
				'description' => __( 'Enter the Post Type of the post you wanted to create.', 'text-domain' ),
			]
		);

		$widget->add_control(
			'post_id',
			[
				'label' => __( 'Post ID Elementor Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'description' => __( 'Enter the ID of elementor form field for Post ID. Left it empty for new posts.', 'text-domain' ),
			]
		);

		$widget->add_control(
			'post_title',
			[
				'label' => __( 'Post Title Elementor Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of elementor form field for Post title', 'text-domain' ),
			]
		);

		$widget->add_control(
			'post_content',
			[
				'label' => __( 'Post Content Elementor Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the ID of elementor form field for Post content', 'text-domain' ),
			]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
            'custom_field_elementor_id', [
                'label' => __( 'Elementor field Id', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Enter the id of the Elementor form field', 'text-domain' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'custom_field_acf_id', [
                'label' => __( 'ACF field id', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Enter the ID of the ACF field', 'text-domain' ),
                'label_block' => true,
            ]
        );

        $widget->add_control(
            'post_custom_fields',
            [
                'label' => __( 'ACF Custom Fields', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ custom_field_elementor_id }}}',
                'separator' => 'before'
            ]
        );
		$widget->end_controls_section();

	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['post_id'],
			$element['post_type'],
			$element['post_title'],
			$element['post_content'],
			$element['post_custom_fields'],
			$element['custom_field_elementor_id'],
			$element['custom_field_acf_id']
		);
	}
}

