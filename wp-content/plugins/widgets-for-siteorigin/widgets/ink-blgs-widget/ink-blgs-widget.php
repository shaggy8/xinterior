<?php

/*
Widget Name: Inked Blog
Description: A widget to display Blog posts.
Author: wpinked
Author URI: https://wpinked.com
*/

class Inked_Blog_SO_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'ink-blog',
			__( 'Inked Blog', 'wpinked-widgets' ),
			array(
				'description' => __( 'A widget to display Blog posts.', 'wpinked-widgets' ),
				'help'        => 'http://widgets.wpinked.com/docs/post-type-widget/blog/'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
	}

	function get_widget_form() {
		return array(
			'title'                     => array(
				'type'                     => 'text',
				'label'                    => __( 'Title', 'wpinked-widgets' ),
				'default'                  => ''
			),

			'loop' => array(
				'type'                     => 'section',
				'label'                    => __( 'Blog Post Loop' , 'wpinked-widgets' ),
				'hide'                     => true,
				'fields'                   => array(

					'posts'                     => array(
						'type'                     => 'posts',
						'label'                    => __( 'Select Posts', 'wpinked-widgets' ),
					),

					'current'                   => array(
						'type'                     => 'checkbox',
						'label'                    => __( 'Exclude current post from query ?', 'wpinked-widgets' ),
						'default'                  => false,
					),
				)
			),

			'icons'                     => array(
				'type'                     => 'section',
				'label'                    => __( 'Post Format Icons' , 'wpinked-widgets' ),
				'hide'                     => true,
				'fields'                   => array(

					'standard'                => array(
						'type'                   => 'icon',
						'label'                  => __( 'Standard Icon', 'wpinked-widgets' ),
					),

					'aside'                   => array(
						'type'                   => 'icon',
						'label'                  => __( 'Aside Icon', 'wpinked-widgets' ),
					),

					'gallery'                 => array(
						'type'                   => 'icon',
						'label'                  => __( 'Gallery Icon', 'wpinked-widgets' ),
					),

					'link'                    => array(
						'type'                   => 'icon',
						'label'                  => __( 'Link Icon', 'wpinked-widgets' ),
					),

					'image'                   => array(
						'type'                   => 'icon',
						'label'                  => __( 'Image Icon', 'wpinked-widgets' ),
					),

					'quote'                   => array(
						'type'                   => 'icon',
						'label'                  => __( 'Quote Icon', 'wpinked-widgets' ),
					),
					'status'                  => array(
						'type'                   => 'icon',
						'label'                  => __( 'Status Icon', 'wpinked-widgets' ),
					),
					'video'                   => array(
						'type'                   => 'icon',
						'label'                  => __( 'Video Icon', 'wpinked-widgets' ),
					),
					'audio'                   => array(
						'type'                   => 'icon',
						'label'                  => __( 'Audio Icon', 'wpinked-widgets' ),
					),
					'chat'                    => array(
						'type'                   => 'icon',
						'label'                  => __( 'Chat Icon', 'wpinked-widgets' ),
					),

					'color'                   => array(
						'type'                   => 'color',
						'label'                  => __( 'Icon Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'size'                    => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Icon Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'bg'                      => array(
						'type'                   => 'color',
						'label'                  => __( 'Background Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'bg-shape'                => array(
						'type'                   => 'select',
						'label'                  => __( 'Background Corners', 'wpinked-widgets' ),
						'default'                => '0',
						'options'                => array(
							'0'                     => __( 'Sharp', 'wpinked-widgets' ),
							'5%'                    => __( 'Curved', 'wpinked-widgets' ),
							'50%'                   => __( 'Round', 'wpinked-widgets' )
						)
					),

					'show'                    => array(
						'type'                   => 'select',
						'label'                  => __( 'Show Icon', 'wpinked-widgets' ),
						'default'                => 'default',
						'options'                => array(
							'default'               => __( 'Always', 'wpinked-widgets' ),
							'hover'                 => __( 'On Hover', 'wpinked-widgets' )
						)
					),
				)
			),

			'design'                    => array(
				'type'                     => 'section',
				'label'                    => __( 'Design' , 'wpinked-widgets' ),
				'hide'                     => true,
				'fields'                   => array(

					'layout'                  => array(
						'type'                   => 'select',
						'label'                  => __( 'Layout', 'wpinked-widgets' ),
						'default'                => 'thumb-above',
						'options'                => array(
							'thumb-above'           => __( 'Image above the content', 'wpinked-widgets' ),
							'thumb-left'            => __( 'Image to the left of content', 'wpinked-widgets' ),
							'thumb-right'           => __( 'Image to the right of content', 'wpinked-widgets' ),
							'thumb-behind'          => __( 'Image as content background', 'wpinked-widgets' ),
							'thumb-none'            => __( 'No Image', 'wpinked-widgets' )
						),
						'state_emitter'          => array(
							'callback'              => 'select',
							'args'                  => array( 'blg_lyt' )
						)
					),

					'img-size'                => array(
						'type'                   => 'image-size',
						'label'                  => __( 'Image Size', 'wpinked-widgets' ),
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'show' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						),
						'description'            => __( 'You can change the default size widths by going to <b>Settings</b> &rarr; <b>Media</b>.', 'wpinked-widgets' ),
					),

					'img-width'              => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Set image width to 100%?', 'wpinked-widgets' ),
						'default'                => true,
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'hide' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						)
					),

					'responsive'              => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Make Image fullwidth for small screens ?', 'wpinked-widgets' ),
						'default'                => true,
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'hide' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'hide' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						)
					),

					'title-link'                  => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Title as a link ?', 'wpinked-widgets' ),
						'default'                => true
					),

					'format'                  => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Show Format Icon ?', 'wpinked-widgets' ),
						'default'                => true
					),

					'equalizer'               => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Make all blog posts the same height?', 'wpinked-widgets' ),
						'default'                => true
					),

					'title-tag'               => array(
						'type'                   => 'select',
						'label'                  => __( 'Title Tag', 'wpinked-widgets' ),
						'default'                => 'h2',
						'options'                => array(
							'h1'                    => __( 'h1', 'wpinked-widgets' ),
							'h2'                    => __( 'h2', 'wpinked-widgets' ),
							'h3'                    => __( 'h3', 'wpinked-widgets' ),
							'h4'                    => __( 'h4', 'wpinked-widgets' ),
							'h5'                    => __( 'h5', 'wpinked-widgets' ),
							'h6'                    => __( 'h6', 'wpinked-widgets' ),
						),
					),

					'content'                 => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Show Content ?', 'wpinked-widgets' ),
						'default'                => false,
						'description'            => __( 'If this is checked, excerpt will not be shown.', 'wpinked-widgets' ),
					),

					'excerpt'                 => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Show Excerpt ?', 'wpinked-widgets' ),
						'default'                => true
					),

					'e-link'                  => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Excerpt as a link ?', 'wpinked-widgets' ),
						'default'                => false
					),

					'excerpt-length'          => array(
						'type'                   => 'number',
						'label'                  => __( 'Excerpt Length', 'wpinked-widgets' ),
						'default'                => '20'
					),

					'excerpt-after'           => array(
						'type'                   => 'text',
						'label'                  => __( 'After Excerpt', 'wpinked-widgets' ),
						'default'                => '...'
					),

					'button'                  => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Show Read More Button ?', 'wpinked-widgets' ),
						'default'                => false
					),

					'btn-text'                => array(
						'type'                   => 'text',
						'label'                  => __( 'Button text', 'wpinked-widgets' ),
						'default'                => 'Read More'
					),

					'byline-above'            => array(
						'type'                   => 'text',
						'label'                  => __( 'Byline above Title', 'wpinked-widgets' ),
						'default'                => '',
						'description'            => __( 'Use %date% : The Date, %tag% : List of Tags, %category% : List of Categories, %author% : Author name with link, %comments% : Number of Comments.', 'wpinked-widgets' ),
					),

					'byline-below'            => array(
						'type'                   => 'text',
						'label'                  => __( 'Byline below Title', 'wpinked-widgets' ),
						'default'                => '',
						'description'            => __( 'Use %date% : The Date, %tag% : List of Tags, %category% : List of Categories, %author% : Author name with link, %comments% : Number of Comments.', 'wpinked-widgets' ),
					),

					'byline-end'              => array(
						'type'                   => 'text',
						'label'                  => __( 'Byline after Excerpt', 'wpinked-widgets' ),
						'default'                => '',
						'description'            => __( 'Use %date% : The Date, %tag% : List of Tags, %category% : List of Categories, %author% : Author name with link, %comments% : Number of Comments.', 'wpinked-widgets' ),
					),

					'cats'                    => array(
						'type'                   => 'text',
						'label'                  => __( 'Text between Categories', 'wpinked-widgets' ),
						'default'                => ', '
					),

					'tags'                    => array(
						'type'                   => 'text',
						'label'                  => __( 'Text between Tags', 'wpinked-widgets' ),
						'default'                => ', '
					),

					'date'                => array(
						'type'                   => 'text',
						'label'                  => __( 'Date Format', 'wpinked-widgets' ),
						'description'            => __( 'Help to <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">format date</a>', 'wpinked-widgets' ),
						'placeholder'            => 'F j, Y'
					),

					'columns'                 => array(
						'type'                   => 'slider',
						'label'                  => __( 'Columns', 'wpinked-widgets' ),
						'default'                => 1,
						'min'                    => 1,
						'max'                    => 4,
						'integer'                => true
					)

				)
			),

			'styling'                   => array(
				'type'                     => 'section',
				'label'                    => __( 'Styling' , 'wpinked-widgets' ),
				'hide'                     => true,
				'fields'                   => array(

					'align'                   => array(
						'type'                   => 'select',
						'label'                  => __( 'Text Align', 'wpinked-widgets' ),
						'default'                => 'iw-text-left',
						'options'                => array(
							'iw-text-left'          => __( 'Left', 'wpinked-widgets' ),
							'iw-text-center'        => __( 'Center', 'wpinked-widgets' ),
							'iw-text-right'         => __( 'Right', 'wpinked-widgets' )
						),
						'description'            => __( 'Sets alignment for title, categories, bylines and excerpt.', 'wpinked-widgets' ),
					),

					'img-expand'              => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Image Expands on hover ?', 'wpinked-widgets' ),
						'default'                => false,
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'hide' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						)
					),

					'img-ol'                  => array(
						'type'                   => 'color',
						'label'                  => __( 'Image Overlay Color', 'wpinked-widgets' ),
						'default'                => '',
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'show' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						)
					),

					'img-ol-o'                => array(
						'type'                   => 'text',
						'label'                  => __( 'Image Overlay Opacity', 'wpinked-widgets' ),
						'default'                => '',
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'show' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						),
						'description'            => __( 'Choose a number between 0 and 1. 0 : Transparent, 1: Opaque', 'wpinked-widgets' ),
					),

					'none-left'               => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Text distance from the left', 'wpinked-widgets' ),
						'default'                => '60px',
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'hide' ),
							'blg_lyt[thumb-left]'   => array( 'hide' ),
							'blg_lyt[thumb-right]'  => array( 'hide' ),
							'blg_lyt[thumb-behind]' => array( 'hide' ),
							'blg_lyt[thumb-none]'   => array( 'show' ),
						),
					),

					'gap-bw'                  => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Gap between posts ?', 'wpinked-widgets' ),
						'default'                => true,
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'hide' ),
							'blg_lyt[thumb-left]'   => array( 'hide' ),
							'blg_lyt[thumb-right]'  => array( 'hide' ),
							'blg_lyt[thumb-behind]' => array( 'show' ),
							'blg_lyt[thumb-none]'   => array( 'hide' ),
						)
					),

					'gap'                     => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Gap Below Posts', 'wpinked-widgets' ),
						'default'                => '1em',
					),

					'content-bg'              => array(
						'type'                   => 'color',
						'label'                  => __( 'Content Background Color', 'wpinked-widgets' ),
						'default'                => '',
						'state_handler'          => array(
							'blg_lyt[thumb-above]'  => array( 'show' ),
							'blg_lyt[thumb-left]'   => array( 'show' ),
							'blg_lyt[thumb-right]'  => array( 'show' ),
							'blg_lyt[thumb-behind]' => array( 'hide' ),
							'blg_lyt[thumb-none]'   => array( 'show' ),
						)
					),

					'title-size'              => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Title Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'title-color'             => array(
						'type'                   => 'color',
						'label'                  => __( 'Title Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'title-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Title Font', 'wpinked-widgets' ),
					),

					'excerpt-size'            => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Excerpt Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'excerpt-color'           => array(
						'type'                   => 'color',
						'label'                  => __( 'Excerpt Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'meta-size'               => array(
						'type'                   => 'measurement',
						'label'                  => __( 'Byline Font Size', 'wpinked-widgets' ),
						'default'                => '',
					),

					'meta-color'              => array(
						'type'                   => 'color',
						'label'                  => __( 'Byline Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'meta-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Byline Font', 'wpinked-widgets' ),
					),

					'meta-link'               => array(
						'type'                   => 'color',
						'label'                  => __( 'Byline Links Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'hl-color'                => array(
						'type'                   => 'color',
						'label'                  => __( 'Byline Link Highlight Color', 'wpinked-widgets' ),
						'default'                => ''
					),

					'meta-link-bold'              => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Make links in Byline bold ?', 'wpinked-widgets' ),
						'default'                => false
					),

					'btn-theme'               => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Theme', 'wpinked-widgets' ),
						'default'                => 'classic',
						'options'                => array(
							'classic'               => __( 'Classic', 'wpinked-widgets' ),
							'flat'                  => __( 'Flat', 'wpinked-widgets' ),
							'outline'               => __( 'Outline', 'wpinked-widgets' ),
							'threed'                => __( '3D', 'wpinked-widgets' ),
							'shadow'                => __( 'Shadow', 'wpinked-widgets' ),
							'deline'                => __( 'Deline', 'wpinked-widgets' ),
						),
					),

					'btn-align'               => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Alignment', 'wpinked-widgets' ),
						'default'                => 'center',
						'options'                => array(
							'left'                  => __( 'Left', 'wpinked-widgets' ),
							'right'                 => __( 'Right', 'wpinked-widgets' ),
							'center'                => __( 'Center', 'wpinked-widgets' ),
						),
					),

					'btn-size'                => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Size', 'wpinked-widgets' ),
						'default'                => 'standard',
						'options'                => array(
							'tiny'                  => __( 'Tiny', 'wpinked-widgets' ),
							'small'                 => __( 'Small', 'wpinked-widgets' ),
							'standard'              => __( 'Standard', 'wpinked-widgets' ),
							'large'                 => __( 'Large', 'wpinked-widgets' ),
						),
					),

					'btn-clr'                 => array(
						'type'                   => 'color',
						'label'                  => __( 'Button Highlight Color', 'wpinked-widgets' ),
						'description'            => __( 'Typically used as button background.', 'wpinked-widgets' ),
					),

					'btn-base'                => array(
						'type'                   => 'color',
						'label'                  => __( 'Button Base Color', 'wpinked-widgets' ),
						'description'            => __( 'Typically used as text color.', 'wpinked-widgets' ),
					),

					'btn-hover'               => array(
						'type'                   => 'checkbox',
						'default'                => true,
						'label'                  => __( 'Use button hover effect ?', 'wpinked-widgets' ),
					),

					'btn-font'             => array(
						'type'                => 'premium',
						'label'               => __( 'Button Font', 'wpinked-widgets' ),
					),

					'btn-click'               => array(
						'type'                   => 'checkbox',
						'default'                => true,
						'label'                  => __( 'Use button click effect ?', 'wpinked-widgets' ),
					),

					'btn-corners'             => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Corners', 'wpinked-widgets' ),
						'default'                => '0.25em',
						'options'                => array(
							'0em'                   => __( 'Sharp', 'wpinked-widgets' ),
							'0.25em'                => __( 'Slightly curved', 'wpinked-widgets' ),
							'0.75em'                => __( 'Highly curved', 'wpinked-widgets' ),
							'1.5em'                 => __( 'Round', 'wpinked-widgets' ),
						),
					),

				)
			),

			'pagination'                => array(
				'type'                     => 'section',
				'label'                    => __( 'Navigation' , 'wpinked-widgets' ),
				'hide'                     => true,
				'fields'                   => array(

					'activate'                => array(
						'type'                   => 'checkbox',
						'label'                  => __( 'Use navigation?', 'wpinked-widgets' ),
						'default'                => false
					),

					'type'                    => array(
						'type'                   => 'select',
						'label'                  => __( 'Type', 'wpinked-widgets' ),
						'default'                => 'normal',
						'options'                => array(
							'normal'                => __( 'Normal', 'wpinked-widgets' ),
							'ajax'                  => __( 'Ajax', 'wpinked-widgets' )
						),
						'description'            => __( 'Ajax is an experimental feature and a WIP. It should work as expected in most cases', 'wpinked-widgets' ),
					),

					'id'              => array(
						'label'                  => __( 'ID', 'wpinked-widgets' ),
						'type'                   => 'text',
						'description'            => __( 'Needed only if Ajax is used. Should be unique on the page. Must begin with alphabets[A-Za-z]. Should not contain spaces.', 'wpinked-widgets' ),
						'default'                => 'my-ajax-blog-widget',
					),

					'links'                    => array(
						'type'                   => 'select',
						'label'                  => __( 'Links', 'wpinked-widgets' ),
						'default'                => 'next-prev',
						'options'                => array(
							'next-prev'             => __( 'Next/Previous', 'wpinked-widgets' ),
							'paginate'              => __( 'Pagination', 'wpinked-widgets' )
						),
						'state_emitter'          => array(
							'callback'              => 'select',
							'args'                  => array( 'nav_type' )
						)
					),

					'older-text'              => array(
						'type'                   => 'text',
						'label'                  => __( 'Older Posts Text', 'wpinked-widgets' ),
						'default'                => 'Older Posts',
					),

					'older-icon'              => array(
						'type'                   => 'icon',
						'label'                  => __( 'Older Posts Icon', 'wpinked-widgets' ),
						'description'            => __( 'This will appear after the text', 'wpinked-widgets' ),
					),

					'newer-text'              => array(
						'type'                   => 'text',
						'label'                  => __( 'Newer Posts Text', 'wpinked-widgets' ),
						'default'                => 'Newer Posts',
					),

					'newer-icon'              => array(
						'type'                   => 'icon',
						'label'                  => __( 'Newer Posts Icon', 'wpinked-widgets' ),
						'description'            => __( 'This will appear before the text', 'wpinked-widgets' ),
					),

					'btn-theme'               => array(
						'type'                   => 'select',
						'label'                  => __( 'Navigation Theme', 'wpinked-widgets' ),
						'default'                => 'classic',
						'options'                => array(
							'classic'               => __( 'Classic', 'wpinked-widgets' ),
							'flat'                  => __( 'Flat', 'wpinked-widgets' ),
							'outline'               => __( 'Outline', 'wpinked-widgets' ),
							'threed'                => __( '3D', 'wpinked-widgets' ),
							'shadow'                => __( 'Shadow', 'wpinked-widgets' ),
							'deline'                => __( 'Deline', 'wpinked-widgets' ),
						),
					),

					'btn-align'               => array(
						'type'                   => 'select',
						'label'                  => __( 'Navigation Alignment', 'wpinked-widgets' ),
						'default'                => 'ends',
						'options'                => array(
							'left'                  => __( 'Left', 'wpinked-widgets' ),
							'right'                 => __( 'Right', 'wpinked-widgets' ),
							'center'                => __( 'Center', 'wpinked-widgets' ),
							'ends'                  => __( 'Ends', 'wpinked-widgets' ),
						),
						'state_handler'          => array(
							'nav_type[next-prev]'  => array( 'show' ),
							'nav_type[paginate]'   => array( 'hide' ),
						)
					),

					'btn-align-pages'         => array(
						'type'                   => 'select',
						'label'                  => __( 'Navigation Alignment', 'wpinked-widgets' ),
						'default'                => 'center',
						'options'                => array(
							'left'                  => __( 'Left', 'wpinked-widgets' ),
							'right'                 => __( 'Right', 'wpinked-widgets' ),
							'center'                => __( 'Center', 'wpinked-widgets' ),
						),
						'state_handler'          => array(
							'nav_type[next-prev]'  => array( 'hide' ),
							'nav_type[paginate]'   => array( 'show' ),
						)
					),

					'btn-size'                => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Size', 'wpinked-widgets' ),
						'default'                => 'standard',
						'options'                => array(
							'tiny'                  => __( 'Tiny', 'wpinked-widgets' ),
							'small'                 => __( 'Small', 'wpinked-widgets' ),
							'standard'              => __( 'Standard', 'wpinked-widgets' ),
							'large'                 => __( 'Large', 'wpinked-widgets' ),
						),
					),

					'btn-clr'                 => array(
						'type'                   => 'color',
						'label'                  => __( 'Button Highlight Color', 'wpinked-widgets' ),
						'description'            => __( 'Typically used as button background.', 'wpinked-widgets' ),
					),

					'btn-base'                => array(
						'type'                   => 'color',
						'label'                  => __( 'Button Base Color', 'wpinked-widgets' ),
						'description'            => __( 'Typically used as text color.', 'wpinked-widgets' ),
					),

					'btn-hover'               => array(
						'type'                   => 'checkbox',
						'default'                => true,
						'label'                  => __( 'Use button hover effect ?', 'wpinked-widgets' ),
					),

					'btn-click'               => array(
						'type'                   => 'checkbox',
						'default'                => true,
						'label'                  => __( 'Use button click effect ?', 'wpinked-widgets' ),
					),

					'btn-corners'             => array(
						'type'                   => 'select',
						'label'                  => __( 'Button Corners', 'wpinked-widgets' ),
						'default'                => '0.25em',
						'options'                => array(
							'0em'                   => __( 'Sharp', 'wpinked-widgets' ),
							'0.25em'                => __( 'Slightly curved', 'wpinked-widgets' ),
							'0.75em'                => __( 'Highly curved', 'wpinked-widgets' ),
							'1.5em'                 => __( 'Round', 'wpinked-widgets' ),
						),
					),
				)
			),
		);
	}

	function get_template_name($instance) {
		return $instance['design']['layout'];
	}

	function get_style_name($instance) {
		return $instance['design']['layout'];
	}

	function initialize() {

		$this->register_frontend_scripts(
			array(
				array( 'iw-blog-js', plugin_dir_url(__FILE__) . 'js/blog' . INKED_JS_SUFFIX . '.js', array( 'iw-match-height-js' ), INKED_SO_VER, true )
			)
		);

		$this->register_frontend_styles(
			array(
				array( 'iw-blog-css', plugin_dir_url(__FILE__) . 'css/blog.css', array(), INKED_SO_VER )
			)
		);

	}

	function modify_instance( $instance )  {
		// Only apply the transformation if the instance does not already have the new structure.
		if( empty( $instance['loop'] ) ) {
			$instance['loop'] = array();
			if( isset( $instance['posts'] ) ) $instance['loop']['posts'] = $instance['posts'];
			if( isset( $instance['current'] ) ) $instance['loop']['current'] = $instance['current'];

			unset( $instance['posts'] );
			unset( $instance['current'] );
		}
		return $instance;
	}

	function get_less_variables($instance) {
		if( empty( $instance ) ) return array();

		$less_variables = array(
			'design'          => $instance['design']['layout'],
			'columns'         => $instance['design']['columns'],
			'format'          => $instance['design']['format'],
			'img-width'       => $instance['design']['img-width'],
			'cnt-bg'          => $instance['styling']['content-bg'],
			't-size'          => $instance['styling']['title-size'],
			't-clr'           => $instance['styling']['title-color'],
			'e-size'          => $instance['styling']['excerpt-size'],
			'e-clr'           => $instance['styling']['excerpt-color'],
			'm-size'          => $instance['styling']['meta-size'],
			'm-clr'           => $instance['styling']['meta-color'],
			'm-link'          => $instance['styling']['meta-link'],
			'hl-clr'          => $instance['styling']['hl-color'],
			'meta-link-bold'  => $instance['styling']['meta-link-bold'],
			'gap'             => $instance['styling']['gap'],
			'img-ol'          => $instance['styling']['img-ol'],
			'img-ol-o'        => $instance['styling']['img-ol-o'],
			'img-e'           => $instance['styling']['img-expand'],
			'icon-bg'         => $instance['icons']['bg'],
			'icon-shape'      => $instance['icons']['bg-shape'],
			'icon-show'       => $instance['icons']['show'],
			'gap-left'        => $instance['styling']['none-left'],
			'btn-theme'       => $instance['styling']['btn-theme'],
			'btn-align'       => $instance['styling']['btn-align'],
			'btn-size'        => $instance['styling']['btn-size'],
			'btn-clr'         => $instance['styling']['btn-clr'],
			'btn-base'        => $instance['styling']['btn-base'],
			'btn-crnr'        => $instance['styling']['btn-corners'],
			'navi-theme'      => $instance['pagination']['btn-theme'],
			'navi-align'      => $instance['pagination']['btn-align'],
			'navi-align-pages'=> $instance['pagination']['btn-align-pages'],
			'navi-size'       => $instance['pagination']['btn-size'],
			'navi-clr'        => $instance['pagination']['btn-clr'],
			'navi-base'       => $instance['pagination']['btn-base'],
			'navi-crnr'       => $instance['pagination']['btn-corners'],
		);

		if ( function_exists( 'wpinked_pro_so_widgets' ) ) {
			if ( $instance['styling']['title-font']  ) {
				$title_font = siteorigin_widget_get_font( $instance['styling']['title-font'] );
				$less_variables['title-font-fly'] = $title_font['family'];
				if( ! empty( $title_font['weight'] ) ) {
					$less_variables['title-font-wt'] = $title_font['weight'];
				}
			}
			if ( $instance['styling']['meta-font']  ) {
				$meta_font = siteorigin_widget_get_font( $instance['styling']['meta-font'] );
				$less_variables['meta-font-fly'] = $meta_font['family'];
				if( ! empty( $meta_font['weight'] ) ) {
					$less_variables['meta-font-wt'] = $meta_font['weight'];
				}
			}
			if ( $instance['styling']['btn-font']  ) {
				$type_font = siteorigin_widget_get_font( $instance['styling']['btn-font'] );
				$less_variables['btn-font-fly'] = $type_font['family'];
				if( ! empty( $type_font['weight'] ) ) {
					$less_variables['btn-font-wt'] = $type_font['weight'];
				}
			}
		}

		return $less_variables;
	}

	function get_google_font_fields( $instance ) {
		if( empty( $instance ) || ! function_exists( 'wpinked_pro_so_widgets' ) ) return array();

		$fonts = array();
		if ( $instance['styling']['title-font'] ) {
			$fonts[] = $instance['styling']['title-font'];
		}
		if ( $instance['styling']['meta-font'] ) {
			$fonts[] = $instance['styling']['meta-font'];
		}
		if ( $instance['styling']['btn-font'] ) {
			$fonts[] = $instance['styling']['btn-font'];
		}
		return $fonts;
	}

}

siteorigin_widget_register( 'ink-blog', __FILE__, 'Inked_Blog_SO_Widget' );
