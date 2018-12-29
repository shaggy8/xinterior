<?php

if (!defined('ABSPATH')) die('No direct access.');

/**
 * Class to handle individual slideshow settings
 */
class MetaSlider_Slideshow_Settings {

	/**
	 * Themes class
	 * 
	 * @var object
	 */
	private $settings;

	/**
	 * Constructor
	 * 
	 * @param string $slideshow_id The settings object
	 */
	public function __construct($slideshow_id) {
		$this->settings = get_post_meta($slideshow_id, 'ml-slider_settings', true);
	}

	/**
	 * Returns a single setting
	 * 
	 * @param string $setting A single setting name
	 *
	 * @return mixed|WP_error The setting result or an error object
	 */
	public function get_single($setting) {
		return isset($this->settings[$setting]) ? $this->settings[$setting] : new WP_Error('invalid_setting', 'The setting was not found', array('status' => 404));
	}
}
