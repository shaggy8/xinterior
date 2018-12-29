<?php

/*
Widget Name: Inked Divider/Gap
Description: Draw seperators or create gaps in your page.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Divider_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-divider',
			__( 'Inked Divider', 'wpinked-widgets' ),
			array(
				'description' => __( 'Draw seperators or create gaps in your page.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/basic-widgets/divider/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(

			'type'            => array(
				'type'           => 'select',
				'label'          => __( 'Type', 'wpinked-widgets' ),
				'default'        => 'none',
				'options'        => array(
					'none'          => __( 'Gap', 'wpinked-widgets' ),
					'solid'         => __( 'Single Line', 'wpinked-widgets' ),
					'double'        => __( 'Double Line', 'wpinked-widgets' ),
					'dotted'        => __( 'Dotted Line', 'wpinked-widgets' ),
					'dashed'        => __( 'Dashed Line', 'wpinked-widgets' )
				)
			),

			'styling'         => array(
				'type'           => 'section',
				'label'          => __( 'Styling' , 'wpinked-widgets' ),
				'hide'           => true,
				'fields'         => array(

					'color'         => array(
						'type'         => 'color',
						'label'        => __( 'Color', 'wpinked-widgets' ),
						'description'  => __( 'Select the color of your divider.', 'wpinked-widgets' ),
						'default'      => '#333'
					),

					'size'          => array(
						'type'         => 'measurement',
						'label'        => __( 'Thickness', 'wpinked-widgets' ),
						'default'      => '3px'
					),

					'width'         => array(
						'type'         => 'measurement',
						'label'        => __( 'Width', 'wpinked-widgets' ),
						'default'      => '100%'
					),

					'margin-top'    => array(
						'type'         => 'measurement',
						'label'        => __( 'Margin Top', 'wpinked-widgets' ),
						'default'      => '0'
					),

					'margin-bottom' => array(
						'type'         => 'measurement',
						'label'        => __( 'Margin Bottom', 'wpinked-widgets' ),
						'default'      => '0'
					),

				)
			),

			'attributes'         => array(
				'type'           => 'section',
				'label'          => __( 'Attributes' , 'wpinked-widgets' ),
				'hide'           => true,
				'fields'         => array(

					'divider'         => array(
						'type'         => 'text',
						'label'        => __( 'Divider Class', 'wpinked-widgets' ),
					),

				)
			),
		);
	}

	function get_template_name( $instance ) {
		return 'divider';
	}

	function get_style_name( $instance ) {
		return 'divider';
	}

	function get_less_variables( $instance ) {
		if( empty( $instance ) ) return array();

		return array(
			'type'  => $instance['type'],
			'color' => $instance['styling']['color'],
			'size'  => $instance['styling']['size'],
			'width' => $instance['styling']['width'],
			'm-top' => $instance['styling']['margin-top'],
			'm-btm' => $instance['styling']['margin-bottom']
		);
	}

	function get_template_variables( $instance, $args ) {

		if( empty( $instance ) ) return array();

		return array(
			'class_divider' => $instance['attributes']['divider'],
		);
	}

}

siteorigin_widget_register( 'ink-divider', __FILE__, 'Inked_Divider_SO_Widget' );
