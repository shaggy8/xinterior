<?php

/*
Widget Name: Inked Image
Description: Spice up your images.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Image_SO_Widget extends SiteOrigin_Widget {

	function __construct() {
		parent::__construct(

			'ink-image',
			__( 'Inked Image', 'wpinked-widgets' ),
			array(
				'description' => __( 'Spice up your images.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/media-widgets/image/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(
			'image'            => array(
				'type'            => 'section',
				'label'           => __( 'Image' , 'wpinked-widgets' ),
				'hide'            => true,
				'fields'          => array(

					'image'          => array(
						'type'          => 'media',
						'fallback'      => false,
						'label'         => __( 'Image', 'wpinked-widgets' ),
						'default'       => '',
						'library'       => 'image',
					),

					'img-size'       => array(
						'type'          => 'image-size',
						'label'         => __( 'Image Size', 'wpinked-widgets' ),
						'description'   => __( 'You can change the default size widths by going to <b>Settings</b> &rarr; <b>Media</b>.', 'wpinked-widgets' ),
					),

					'alignment'      => array(
						'type'          => 'select',
						'label'         => __( 'Image Alignment', 'wpinked-widgets' ),
						'default'       => 'default',
						'options'       => array(
							'default'      => __( 'Default', 'wpinked-widgets' ),
							'left'         => __( 'Left', 'wpinked-widgets' ),
							'center'       => __( 'Center', 'wpinked-widgets' ),
							'right'        => __( 'Right', 'wpinked-widgets' ),
						)
					),

					'link-type'      => array(
						'type'          => 'select',
						'label'         => __( 'Image Link', 'wpinked-widgets' ),
						'default'       => '0',
						'options'       => array(
							'none'         => __( 'None', 'wpinked-widgets' ),
							'url'          => __( 'URL', 'wpinked-widgets' ),
							'lightbox'     => __( 'Lightbox', 'wpinked-widgets' ),
						),
						'state_emitter' => array(
							'callback'     => 'select',
							'args'         => array( 'link_type' )
						)
					),

				)
			),

			'caption'         => array(
				'type'           => 'section',
				'label'          => __( 'Caption' , 'wpinked-widgets' ),
				'hide'           => true,
				'fields'         => array(

					'title'         => array(
						'type'         => 'text',
						'label'        => __( 'Caption Title', 'wpinked-widgets' ),
						'default'      => '',
					),

					'desc'          => array(
						'type'         => 'textarea',
						'label'        => __( 'Caption Description', 'wpinked-widgets' ),
						'rows'         => 3
					),

					'design'        => array(
						'type'         => 'select',
						'label'        => __( 'Caption Design', 'wpinked-widgets' ),
						'default'      => 'bottom',
						'options'      => array(
							'bottom'      => __( 'Bottom', 'wpinked-widgets' ),
							'center'      => __( 'Center', 'wpinked-widgets' ),
							'topbottom'   => __( 'Title top - Description bottom', 'wpinked-widgets' ),
							'diatop'      => __( 'Diagonal - Title top', 'wpinked-widgets' ),
							'diabottom'   => __( 'Diagonal - Title bottom', 'wpinked-widgets' ),
						)
					),

					'display-title' => array(
						'type'         => 'checkbox',
						'label'        => __( 'Show caption title by default', 'wpinked-widgets' ),
						'default'      => false,
						'description'  => __( 'Caption title is shown even without hover', 'wpinked-widgets' ),
					),

				)
			),

			'link'                  => array(
				'type'                 => 'section',
				'label'                => __( 'Link' , 'wpinked-widgets' ),
				'hide'                 => true,
				'state_handler'        => array(
					'link_type[none]'     => array( 'hide' ),
					'link_type[url]'      => array( 'show' ),
					'link_type[lightbox]' => array( 'hide' ),
				),
				'fields'               => array(

					'url'                 => array(
						'type'               => 'link',
						'label'              => __( 'Destination URL', 'wpinked-widgets' ),
					),

					'window'              => array(
						'type'               => 'checkbox',
						'default'            => false,
						'label'              => __( 'Open in a new window', 'wpinked-widgets' ),
					),

				)
			),

			'lightbox'              => array(
				'type'                 => 'section',
				'label'                => __( 'Lightbox' , 'wpinked-widgets' ),
				'hide'                 => true,
				'state_handler'        => array(
					'link_type[none]'     => array( 'hide' ),
					'link_type[url]'      => array( 'hide' ),
					'link_type[lightbox]' => array( 'show' ),
				),
				'fields'               => array(

					'img-size'            => array(
						'type'               => 'premium',
						'label'              => __( 'Lightbox Image Size', 'wpinked-widgets' ),
						'description'        => __( 'You can change the default size widths by going to <b>Settings</b> &rarr; <b>Media</b>.', 'wpinked-widgets' ),
					),

				)
			),

			'styling'              => array(
				'type'                => 'section',
				'label'               => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                => true,
				'fields'              => array(

					'shadow'             => array(
						'type'              => 'select',
						'label'             => __( 'Image Shadow', 'wpinked-widgets' ),
						'default'           => 'none',
						'options'           => array(
							'none'             => __( 'None', 'wpinked-widgets' ),
							'bottom'           => __( 'Bottom', 'wpinked-widgets' ),
							'bottomsides'      => __( 'Bottom Sides', 'wpinked-widgets' ),
							'bottomleft'       => __( 'Bottom Left', 'wpinked-widgets' ),
							'bottomright'      => __( 'Bottom Right', 'wpinked-widgets' ),
							'topbottom'        => __( 'Top Bottom', 'wpinked-widgets' ),
							'leftright'        => __( 'Left Right', 'wpinked-widgets' ),
							'surround'         => __( 'All Sides', 'wpinked-widgets' ),
						)
					),

					'image-border-width' => array(
						'type'              => 'measurement',
						'label'             => __( 'Image Border Width', 'wpinked-widgets' ),
						'default'           => '0',
					),

					'image-border-clr'   => array(
						'type'              => 'color',
						'label'             => __( 'Image Border Color', 'wpinked-widgets' ),
						'default'           => '',
					),

					'image-shape'        => array(
						'type'              => 'select',
						'label'             => __( 'Image Border Shape', 'wpinked-widgets' ),
						'default'           => '0',
						'options'           => array(
							'0'                => __( 'Sharp', 'wpinked-widgets' ),
							'3px'              => __( 'Slight Curve', 'wpinked-widgets' ),
							'20px'             => __( 'High Curve', 'wpinked-widgets' ),
						),
					),

					'image-ol'           => array(
						'type'              => 'color',
						'label'             => __( 'Image Overlay Color', 'wpinked-widgets' ),
						'default'           => '#333'
					),

					'image-ol-o'           => array(
						'type'                => 'slider',
						'label'               => __( 'Image Overlay Opacity', 'wpinked-widgets' ),
						'default'             => 0,
						'min'                 => 0,
						'max'                 => 100,
						'integer'             => true,
					),

					'image-ol-o-h'           => array(
						'type'                => 'slider',
						'label'               => __( 'Image Overlay Hover Opacity', 'wpinked-widgets' ),
						'default'             => 60,
						'min'                 => 0,
						'max'                 => 100,
						'integer'             => true,
					),

					'image-o-margin'    => array(
						'type'              => 'measurement',
						'label'             => __( 'Image Overlay margin', 'wpinked-widgets' ),
						'default'           => '0',
					),

					'caption-clr'        => array(
						'type'              => 'color',
						'label'             => __( 'Caption Color', 'wpinked-widgets' ),
						'default'           => '#f1f1f1'
					),

					'caption-font'       => array(
						'type'              => 'premium',
						'label'             => __( 'Caption Font', 'wpinked-widgets' ),
					),

					'caption-title-size' => array(
						'type'              => 'measurement',
						'label'             => __( 'Caption Title Size', 'wpinked-widgets' ),
						'default'           => '25px',
					),

					'caption-desc-size'  => array(
						'type'              => 'measurement',
						'label'             => __( 'Caption Description Size', 'wpinked-widgets' ),
						'default'           => '14px',
					),

				)
			),

		);
	}

	function get_template_name( $instance ) {
		return 'image';
	}

	function get_style_name( $instance ) {
		return 'image';
	}

	function initialize() {

		$this->register_frontend_styles(
			array(
				array( 'iw-image-css', plugin_dir_url(__FILE__) . 'css/image.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables( $instance ) {

		if( empty( $instance ) ) return array();

		$less_variables = array(
			'img-align'      => $instance['image']['alignment'],
			'shadow'         => $instance['styling']['shadow'],
			'img-bor-width'  => $instance['styling']['image-border-width'],
			'img-bor-clr'    => $instance['styling']['image-border-clr'],
			'img-bor-rad'    => $instance['styling']['image-shape'],
			'image-ol'       => $instance['styling']['image-ol'],
			'image-ol-o'     => $instance['styling']['image-ol-o'],
			'image-ol-o-h'   => $instance['styling']['image-ol-o-h'],
			'image-o-margin' => $instance['styling']['image-o-margin'],
			'cap-clr'        => $instance['styling']['caption-clr'],
			'cap-title-size' => $instance['styling']['caption-title-size'],
			'cap-desc-size'  => $instance['styling']['caption-desc-size'],
			'cap-title'      => $instance['caption']['display-title'],
			'cap-design'     => $instance['caption']['design'],
		);
		if ( $instance['styling']['caption-font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['styling']['caption-font'] );
			$less_variables['cap-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['cap-wt'] = $selected_font['weight'];
			}
		}
		return $less_variables;

	}

	function get_template_variables( $instance, $args ) {

		if ( empty( $instance ) ) return array();

		return array(
		);
	}

	function get_google_font_fields( $instance ) {
		if ( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['caption-font'] ) {
			$fonts[] = $instance['styling']['caption-font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-image', __FILE__, 'Inked_Image_SO_Widget' );
