<?php

if (!defined('ABSPATH')) die('No direct access.');

/** 
 * Class to handle individual slides
 */
class MetaSlider_Slides {
	
	/**
	 * The id of the slideshow 
	 * 
	 * @var string
	 */
    protected $slideshow_id;

	/**
	 * Theme instance
	 * 
	 * @var object
	 * @see get_instance()
	 */
	protected static $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Used to access the instance
	 */
	public static function get_instance() {
		if (null === self::$instance) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Method to get all slides assigned to the slideshow
	 * Can be called statically to get the entire collection of slides
	 */
	public static function all() {}

	/**
	 * Method to import image slides from a theme
	 * 
	 * @param string $slideshow_id - The id of the slideshow
	 * @param string $theme_id 	   - The folder name of a theme
	 * 
	 * @return WP_Error|array - The array of ids that were uploaded
	 */
	public function import($slideshow_id, $theme_id = null) {

		// Check for the images folder
		if (!file_exists(METASLIDER_THEMES_PATH . 'images')) {
			return new WP_Error('images_not_found', __('We could not find any images to import.', 'ml-slider'), array('status' => 404));
		}

		$images = array();
		// Check for the manifest, and load theme specific images
		if (!is_null($theme_id) && file_exists(METASLIDER_THEMES_PATH . 'manifest.php')) {
			$themes = (include METASLIDER_THEMES_PATH . 'manifest.php');

			// Check if the theme is available and has images set
			foreach ($themes as $theme) {
				if (!empty($theme['images']) && $theme_id === $theme['folder']) {
					$images = $theme['images'];
				}
			}
		}
		
		if (!class_exists('MetaImageSlide')) {
			require_once(METASLIDER_PATH . 'inc/slide/metaslide.image.class.php');
		}

		// Copied from metaslide.image.class.php
		// TODO refactor and place in this class
		$slide = new MetaImageSlide;

		// This uploads the images then creates the slides
        $slide_ids = $slide->create_slides(
			$slideshow_id, array_map(array($this, 'make_image_slide_data'), $this->upload_theme_images($images))
		);

		// If there were errors creating slides, we can still attempt to crop
		foreach($slide_ids as $slide_id) {
			$slide->resize_slide($slide_id, $slideshow_id);
		}
		return $slide_ids;
	}

	/**
	 * Adds the type and image id to an array
	 *
	 * @param  int $image_id Image ID
	 * @return array
	 */
	public function make_image_slide_data($image_id) {
		return array(
			'type' => 'image',
			'id'   => absint($image_id)
		);
	}

	/**
	 * Method to import image slides from a theme
	 *
	 * @param array $images_to_use - The full path to the image dir in the theme folder
	 */
	private function upload_theme_images($images_to_use = array())
	{
		if (!function_exists('media_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
		}

		// The images directory in the themes folder
		$directory = METASLIDER_THEMES_PATH . 'images/';

		// Get list of images in the folder
		$images = array_filter(scandir($directory), array($this, 'filter_images'));

		// If images are specified, make sure they exist and use them. if not, use 4 at random
		$images = !empty($images_to_use) ? $this->pluck_images($images_to_use, $images) : array_rand(array_flip($images), 4);

		// Upload and attach images to WP
		$wp_upload_dir = wp_upload_dir();
		$successful_uploads = array();
		foreach ($images as $filedata) {

			// $filedata might be an array with filename, caption, alt and title, 
			// or it might be just a string with the filename
			$filename = $filedata;
			$data = array();
			if (is_array($filedata) && !empty($filedata['filename'])) {
				$filename = $filedata['filename'];
				
				// Theme developers can override the caption, etc
				$data = $filedata;
			}

			$source = trailingslashit($directory) . $filename;
			if (file_exists($source)) {
				$filename = wp_unique_filename(trailingslashit($wp_upload_dir['path']), $filename);
				$destination = trailingslashit($wp_upload_dir['path']) . $filename;
				if (copy($source, $destination)) {
					if ($attach_id = $this->attach_image_to_media_library($destination, $data)) {
						array_push($successful_uploads, $attach_id);
					}
				}
			}
		}
		return $successful_uploads;
	}

	/**
     * Method to use filter out non-images
     *
     * @param array $string - a filename scanned from the images dir
	 * 
	 * @return boolean
     */
	private function filter_images($string) {
		return preg_match('/jpg$/i', $string);
	}

	/**
     * Method to use filter out images that might not exist
     *
     * @param array $images_to_use - Images defined in the manifest
     * @param array $images 	   - Images from the images folder
	 * 
	 * @return array
     */
	private function pluck_images($images_to_use, $images) {

		// For the filename/caption scenario
		if (!empty($images_to_use[0]) && is_array($images_to_use[0])) {

			// Just return the multi-dimentional array and handle the filecheck later
			return $images_to_use;
		}

		// Return the images specified by the filename or four random
		$images_that_exist = array_intersect($images_to_use, $images);
		return (!empty($images_that_exist)) ? $images_that_exist : array_rand(array_flip($images), 4);
	}

	/**
     * Method to add a file to the media library
     *
     * @param string $filename   - The full path to the image dir in the media library
     * @param array  $image_data - Optional data to attach / override to the image
	 * 
	 * @return int
     */
	private function attach_image_to_media_library($filename, $image_data = array()) {

		$filetype = wp_check_filetype(basename($filename), null);
		$wp_upload_dir = wp_upload_dir();

		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($filename),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_status'    => 'publish'
		);

		// Add the caption, title and description if set. This used human-friendly words
		// instead of WP specific to make it more simple for theme developers
		if (!empty($image_data['caption'])) {
			$image_data['post_excerpt'] = $image_data['caption'];
			unset($image_data['caption']);
		}
		if (!empty($image_data['title'])) {
			$image_data['post_title'] = $image_data['title'];
			unset($image_data['title']);
		}
		if (!empty($image_data['description'])) {
			$image_data['post_content'] = $image_data['description'];
			unset($image_data['description']);
		}

		// Merge the theme data with the defaults
		$data = array_merge($attachment, $image_data);

		// Insert the attachment
		$attach_id = wp_insert_attachment($data, $filename);

		// Generate the metadata for the attachment, and update the database record
		$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
		wp_update_attachment_metadata($attach_id, $attach_data);

		// The theme can set the alt tag too if needed
		if ($attach_id && !empty($image_data['alt'])) {
			update_post_meta($attach_id, '_wp_attachment_image_alt', $image_data['alt']);
		}

		return $attach_id;
	}
}
