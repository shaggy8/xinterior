<?php

/*
Widget Name: Inked Tabs
Description: Organize and navigate multiple documents in a single container.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Tabs_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-tabs',
			__( 'Inked Tabs', 'wpinked-widgets' ),
			array(
				'description' => __( 'Organize and navigate multiple documents in a single container.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/content-widgets/tabs/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(
			'admin'                  => array(
				'type'                  => 'text',
				'label'                 => __( 'Admin Label', 'wpinked-widgets' ),
				'default'               => ''
			),

			'id'                     => array(
				'type'                  => 'text',
				'label'                 => __( 'ID', 'wpinked-widgets' ),
				'description'           => __( 'Should be unique on the page. Must begin with alphabets[A-Za-z]. Should not contain spaces.', 'wpinked-widgets' ),
				'default'               => ''
			),

			'tabs'                   => array(
				'type'                  => 'repeater',
				'label'                 => __( 'Tabs' , 'wpinked-widgets' ),
				'item_name'             => __( 'Tab', 'wpinked-widgets' ),
				'item_label'            => array(
					'selector'             => "[id*='title']",
					'update_event'         => 'change',
					'value_method'         => 'val'
				),
				'fields'                => array(

					'title'                => array(
						'type'                => 'text',
						'label'               => __( 'Title', 'wpinked-widgets' ),
						'default'             => ''
					),

					'active'               => array(
						'type'                => 'checkbox',
						'label'               => __( 'Open by default ?', 'wpinked-widgets' ),
						'default'             => false,
						'description'         => __( 'Check this for only one of the tabs.', 'wpinked-widgets' ),
					),

					'icon'                 => array(
						'type'                => 'icon',
						'label'               => __( 'Icon', 'wpinked-widgets' ),
					),

					'content_type'         => array(
						'type'                => 'select',
						'label'               => __( 'Content Type', 'wpinked-widgets' ),
						'default'             => 'tinymce',
						'options'             => array(
							'tinymce'            => __( 'TinyMCE Editor', 'wpinked-widgets' ),
							'builder'            => __( 'Page Builder', 'wpinked-widgets' )
						),
						'state_emitter'          => array(
							'callback'              => 'select',
							'args'                  => array( 'ctnt_type' )
						)
					),

					'content'              => array(
						'type'                => 'tinymce',
						'label'               => __( 'Content', 'wpinked-widgets' ),
						'state_handler'          => array(
							'ctnt_type[tinymce]'  => array( 'show' ),
							'ctnt_type[builder]'  => array( 'hide' ),
						),
					),

					'autop'                => array(
						'type'                => 'checkbox',
						'default'             => false,
						'label'               => __( 'Automatically add paragraphs', 'wpinked-widgets' ),
						'state_handler'          => array(
							'ctnt_type[tinymce]'  => array( 'show' ),
							'ctnt_type[builder]'  => array( 'hide' ),
						),
					),

					'builder'                 => array(
						'type'                => 'premium',
						'label'               => __( 'Builder', 'wpinked-widgets' ),
						'state_handler'          => array(
							'ctnt_type[tinymce]'  => array( 'hide' ),
							'ctnt_type[builder]'  => array( 'show' ),
						),
					),

				)
			),

			'styling'                => array(
				'type'                  => 'section',
				'label'                 => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                  => true,
				'fields'                => array(

					'orientation'          => array(
						'type'                => 'select',
						'label'               => __( 'Orientation', 'wpinked-widgets' ),
						'default'             => 'horizontal',
						'options'             => array(
							'horizontal'         => __( 'Horizontal', 'wpinked-widgets' ),
							'vertical'           => __( 'Vertical', 'wpinked-widgets' )
						)
					),

					'responsive'          => array(
						'type'                => 'select',
						'label'               => __( 'Mobile View', 'wpinked-widgets' ),
						'default'             => 'default',
						'options'             => array(
							'default'            => __( 'Default', 'wpinked-widgets' ),
							'icons'              => __( 'Icons Only', 'wpinked-widgets' ),
							'fullwidth'          => __( 'Fullwidth', 'wpinked-widgets' ),
						)
					),

					'theme'                => array(
						'type'                => 'select',
						'label'               => __( 'Theme', 'wpinked-widgets' ),
						'default'             => 'flat',
						'options'             => array(
							'boxed'              => __( 'Boxed', 'wpinked-widgets' ),
							'flat'               => __( 'Flat', 'wpinked-widgets' ),
							'underline'          => __( 'Underline', 'wpinked-widgets' ),
							'overline'           => __( 'Overline', 'wpinked-widgets' ),
							'minimal'            => __( 'Minimal', 'wpinked-widgets' ),
						)
					),

					'icon'                 => array(
						'type'                => 'select',
						'label'               => __( 'Icon Location', 'wpinked-widgets' ),
						'default'             => 'left',
						'options'             => array(
							'left'               => __( 'Left', 'wpinked-widgets' ),
							'right'              => __( 'Right', 'wpinked-widgets' ),
							'above'              => __( 'Above', 'wpinked-widgets' )
						)
					),

					'tab'                  => array(
						'type'                => 'color',
						'label'               => __( 'Tab Background Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'tab-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Tab Font', 'wpinked-widgets' ),
					),

					'content'              => array(
						'type'                => 'color',
						'label'               => __( 'Content Background Color', 'wpinked-widgets' ),
						'default'             => ''
					),

					'basic'                => array(
						'type'                => 'color',
						'label'               => __( 'Basic Color', 'wpinked-widgets' ),
						'default'             => '',
						'description'         => __( 'Color of the title.', 'wpinked-widgets' ),
					),

					'highlight'            => array(
						'type'                => 'color',
						'label'               => __( 'Highlight Color', 'wpinked-widgets' ),
						'default'             => '',
						'description'         => __( 'Color of title when it is active.', 'wpinked-widgets' ),
					),

				)
			),
		);
	}

	function get_template_name($instance) {
		return 'tabs';
	}

	function get_style_name($instance) {
		return 'tabs';
	}

	function initialize() {

		$this->register_frontend_scripts(
			array(
				array( 'iw-tabs-js', plugin_dir_url(__FILE__) . 'js/tabs' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER )
			)
		);

		$this->register_frontend_styles(
			array(
				array( 'iw-tabs-css', plugin_dir_url(__FILE__) . 'css/tabs.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables($instance) {

		if( empty( $instance ) ) return array();

		$less_variables = array(
			'theme'     => $instance['styling']['theme'],
			'bg'        => $instance['styling']['tab'],
			'bg-c'      => $instance['styling']['content'],
			'title'     => $instance['styling']['basic'],
			'highlight' => $instance['styling']['highlight'],
			'icon'      => $instance['styling']['icon'],
		);

		if ( $instance['styling']['tab-font'] && function_exists( 'wpinked_pro_so_widgets' ) ) {
			$selected_font = siteorigin_widget_get_font( $instance['styling']['tab-font'] );
			$less_variables['tab-font-fly'] = $selected_font['family'];
			if( ! empty( $selected_font['weight'] ) ) {
				$less_variables['tab-font-wt'] = $selected_font['weight'];
			}
		}
		return $less_variables;

	}

	function get_google_font_fields( $instance ) {
		if( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['tab-font'] ) {
			$fonts[] = $instance['styling']['tab-font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-tabs', __FILE__, 'Inked_Tabs_SO_Widget' );
