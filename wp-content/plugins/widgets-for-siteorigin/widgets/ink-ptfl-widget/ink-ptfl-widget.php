<?php

/*
Widget Name: Inked Portfolio
Description: Show off your work.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Folio_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-folio',
			__( 'Inked Portfolio', 'wpinked-widgets' ),
			array(
				'description' => __( 'Show off your work.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/post-type-widget/portfolio/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(
			'title'                         => array(
				'type'                         => 'text',
				'label'                        => __( 'Title', 'wpinked-widgets' ),
				'default'                      => ''
			),

			'portfolio'                     => array(
				'type'                         => 'posts',
				'label'                        => __( 'Select Projects', 'wpinked-widgets' ),
				'description'                  => __( 'Make sure that <b>Custom Post Types</b> module in active in Jetpack. Choose <b>Projects</b> under post type', 'wpinked-widgets' ),
			),

			'design'                        => array(
				'type'                         => 'section',
				'label'                        => __( 'Design' , 'wpinked-widgets' ),
				'hide'                         => true,
				'fields'                       => array(

					'theme'                       => array(
						'type'                       => 'select',
						'label'                      => __( 'Theme', 'wpinked-widgets' ),
						'default'                    => 'folio-default',
						'options'                    => array(
							'folio-default'             => __( 'Default', 'wpinked-widgets' ),
							'folio-gallery'             => __( 'Gallery', 'wpinked-widgets' )
						),
						'state_emitter'              => array(
							'callback'                  => 'select',
							'args'                      => array( 'folio_type' )
						)
					),

					'text-hover'                  => array(
						'type'                       => 'checkbox',
						'label'                      => __( 'Display Text only on hover ?', 'wpinked-widgets' ),
						'default'                    => false,
						'state_handler'              => array(
							'folio_type[folio-default]' => array( 'hide' ),
							'folio_type[folio-gallery]' => array( 'show' ),
						),
					),

					'sorting'                     => array(
						'type'                       => 'checkbox',
						'label'                      => __( 'Enable Sorting ?', 'wpinked-widgets' ),
						'default'                    => true
					),

					'show-all'                    => array(
						'type'                       => 'checkbox',
						'label'                      => __( 'Show all text ?', 'wpinked-widgets' ),
						'default'                    => true
					),

					'all'                         => array(
						'type'                       => 'text',
						'label'                      => __( 'Label for showing all projects', 'wpinked-widgets' ),
						'default'                    => 'ALL'
					),

					'icon'                        => array(
						'type'                       => 'icon',
						'label'                      => __( 'Icon', 'wpinked-widgets' ),
						'description'                => __( 'This will appear above the image, on hover.', 'wpinked-widgets' ),
						'state_handler'              => array(
							'folio_type[folio-default]' => array( 'show' ),
							'folio_type[folio-gallery]' => array( 'hide' ),
						),
					),

					'columns'                     => array(
						'type'                       => 'select',
						'label'                      => __( 'Number of Columns', 'wpinked-widgets' ),
						'default'                    => '25%',
						'options'                    => array(
							'100%'                      => __( '1', 'wpinked-widgets' ),
							'50%'                       => __( '2', 'wpinked-widgets' ),
							'33.33%'                    => __( '3', 'wpinked-widgets' ),
							'25%'                       => __( '4', 'wpinked-widgets' ),
							'20%'                       => __( '5', 'wpinked-widgets' ),
							'16.66%'                    => __( '6', 'wpinked-widgets' ),
						),
						'state_emitter'              => array(
							'callback'                  => 'select',
							'args'                      => array( 'columns' )
						),
						'description'                => __( 'The widget is responsive, so the columns will adjust based on screen size.', 'wpinked-widgets' ),
					),
				)
			),

			'styling'                       => array(
				'type'                         => 'section',
				'label'                        => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                         => true,
				'fields'                       => array(

					'type-theme'                  => array(
						'type'                       => 'select',
						'label'                      => __( 'Filter List Theme', 'wpinked-widgets' ),
						'default'                    => 'minimal',
						'options'                    => array(
							'minimal'                   => __( 'Minimal', 'wpinked-widgets' ),
							'outline'                   => __( 'Outline', 'wpinked-widgets' ),
							'flat'                      => __( 'Flat', 'wpinked-widgets' )
						)
					),

					'type-color'                  => array(
						'type'                       => 'color',
						'label'                      => __( 'Filter List Color', 'wpinked-widgets' ),
						'default'                    => '',
						'description'                => __( 'Typically used as text color.', 'wpinked-widgets' ),
					),

					'type-h-color'                => array(
						'type'                       => 'color',
						'label'                      => __( 'Filter List Highlight Color', 'wpinked-widgets' ),
						'default'                    => '',
						'description'                => __( 'Typically used as button background.', 'wpinked-widgets' ),
					),

					'type-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Filter List Font', 'wpinked-widgets' ),
					),

					'type-pos'                    => array(
						'type'                       => 'select',
						'label'                      => __( 'Filter List Position', 'wpinked-widgets' ),
						'default'                    => 'center',
						'options'                    => array(
							'left'                      => __( 'Left', 'wpinked-widgets' ),
							'center'                    => __( 'Center', 'wpinked-widgets' ),
							'right'                     => __( 'Right', 'wpinked-widgets' )
						)
					),

					'align'                       => array(
						'type'                       => 'select',
						'label'                      => __( 'Text Alignment', 'wpinked-widgets' ),
						'default'                    => 'center',
						'options'                    => array(
							'left'                      => __( 'Left', 'wpinked-widgets' ),
							'center'                    => __( 'Center', 'wpinked-widgets' ),
							'right'                     => __( 'Right', 'wpinked-widgets' )
						)
					),

					'spacing'                     => array(
						'type'                       => 'checkbox',
						'label'                      => __( 'Remove Spacing between projects ?', 'wpinked-widgets' ),
						'default'                    => false
					),

					'background'                  => array(
						'type'                       => 'color',
						'label'                      => __( 'Background Color', 'wpinked-widgets' ),
						'default'                    => '',
						'state_handler'              => array(
							'folio_type[folio-default]' => array( 'show' ),
							'folio_type[folio-gallery]' => array( 'hide' ),
						),
					),

					'img-hover'                   => array(
						'type'                       => 'color',
						'label'                      => __( 'Image Background Color', 'wpinked-widgets' ),
						'default'                    => ''
					),

					'img-opacity-init'            => array(
						'type'                       => 'number',
						'label'                      => __( 'Image Initial Opacity', 'wpinked-widgets' ),
						'default'                    => '1',
						'description'                => __( 'Choose a value between 0 and 1. 0: transparent 1: opaque', 'wpinked-widgets' ),
						'state_handler'              => array(
							'folio_type[folio-default]' => array( 'hide' ),
							'folio_type[folio-gallery]' => array( 'show' ),
						),
					),

					'img-opacity'                 => array(
						'type'                       => 'number',
						'label'                      => __( 'Image Hover Opacity', 'wpinked-widgets' ),
						'default'                    => '0.5',
						'description'                => __( 'Choose a value between 0 and 1. 0: transparent 1: opaque', 'wpinked-widgets' ),
					),

					'icon'                        => array(
						'type'                       => 'color',
						'label'                      => __( 'Icon Color', 'wpinked-widgets' ),
						'default'                    => '',
						'state_handler'              => array(
							'folio_type[folio-default]' => array( 'show' ),
							'folio_type[folio-gallery]' => array( 'hide' ),
						),
					),

					'p-title'                     => array(
						'type'                       => 'color',
						'label'                      => __( 'Project Name Color', 'wpinked-widgets' ),
						'default'                    => ''
					),

					'p-title-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Project Name Font', 'wpinked-widgets' ),
					),

					'p-cats'                      => array(
						'type'                       => 'color',
						'label'                      => __( 'Project Types Color', 'wpinked-widgets' ),
						'default'                    => ''
					),

					'p-cats-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Project Types Font', 'wpinked-widgets' ),
					),
				)
			),
		);
	}

	function get_template_name($instance) {
		return $instance['design']['theme'];
	}

	function get_style_name($instance) {
		return $instance['design']['theme'];
	}

	function initialize() {

		$this->register_frontend_scripts(
			array(
				array( 'iw-portfolio-js', plugin_dir_url(__FILE__) . 'js/portfolio' . INKED_JS_SUFFIX . '.js', array( 'iw-mixitup-js', 'iw-match-height-js' ), INKED_SO_VER, true )
			)
		);

		$this->register_frontend_styles(
			array(
				array( 'iw-portfolio-css', plugin_dir_url(__FILE__) . 'css/portfolio.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables($instance) {
		if( empty( $instance ) ) return array();

		$less_variables = array(
			'columns'     => $instance['design']['columns'],
			'img-hover'   => $instance['styling']['img-hover'],
			'background'  => $instance['styling']['background'],
			'img-op-init' => $instance['styling']['img-opacity-init'],
			'img-op'      => $instance['styling']['img-opacity'],
			'p-title'     => $instance['styling']['p-title'],
			'p-cats'      => $instance['styling']['p-cats'],
			'type-th'     => $instance['styling']['type-theme'],
			'type-clr'    => $instance['styling']['type-color'],
			'type-hl'     => $instance['styling']['type-h-color'],
			'type-pos'    => $instance['styling']['type-pos'],
			'spacing'     => $instance['styling']['spacing']
		);

		if ( function_exists( 'wpinked_pro_so_widgets' ) ) {
			if ( $instance['styling']['type-font']  ) {
				$type_font = siteorigin_widget_get_font( $instance['styling']['type-font'] );
				$less_variables['type-font-fly'] = $type_font['family'];
				if( ! empty( $type_font['weight'] ) ) {
					$less_variables['type-font-wt'] = $type_font['weight'];
				}
			}
			if ( $instance['styling']['p-title-font']  ) {
				$title_font = siteorigin_widget_get_font( $instance['styling']['p-title-font'] );
				$less_variables['title-font-fly'] = $title_font['family'];
				if( ! empty( $title_font['weight'] ) ) {
					$less_variables['title-font-wt'] = $title_font['weight'];
				}
			}
			if ( $instance['styling']['p-cats-font']  ) {
				$cats_font = siteorigin_widget_get_font( $instance['styling']['p-cats-font'] );
				$less_variables['cats-font-fly'] = $cats_font['family'];
				if( ! empty( $cats_font['weight'] ) ) {
					$less_variables['cats-font-wt'] = $cats_font['weight'];
				}
			}
		}

		return $less_variables;
	}

	function get_google_font_fields( $instance ) {
		if( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['type-font'] ) {
			$fonts[] = $instance['styling']['type-font'];
		}
		if ( $instance['styling']['p-title-font'] ) {
			$fonts[] = $instance['styling']['p-title-font'];
		}
		if ( $instance['styling']['p-cats-font'] ) {
			$fonts[] = $instance['styling']['p-cats-font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-folio', __FILE__, 'Inked_Folio_SO_Widget' );
