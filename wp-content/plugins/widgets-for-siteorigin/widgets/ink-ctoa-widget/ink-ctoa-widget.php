<?php

/*
Widget Name: Inked Call to Action
Description: Simple and effective CTA.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Call_To_Action_SO_Widget extends SiteOrigin_Widget {

	function __construct() {
		parent::__construct(

			'ink-call-to-action',
			__( 'Inked Call to Action', 'wpinked-widgets' ),
			array(
				'description' => __( 'Simple and effective CTA.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/content-widgets/call-to-action/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(

			'text'         => array(
				'type'        => 'section',
				'label'       => __( 'Text' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'title'      => array(
						'type'        => 'text',
						'label'       => __( 'Title', 'wpinked-widgets' ),
						'default'     => ''
					),

					'subtitle'      => array(
						'type'        => 'text',
						'label'       => __( 'Subtitle', 'wpinked-widgets' ),
						'default'     => ''
					),

				)
			),

			'styling'         => array(
				'type'        => 'section',
				'label'       => __( 'Styling' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'orientation'      => array(
						'type'        => 'select',
						'label'       => __( 'Orientation', 'wpinked-widgets' ),
						'default'     => 'horizontal',
						'options'     => array(
							'horizontal'   => __( 'Horizontal', 'wpinked-widgets' ),
							'vertical'     => __( 'Vertical', 'wpinked-widgets' ),
						),
					),

					'background' => array(
						'type'      => 'color',
						'label'     => __( 'Background Color', 'wpinked-widgets' ),
						'default'   => '#fff'
					),

					'align'        => array(
						'type'        => 'select',
						'label'       => __( 'Text Alignment', 'wpinked-widgets' ),
						'default'     => 'left',
						'options'     => array(
							'left'       => __( 'Left', 'wpinked-widgets' ),
							'right'      => __( 'Right', 'wpinked-widgets' ),
							'center'     => __( 'Center', 'wpinked-widgets' ),
						),
					),

					'title_font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Title Font', 'wpinked-widgets' ),
					),

					'title_color'       => array(
						'type'      => 'color',
						'label'     => __( 'Title Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'title_size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Title Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'subtitle_font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Subtitle Font', 'wpinked-widgets' ),
					),

					'subtitle_color'       => array(
						'type'      => 'color',
						'label'     => __( 'Subtitle Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'subtitle_size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Subtitle Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

				)
			),

			'btn-styling'         => array(
				'type'        => 'section',
				'label'       => __( 'Button Styles' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					// 'fullwidth'        => array(
					// 	'type'        => 'checkbox',
					// 	'default'     => true,
					// 	'label'       => __( 'Fullwidth ?', 'wpinked-widgets' ),
					// ),

					'theme'        => array(
						'type'        => 'select',
						'label'       => __( 'Theme', 'wpinked-widgets' ),
						'default'     => 'classic',
						'options'     => array(
							'classic'    => __( 'Classic', 'wpinked-widgets' ),
							'flat'       => __( 'Flat', 'wpinked-widgets' ),
							'outline'    => __( 'Outline', 'wpinked-widgets' ),
							'threed'     => __( '3D', 'wpinked-widgets' ),
							'shadow'     => __( 'Shadow', 'wpinked-widgets' ),
							'deline'     => __( 'Deline', 'wpinked-widgets' ),
						),
					),

					'font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Font', 'wpinked-widgets' ),
					),

					'hover'        => array(
						'type'        => 'checkbox',
						'default'     => true,
						'label'       => __( 'Use hover effect ?', 'wpinked-widgets' ),
					),

					'click'        => array(
						'type'        => 'checkbox',
						'default'     => true,
						'label'       => __( 'Use click effect ?', 'wpinked-widgets' ),
					),

					'corners'      => array(
						'type'        => 'select',
						'label'       => __( 'Corners', 'wpinked-widgets' ),
						'default'     => '0.25em',
						'options'     => array(
							'0em'        => __( 'Sharp', 'wpinked-widgets' ),
							'0.25em'     => __( 'Slightly curved', 'wpinked-widgets' ),
							'0.75em'     => __( 'Highly curved', 'wpinked-widgets' ),
							'1.5em'      => __( 'Round', 'wpinked-widgets' ),
						),
					),

					'size'         => array(
						'type'        => 'select',
						'label'       => __( 'Size', 'wpinked-widgets' ),
						'default'     => 'standard',
						'options'     => array(
							'tiny'       => __( 'Tiny', 'wpinked-widgets' ),
							'small'      => __( 'Small', 'wpinked-widgets' ),
							'standard'   => __( 'Standard', 'wpinked-widgets' ),
							'large'      => __( 'Large', 'wpinked-widgets' ),
						),
					),

				)
			),

			'btn-1'         => array(
				'type'        => 'section',
				'label'       => __( 'Button 1' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'text'           => array(
						'type'          => 'text',
						'label'         => __( 'Button text', 'wpinked-widgets' ),
					),

					'url'            => array(
						'type'          => 'link',
						'label'         => __( 'Destination URL', 'wpinked-widgets' ),
					),

					'new_window'     => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Open in a new window', 'wpinked-widgets' ),
					),

					'icon'       => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'wpinked-widgets' ),
					),

					'icon_location'     => array(
						'type'        => 'select',
						'label'       => __( 'Icon Location', 'wpinked-widgets' ),
						'default'     => 'left',
						'options'     => array(
							'left'       => __( 'Before Text', 'wpinked-widgets' ),
							'right'      => __( 'After Text', 'wpinked-widgets' ),
							'above'      => __( 'Above Text', 'wpinked-widgets' )
						)
					),

					'button_color' => array(
						'type'        => 'color',
						'label'       => __( 'Highlight Color', 'wpinked-widgets' ),
						'description' => __( 'Typically used as button background.', 'wpinked-widgets' ),
					),

					'text_color'   => array(
						'type'        => 'color',
						'label'       => __( 'Base Color', 'wpinked-widgets' ),
						'description' => __( 'Typically used as text color.', 'wpinked-widgets' ),
					),

					'id'           => array(
						'type'        => 'text',
						'label'       => __( 'Button ID', 'wpinked-widgets' ),
						'description' => __( 'An ID attribute allows you to target this button in Javascript.', 'wpinked-widgets' ),
					),

					'title'        => array(
						'type'        => 'text',
						'label'       => __( 'Title attribute', 'wpinked-widgets' ),
						'description' => __( 'Adds a title attribute to the button link.', 'wpinked-widgets' ),
					),

					'onclick'      => array(
						'type'        => 'text',
						'label'       => __( 'Onclick', 'wpinked-widgets' ),
						'description' => __( 'Run this Javascript when the button is clicked. Ideal for tracking.', 'wpinked-widgets' ),
					),

					'class'           => array(
						'type'        => 'text',
						'label'       => __( 'Button Class', 'wpinked-widgets' ),
						'description' => __( 'A Class attribute allows you to style this button with CSS.', 'wpinked-widgets' ),
					),

				)
			),

			'btn-2'         => array(
				'type'        => 'section',
				'label'       => __( 'Button 2' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'text'           => array(
						'type'          => 'text',
						'label'         => __( 'Button text', 'wpinked-widgets' ),
					),

					'url'            => array(
						'type'          => 'link',
						'label'         => __( 'Destination URL', 'wpinked-widgets' ),
					),

					'new_window'     => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Open in a new window', 'wpinked-widgets' ),
					),

					'icon'       => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'wpinked-widgets' ),
					),

					'icon_location'     => array(
						'type'        => 'select',
						'label'       => __( 'Icon Location', 'wpinked-widgets' ),
						'default'     => 'left',
						'options'     => array(
							'left'       => __( 'Before Text', 'wpinked-widgets' ),
							'right'      => __( 'After Text', 'wpinked-widgets' ),
							'above'      => __( 'Above Text', 'wpinked-widgets' )
						)
					),

					'button_color' => array(
						'type'        => 'color',
						'label'       => __( 'Highlight Color', 'wpinked-widgets' ),
						'description' => __( 'Typically used as button background.', 'wpinked-widgets' ),
					),

					'text_color'   => array(
						'type'        => 'color',
						'label'       => __( 'Base Color', 'wpinked-widgets' ),
						'description' => __( 'Typically used as text color.', 'wpinked-widgets' ),
					),

					'id'           => array(
						'type'        => 'text',
						'label'       => __( 'Button ID', 'wpinked-widgets' ),
						'description' => __( 'An ID attribute allows you to target this button in Javascript.', 'wpinked-widgets' ),
					),

					'title'        => array(
						'type'        => 'text',
						'label'       => __( 'Title attribute', 'wpinked-widgets' ),
						'description' => __( 'Adds a title attribute to the button link.', 'wpinked-widgets' ),
					),

					'onclick'      => array(
						'type'        => 'text',
						'label'       => __( 'Onclick', 'wpinked-widgets' ),
						'description' => __( 'Run this Javascript when the button is clicked. Ideal for tracking.', 'wpinked-widgets' ),
					),

					'class'           => array(
						'type'        => 'text',
						'label'       => __( 'Button Class', 'wpinked-widgets' ),
						'description' => __( 'A Class attribute allows you to style this button with CSS.', 'wpinked-widgets' ),
					),

				)
			),
		);
	}

	function get_template_name( $instance ) {
		return 'call-to-action';
	}

	function get_style_name( $instance ) {
		return 'call-to-action';
	}

	function initialize() {

		$this->register_frontend_styles(
			array(
				array( 'iw-call-to-action-css', plugin_dir_url(__FILE__) . 'css/call-to-action.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables( $instance ) {

		if( empty( $instance ) ) return array();

		$less_variables = array(
			'background'     => $instance['styling']['background'],
			'title-color'    => $instance['styling']['title_color'],
			'title-size'     => $instance['styling']['title_size'],
			'subtitle-color' => $instance['styling']['subtitle_color'],
			'subtitle-size'  => $instance['styling']['subtitle_size'],
			// 'btn-fullwidth'  => $instance['btn-styling']['fullwidth'],
			'btn-theme'      => $instance['btn-styling']['theme'],
			'btn-corners'    => $instance['btn-styling']['corners'],
			'btn-size'       => $instance['btn-styling']['size'],
			'btn-1-h-color'  => $instance['btn-1']['button_color'],
			'btn-1-t-color'  => $instance['btn-1']['text_color'],
			'btn-2-h-color'  => $instance['btn-2']['button_color'],
			'btn-2-t-color'  => $instance['btn-2']['text_color'],
			'btn-1-icon'     => $instance['btn-1']['icon_location'],
			'btn-2-icon'     => $instance['btn-2']['icon_location'],
		);
		if ( $instance['styling']['title_font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['styling']['title_font'] );
			$less_variables['title-font-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['title-font-wt'] = $selected_font['weight'];
			}
		}
		if ( $instance['styling']['subtitle_font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['styling']['subtitle_font'] );
			$less_variables['subtitle-font-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['subtitle-font-wt'] = $selected_font['weight'];
			}
		}
		if ( $instance['btn-styling']['font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['btn-styling']['font'] );
			$less_variables['btn-font-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['btn-font-wt'] = $selected_font['weight'];
			}
		}
		return $less_variables;

	}

	function get_template_variables( $instance, $args ) {

		if( empty( $instance ) ) return array();

		return array(
			'title'         => $instance['text']['title'],
			'subtitle'      => $instance['text']['subtitle'],
			'orientation'   => $instance['styling']['orientation'],
			'align'         => $instance['styling']['align'],
			'btn_hover'     => $instance['btn-styling']['hover'],
			'btn_click'     => $instance['btn-styling']['click'],
			'btn_1_text'    => $instance['btn-1']['text'],
			'btn_1_url'     => $instance['btn-1']['url'],
			'btn_1_target'  => $instance['btn-1']['new_window'],
			'btn_1_icon'    => $instance['btn-1']['icon'],
			'btn_1_id'      => $instance['btn-1']['id'],
			'btn_1_title'   => $instance['btn-1']['title'],
			'btn_1_onclick' => $instance['btn-1']['onclick'],
			'btn_1_class'   => $instance['btn-1']['class'],
			'btn_2_text'    => $instance['btn-2']['text'],
			'btn_2_url'     => $instance['btn-2']['url'],
			'btn_2_target'  => $instance['btn-2']['new_window'],
			'btn_2_icon'    => $instance['btn-2']['icon'],
			'btn_2_id'      => $instance['btn-2']['id'],
			'btn_2_title'   => $instance['btn-2']['title'],
			'btn_2_onclick' => $instance['btn-2']['onclick'],
			'btn_2_class'   => $instance['btn-2']['class'],
		);
	}

	function get_google_font_fields( $instance ) {
		if( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['title_font'] ) {
			$fonts[] = $instance['styling']['title_font'];
		}
		if ( $instance['styling']['subtitle_font'] ) {
			$fonts[] = $instance['styling']['subtitle_font'];
		}
		if ( $instance['btn-styling']['font'] ) {
			$fonts[] = $instance['btn-styling']['font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-call-to-action', __FILE__, 'Inked_Call_To_Action_SO_Widget' );
