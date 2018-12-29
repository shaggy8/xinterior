<?php

/*
Widget Name: Inked Testimonial
Description: Highlight what your customers think of you.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Testimonial_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-testimonial',
			__( 'Inked Testimonial', 'wpinked-widgets' ),
			array(
				'description' => __( 'Highlight what your customers think of you.', 'wpinked-widgets' ),
				'help' => 'http://widgets.wpinked.com/docs/social-widgets/testimonial/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(

			'name'                   => array(
				'type'                  => 'text',
				'label'                 => __( 'Name', 'wpinked-widgets' ),
				'default'               => '',
				'description'           => __( 'Name of the testimonial author.', 'wpinked-widgets' ),
			),

			'testimonial'            => array(
				'type'                  => 'section',
				'label'                 => __( 'Testimonial' , 'wpinked-widgets' ),
				'hide'                  => true,
				'fields'                => array(

					'image'                => array(
						'type'                => 'media',
						'label'               => __( 'Image', 'wpinked-widgets' ),
						'choose'              => __( 'Choose image', 'wpinked-widgets' ),
						'update'              => __( 'Set image', 'wpinked-widgets' ),
						'library'             => 'image',
						'fallback'            => false
					),

					'company'              => array(
						'type'                => 'text',
						'label'               => __( 'Company', 'wpinked-widgets' ),
						'default'             => ''
					),

					'link'                 => array(
						'type'                => 'link',
						'label'               => __( 'Company Link', 'wpinked-widgets' ),
						'default'             => ''
					),

					'target'               => array(
						'type'                => 'select',
						'label'               => __( 'Open link in', 'wpinked-widgets' ),
						'default'             => '_blank',
						'options'             => array(
							'_self'              => __( 'Same Window', 'wpinked-widgets' ),
							'_blank'             => __( 'New Window', 'wpinked-widgets' )
						)
					),

					'content'              => array(
						'type'                => 'tinymce',
						'label'               => __( 'Content', 'wpinked-widgets' ),
					),

					'autop'                => array(
						'type'                => 'checkbox',
						'default'             => false,
						'label'               => __( 'Automatically add paragraphs', 'wpinked-widgets' ),
					),

				)
			),

			'styling'                => array(
				'type'                  => 'section',
				'label'                 => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                  => true,
				'fields'                => array(

					'design'               => array(
						'type'                => 'select',
						'label'               => __( 'Design', 'wpinked-widgets' ),
						'default'             => 'above',
						'options'             => array(
							'above'              => __( 'Image above', 'wpinked-widgets' ),
							'below'              => __( 'Image below', 'wpinked-widgets' ),
							'between'            => __( 'Image in-between', 'wpinked-widgets' ),
							'left'               => __( 'Image to the left', 'wpinked-widgets' ),
							'right'              => __( 'Image to the right', 'wpinked-widgets' ),
							'by-above'           => __( 'Byline above', 'wpinked-widgets' ),
							'by-below'           => __( 'Byline below', 'wpinked-widgets' ),
							'boxed-by-above'     => __( 'Boxed byline above', 'wpinked-widgets' ),
							'boxed-by-below'     => __( 'Boxed byline below', 'wpinked-widgets' ),
						)
					),

					'img-radius'           => array(
						'type'                => 'select',
						'label'               => __( 'Image Shape', 'wpinked-widgets' ),
						'default'             => '0',
						'options'             => array(
							'0'                  => __( 'Square', 'wpinked-widgets' ),
							'5%'                 => __( 'Curved', 'wpinked-widgets' ),
							'50%'                => __( 'Round', 'wpinked-widgets' )
						)
					),

					'img-width'           => array(
						'type'                => 'measurement',
						'label'               => __( 'Image Width', 'wpinked-widgets' ),
						'default'             => '100%',
					),

					'text'                 => array(
						'type'                => 'select',
						'label'               => __( 'Text Alignment', 'wpinked-widgets' ),
						'default'             => 'iw-text-left',
						'options'             => array(
							'iw-text-left'       => __( 'Left', 'wpinked-widgets' ),
							'iw-text-center'     => __( 'Center', 'wpinked-widgets' ),
							'iw-text-right'      => __( 'Right', 'wpinked-widgets' ),
						)
					),

					'background'           => array(
						'type'                => 'color',
						'label'               => __( 'Background Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'name'                 => array(
						'type'                => 'color',
						'label'               => __( 'Name Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'company'              => array(
						'type'                => 'color',
						'label'               => __( 'Company Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'padding'              => array(
						'type'                => 'checkbox',
						'label'               => __( 'Remove testimonial padding', 'wpinked-widgets' ),
						'default'             => false
					),

					'boxed-background'     => array(
						'type'                => 'color',
						'label'               => __( 'Boxed Background Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'boxed-border'         => array(
						'type'                => 'color',
						'label'               => __( 'Boxed Border Color', 'wpinked-widgets' ),
						'default'             => ''
					),

				)
			),
		);
	}

	function get_template_name($instance) {
		return 'testimonial';
	}

	function get_style_name($instance) {
		return 'testimonial';
	}

	function initialize() {

		$this->register_frontend_styles(
			array(
				array( 'iw-testimonial-css', plugin_dir_url(__FILE__) . 'css/testimonial.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables($instance) {
		if( empty( $instance ) ) return array();

		return array(
			'design'     => $instance['styling']['design'],
			'bg'         => $instance['styling']['background'],
			'img-r'      => $instance['styling']['img-radius'],
			'img-w'      => $instance['styling']['img-width'],
			'name'       => $instance['styling']['name'],
			'company'    => $instance['styling']['company'],
			'padding'    => $instance['styling']['padding'],
			'box-bg'     => $instance['styling']['boxed-background'],
			'box-border' => $instance['styling']['boxed-border'],
		);
	}

}

siteorigin_widget_register( 'ink-testimonial', __FILE__, 'Inked_Testimonial_SO_Widget' );
