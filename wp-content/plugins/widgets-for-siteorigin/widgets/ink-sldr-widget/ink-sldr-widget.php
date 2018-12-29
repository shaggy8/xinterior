<?php

/*
Widget Name: Inked Slider
Description: A most basic image slider to leave a great impression.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Slider_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-slider',
			__( 'Inked Slider', 'wpinked-widgets' ),
			array(
				'description' => __( 'A most basic image slider to leave a great impression.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/media-widgets/slider/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(

			'admin'            => array(
				'type'            => 'text',
				'label'           => __( 'Admin Label', 'wpinked-widgets' )
			),

			'slides'           => array(
				'type'            => 'repeater',
				'label'           => __( 'Slides' , 'wpinked-widgets' ),
				'item_name'       => __( 'Slide', 'wpinked-widgets' ),
				'item_label'      => array(
					'selector'       => "[id*='name']",
					'update_event'   => 'change',
					'value_method'   => 'val'
				),
				'fields'          => array(

					'name'           => array(
						'type'          => 'text',
						'label'         => __( 'Name', 'wpinked-widgets' ),
						'default'       => ''
					),

					'image'          => array(
						'type'          => 'media',
						'fallback'      => true,
						'label'         => __( 'Image', 'wpinked-widgets' ),
						'default'       => '',
						'library'       => 'image',
					),

				)
			),

			'settings'         => array(
				'type'            => 'section',
				'label'           => __( 'Settings' , 'wpinked-widgets' ),
				'hide'            => true,
				'fields'          => array(

					'adaptive'       => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Enable Adaptive Height', 'wpinked-widgets' ),
					),

					'autoplay'       => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Enable Autoplay', 'wpinked-widgets' ),
					),

					'autoplay-speed' => array(
						'type'          => 'number',
						'label'         => __( 'Autoplay Speed', 'wpinked-widgets' ),
						'default'       => '3000',
						'description'   => __( 'Value in milliseconds.', 'wpinked-widgets' ),
					),

					'autoplay-focus' => array(
						'type'          => 'checkbox',
						'default'       => true,
						'label'         => __( 'Pause Autoplay on Focus', 'wpinked-widgets' ),
					),

					'autoplay-hover' => array(
						'type'          => 'checkbox',
						'default'       => true,
						'label'         => __( 'Pause Autoplay on Hover', 'wpinked-widgets' ),
					),

					'arrows'         => array(
						'type'          => 'checkbox',
						'default'       => true,
						'label'         => __( 'Enable Arrow Navigation', 'wpinked-widgets' ),
					),

					'arrows-hover'   => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Show arrows only on hover', 'wpinked-widgets' ),
					),

					'prev-arrow'     => array(
						'type'          => 'icon',
						'label'         => __( 'Previous Arrow', 'wpinked-widgets' ),
					),

					'next-arrow'     => array(
						'type'          => 'icon',
						'label'         => __( 'Next Arrow', 'wpinked-widgets' ),
					),

					'dots'           => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Enable Dots Navigation', 'wpinked-widgets' ),
					),

					'dots-hover'     => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Pause Autoplay on Dots Hover', 'wpinked-widgets' ),
					),

					'fade'           => array(
						'type'          => 'checkbox',
						'default'       => false,
						'label'         => __( 'Enable Fade Transitions', 'wpinked-widgets' ),
					),

					'infinite'       => array(
						'type'          => 'checkbox',
						'default'       => true,
						'label'         => __( 'Enable Infinite Loop', 'wpinked-widgets' ),
					),

					'caption'       => array(
						'type'          => 'checkbox',
						'default'       => true,
						'label'         => __( 'Show Caption', 'wpinked-widgets' ),
					),

					'caption-icon'     => array(
						'type'          => 'icon',
						'label'         => __( 'Caption Icon', 'wpinked-widgets' ),
					),

				)
			),

			'styling'          => array(
				'type'            => 'section',
				'label'           => __( 'Styling' , 'wpinked-widgets' ),
				'hide'            => true,
				'fields'          => array(

					'icon-color'     => array(
						'type'          => 'color',
						'label'         => __( 'Icon Color', 'wpinked-widgets' ),
						'default'       => '#fff'
					),

					'icon-size'      => array(
						'type'          => 'measurement',
						'label'         => __( 'Icon Size', 'wpinked-widgets'),
						'default'       => '25px'
					),

					'icon-bg'        => array(
						'type'          => 'color',
						'label'         => __( 'Icon Background', 'wpinked-widgets' ),
						'default'       => '#333'
					),

					'icon-border'        => array(
						'type'          => 'color',
						'label'         => __( 'Icon Border', 'wpinked-widgets' ),
						'default'       => '#333'
					),

					'icon-border-width'      => array(
						'type'          => 'measurement',
						'label'         => __( 'Icon Border Width', 'wpinked-widgets'),
						'default'       => '0px'
					),

					'icon-padding'      => array(
						'type'          => 'measurement',
						'label'         => __( 'Icon Padding', 'wpinked-widgets'),
						'default'       => '10px'
					),

					'icon-margin'      => array(
						'type'          => 'measurement',
						'label'         => __( 'Icon Margin', 'wpinked-widgets'),
						'default'       => '0px'
					),

					'icon-shape' => array(
						'type' => 'select',
						'label' => __( 'Icon Border Radius', 'wpinked-widgets' ),
						'default' => '0',
						'options' => array(
							'0'    => __( 'Sharp', 'wpinked-widgets' ),
							'10em' => __( 'Curved', 'wpinked-widgets' )
						)
					),

					'dot-color'      => array(
						'type'          => 'color',
						'label'         => __( 'Dot Color', 'wpinked-widgets' ),
						'default'       => '#333'
					),

					'dot-size'       => array(
						'type'          => 'measurement',
						'label'         => __( 'Dot Size', 'wpinked-widgets'),
						'default'       => '18px'
					),

					'dot-position'   => array(
						'type'          => 'select',
						'label'         => __( 'Dot position', 'wpinked-widgets' ),
						'default'       => 'below',
						'options'       => array(
							'below'        => __( 'Below Slider', 'wpinked-widgets' ),
							'bottom'       => __( 'Bottom of Slider', 'wpinked-widgets' ),
						),
					),

					'caption-position'   => array(
						'type'          => 'select',
						'label'         => __( 'Caption position', 'wpinked-widgets' ),
						'default'       => 'bottom',
						'options'       => array(
							'top'          => __( 'Top', 'wpinked-widgets' ),
							'bottom'       => __( 'Bottom', 'wpinked-widgets' ),
						),
					),

					'caption-theme'   => array(
						'type'          => 'select',
						'label'         => __( 'Caption theme', 'wpinked-widgets' ),
						'default'       => 'dark',
						'options'       => array(
							'light'       => __( 'Light', 'wpinked-widgets' ),
							'dark'        => __( 'Dark', 'wpinked-widgets' ),
						),
					),

				)
			),

		);
	}

	function get_template_name( $instance ) {
		return 'slider';
	}

	function get_style_name( $instance ) {
		return 'slider';
	}

	function initialize() {

		$this->register_frontend_scripts(
			array(
				array( 'iw-slider-js', plugin_dir_url(__FILE__) . 'js/slider' . INKED_JS_SUFFIX . '.js', array( 'iw-slick-js' ), INKED_SO_VER, true )
			)
		);

		$this->register_frontend_styles(
			array(
				array( 'iw-slider-css', plugin_dir_url(__FILE__) . 'css/slider.css', array( 'iw-slick' ), INKED_SO_VER )
			)
		);

	}

	function get_less_variables( $instance ) {

		if( empty( $instance ) ) return array();

		return array(
			'icon-color'        => $instance['styling']['icon-color'],
			'icon-size'         => $instance['styling']['icon-size'],
			'icon-bg'           => $instance['styling']['icon-bg'],
			'icon-border'       => $instance['styling']['icon-border'],
			'icon-border-width' => $instance['styling']['icon-border-width'],
			'icon-padding'      => $instance['styling']['icon-padding'],
			'icon-margin'       => $instance['styling']['icon-margin'],
			'icon-shape'        => $instance['styling']['icon-shape'],
			'dot-color'         => $instance['styling']['dot-color'],
			'dot-size'          => $instance['styling']['dot-size'],
			'dot-position'      => $instance['styling']['dot-position'],
			'caption-position'  => $instance['styling']['caption-position'],
			'caption-theme'     => $instance['styling']['caption-theme'],
		);

	}

}

siteorigin_widget_register( 'ink-slider', __FILE__, 'Inked_Slider_SO_Widget' );
