<?php

/*
Widget Name: Inked Social Icons
Description: Redirect user to your social media profiles.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Social_Icons_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-social-icons',
			__( 'Inked Social Icons', 'wpinked-widgets' ),
			array(
				'description' => __( 'Redirect user to your social media profiles.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/social-widgets/social-icons/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(

			'icons'                   => array(
				'type'                  => 'repeater',
				'label'                 => __( 'Icons' , 'wpinked-widgets' ),
				'item_name'             => __( 'Icon', 'wpinked-widgets' ),
				'item_label'            => array(
					'selector'             => "[id*='network']",
					'update_event'         => 'change',
					'value_method'         => 'val'
				),
				'fields'                => array(

                    'network'            => array(
						'type'            => 'text',
						'label'           => __( 'Network', 'wpinked-widgets' ),
					),

					'icon'                 => array(
						'type'                => 'icon',
						'label'               => __( 'Icon', 'wpinked-widgets' ),
                    ),

                    'link'           => array(
                        'type'          => 'link',
                        'label'         => __( 'Link', 'wpinked-widgets' ),
                        'default'       => ''
                    ),

                    'color'        => array(
						'type'                => 'color',
						'label'               => __( 'Icon Color', 'wpinked-widgets' ),
						'default'             => '',
						'description'         => __( 'Color of the icon.', 'wpinked-widgets' ),
					),

                    'bg-color'      => array(
						'type'                => 'color',
						'label'               => __( 'Icon Background Color', 'wpinked-widgets' ),
						'default'             => '',
					),

					'hover-color'           => array(
						'type'                => 'color',
						'label'               => __( 'Icon Hover Color', 'wpinked-widgets' ),
						'default'             => '',
						'description'         => __( 'Hover color of the icon.', 'wpinked-widgets' ),
                    ),

					'hover-bg-color'           => array(
						'type'                => 'color',
						'label'               => __( 'Icon Background Hover Color', 'wpinked-widgets' ),
						'default'             => '',
						'description'         => __( 'Hover color of the icon.', 'wpinked-widgets' ),
                    ),

				)
			),

			'styling'                => array(
				'type'                  => 'section',
				'label'                 => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                  => true,
				'fields'                => array(

					'alignment'          => array(
						'type'                => 'select',
						'label'               => __( 'Alignment', 'wpinked-widgets' ),
						'default'             => 'center',
						'options'             => array(
							'center'         => __( 'Center', 'wpinked-widgets' ),
							'left'           => __( 'Left', 'wpinked-widgets' ),
							'right'          => __( 'Right', 'wpinked-widgets' ),
						)
                    ),

                    'size'             => array(
						'type'            => 'measurement',
						'label'           => __( 'Icon Size', 'wpinked-widgets' ),
						'default'         => '30px',
                    ),

                    'gap'             => array(
						'type'            => 'measurement',
						'label'           => __( 'Gap between icons', 'wpinked-widgets' ),
						'default'         => '20px',
                    ),

                    'padding'    => array(
						'type'            => 'measurement',
						'label'           => __( 'Icon padding', 'wpinked-widgets' ),
						'default'         => '10px',
					),

					'design'                => array(
						'type'                => 'select',
						'label'               => __( 'Design', 'wpinked-widgets' ),
						'default'             => 'cir',
						'options'             => array(
							'cir'              => __( 'Circle', 'wpinked-widgets' ),
							'sq'               => __( 'Square', 'wpinked-widgets' ),
							'cirb'             => __( 'Circle Bordered', 'wpinked-widgets' ),
							'sqb'              => __( 'Square Bordered', 'wpinked-widgets' ),
						)
					),

					'default-color'        => array(
						'type'                => 'color',
						'label'               => __( 'Icon Default Color', 'wpinked-widgets' ),
						'default'             => '#fff',
						'description'         => __( 'Default color of the icon.', 'wpinked-widgets' ),
					),

                    'bg-color'      => array(
						'type'                => 'color',
						'label'               => __( 'Default Icon Background Color', 'wpinked-widgets' ),
						'default'             => '#b9b9b9',
					),

					'hover-color'           => array(
						'type'                => 'color',
						'label'               => __( 'Icon Default Hover Color', 'wpinked-widgets' ),
						'default'             => '#fff',
						'description'         => __( 'Default hover color of the icon.', 'wpinked-widgets' ),
					),

					'hover-bg-color'      => array(
						'type'                => 'color',
						'label'               => __( 'Default Icon Background Color', 'wpinked-widgets' ),
						'default'             => '#000',
					),

				)
			),
		);
	}

	function get_template_name($instance) {
		return 'social-icons';
	}

	function get_style_name($instance) {
		return 'social-icons';
	}

	function initialize() {

		$this->register_frontend_styles(
			array(
				array( 'iw-social-icons-css', plugin_dir_url(__FILE__) . 'css/social-icons.css', array(), INKED_SO_VER )
			)
		);

	}

	function get_less_variables($instance) {

		if( empty( $instance ) ) return array();

		$less_variables = array(
			'gap'           => $instance['styling']['gap'],
			'padding'       => $instance['styling']['padding'],
			'design'        => $instance['styling']['design'],
			'size'          => $instance['styling']['size'],
			'default-color' => $instance['styling']['default-color'],
			'hover-color'   => $instance['styling']['hover-color'],
			'bg-color'      => $instance['styling']['bg-color'],
			'hover-bg-color'   => $instance['styling']['hover-bg-color'],
		);
		return $less_variables;

	}

	private function get_instance_icons( $instance ) {
		if ( isset( $instance['icons'] ) && ! empty( $instance['icons'] ) ) {
			$networks = $instance['icons'];
		} else {
			$networks = array();
		}
		return apply_filters( 'ink_so_social_media_icons', $networks, $instance );
	}

	function less_generate_calls_to( $instance, $args ) {
		$icons = $this->get_instance_icons( $instance );
		$calls    = array();
		foreach ( $icons as $icon ) {
			if ( ! empty( $icon['network'] ) ) {
				$call = $args[0] . '( @name:' . $icon['network'];
				$call .= ! empty( $icon['color'] ) ? ', @icon-color:' . $icon['color'] : '';
				$call .= ! empty( $icon['bg-color'] ) ? ', @icon-bg-color:' . $icon['bg-color'] : '';
				$call .= ! empty( $icon['hover-color'] ) ? ', @icon-hover-color:' . $icon['hover-color'] : '';
				$call .= ! empty( $icon['hover-bg-color'] ) ? ', @icon-hover-bg-color:' . $icon['hover-bg-color'] : '';
				$call .= ');';
				$calls[] = $call;
			}
		}

		return implode( "\n", $calls );
	}

	/**
	 * This is used to generate the hash of the instance.
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	protected function get_style_hash_variables( $instance ){
		$networks = $this->get_instance_icons($instance);

		foreach($networks as $i => $network) {
			// URL is not important for the styling
			unset($networks[$i]['link']);
		}

		return array(
			'less' => $this->get_less_variables($instance),
			'icons' => $networks
		);
	}

	function get_template_variables( $instance, $args ) {

		if( empty( $instance ) ) return array();

		return array(
			'icons'         => $instance['icons'],
			'align'         => $instance['styling']['alignment'],
		);
	}

}

siteorigin_widget_register( 'ink-social-icons', __FILE__, 'Inked_Social_Icons_SO_Widget' );
