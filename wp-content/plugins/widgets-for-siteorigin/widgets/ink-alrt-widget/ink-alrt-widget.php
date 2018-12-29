<?php

/*
Widget Name: Inked Alert
Description: Communicate success, warnings, failure or just information.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Alert_SO_Widget extends SiteOrigin_Widget {

	function __construct() {
		parent::__construct(

			'ink-alert',
			__( 'Inked Alert', 'wpinked-widgets' ),
			array(
				'description' => __( 'Communicate success, warnings, failure or just information.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/basic-widgets/alert/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(
			'message'      => array(
				'type'        => 'text',
				'label'       => __( 'Message', 'wpinked-widgets' ),
				'default'     => 'This is an Alert Message'
			),

			'close'        => array(
				'type'        => 'checkbox',
				'label'       => __( 'Show Close Button ?', 'wpinked-widgets' ),
				'default'     => true
			),

			'icon'         => array(
				'type'        => 'section',
				'label'       => __( 'Icon' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'select'     => array(
						'type'      => 'icon',
						'label'     => __( 'Select Icon', 'wpinked-widgets' ),
					),

					'color'      => array(
						'type'      => 'color',
						'label'     => __( 'Icon Color', 'wpinked-widgets' ),
						'default'   => '#fff'
					),

				)
			),

			'styling'      => array(
				'type'        => 'section',
				'label'       => __( 'Styling' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'theme'      => array(
						'type'      => 'select',
						'label'     => __( 'Theme', 'wpinked-widgets' ),
						'default'   => 'classic',
						'options'   => array(
							'classic'  => __( 'Classic', 'wpinked-widgets' ),
							'flat'     => __( 'Flat', 'wpinked-widgets' ),
							'outline'  => __( 'Outline', 'wpinked-widgets' ),
							'threed'   => __( '3D', 'wpinked-widgets' ),
							'shadow'   => __( 'Shadow', 'wpinked-widgets' ),
							'modern'   => __( 'Modern', 'wpinked-widgets' ),
						),
					),

					'background' => array(
						'type'      => 'color',
						'label'     => __( 'Background Color', 'wpinked-widgets' ),
						'default'   => '#333'
					),

					'font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Font', 'wpinked-widgets' ),
					),

					'text'       => array(
						'type'      => 'color',
						'label'     => __( 'Message Color', 'wpinked-widgets' ),
						'default'   => '#fff'
					),

					'close'      => array(
						'type'      => 'color',
						'label'     => __( 'Close Color', 'wpinked-widgets' ),
						'default'   => '#fff'
					),

					'close-bg'      => array(
						'type'      => 'color',
						'label'     => __( 'Close Background Color', 'wpinked-widgets' ),
						'default'   => ''
					),

					'close-radius'      => array(
						'type'      => 'select',
						'label'     => __( 'Close Background Shape', 'wpinked-widgets' ),
						'default'   => '0em',
						'options'   => array(
							'0em'      => __( 'Sharp', 'wpinked-widgets' ),
							'0.25em'   => __( 'Slightly curved', 'wpinked-widgets' ),
							'1em'   => __( 'Round', 'wpinked-widgets' ),
						),
					),

					'corners'    => array(
						'type'      => 'select',
						'label'     => __( 'Corners', 'wpinked-widgets' ),
						'default'   => '0.25em',
						'options'   => array(
							'0em'      => __( 'Sharp', 'wpinked-widgets' ),
							'0.25em'   => __( 'Slightly curved', 'wpinked-widgets' ),
							'0.75em'   => __( 'Highly curved', 'wpinked-widgets' ),
						),
					),

					'size'       => array(
						'type'      => 'select',
						'label'     => __( 'Size', 'wpinked-widgets' ),
						'default'   => 'standard',
						'options'   => array(
							'small'    => __( 'Small', 'wpinked-widgets' ),
							'standard' => __( 'Standard', 'wpinked-widgets' ),
							'large'    => __( 'Large', 'wpinked-widgets' ),
						),
					),

				)
			),

			'attributes'         => array(
				'type'        => 'section',
				'label'       => __( 'Attributes' , 'wpinked-widgets' ),
				'hide'        => true,
				'fields'      => array(

					'main'     => array(
						'type'      => 'text',
						'label'     => __( 'Main Alert Class', 'wpinked-widgets' ),
					),

					'message'     => array(
						'type'      => 'text',
						'label'     => __( 'Message Class', 'wpinked-widgets' ),
					),

					'close'     => array(
						'type'      => 'text',
						'label'     => __( 'Close Class', 'wpinked-widgets' ),
					),
				)
			),
		);
	}

	function get_template_name( $instance ) {
		return 'alert';
	}

	function get_style_name( $instance ) {
		return 'alert';
	}

	function initialize() {

		$this->register_frontend_scripts(
			array(
				array( 'iw-alert-js', plugin_dir_url(__FILE__) . 'js/alert' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true )
			)
		);

		$this->register_frontend_styles(
			array(
				array( 'iw-alert-css', plugin_dir_url(__FILE__) . 'css/alert.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables( $instance ) {

		if( empty( $instance ) ) return array();

		$less_variables = array(
			'icon-color'       => $instance['icon']['color'],
			'size'             => $instance['styling']['size'],
			'radius'           => $instance['styling']['corners'],
			'text-color'       => $instance['styling']['text'],
			'background'       => $instance['styling']['background'],
			'theme'            => $instance['styling']['theme'],
			'close-color'      => $instance['styling']['close'],
			'close-background' => $instance['styling']['close-bg'],
			'close-radius'     => $instance['styling']['close-radius'],
		);
		if ( $instance['styling']['font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['styling']['font'] );
			$less_variables['font-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['font-wt'] = $selected_font['weight'];
			}
		}
		return $less_variables;

	}

	function get_template_variables( $instance, $args ) {

		if( empty( $instance ) ) return array();

		return array(
			'size'          => $instance['styling']['size'],
			'icon'          => $instance['icon']['select'],
			'message'       => $instance['message'],
			'close'         => $instance['close'],
			'class_main'    => $instance['attributes']['main'],
			'class_message' => $instance['attributes']['message'],
			'class_close'   => $instance['attributes']['close'],
		);
	}

	function get_google_font_fields( $instance ) {
		if( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['font'] ) {
			$fonts[] = $instance['styling']['font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-alert', __FILE__, 'Inked_Alert_SO_Widget' );
