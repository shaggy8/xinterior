<?php

if (!defined('ABSPATH')) die('No direct access.');

/** 
 * Class to handle ajax endpoints, specifically used by vue components
 * If possible, keep logic here to a minimum.
 */
class MetaSLider_Api {
	
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
	 * Setup 
	 */
	public function setup() {
		$this->slideshows = new MetaSlider_Slideshows();
		$this->themes = MetaSlider_Themes::get_instance();
		$this->slides = MetaSlider_Slides::get_instance();
	}

	/**
	 * Used to access the instance
	 */
	public static function get_instance() {
		if (null === self::$instance) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Register routes for admin ajax. Even if not used these can still be available.
	 */
	public function register_admin_ajax_hooks() {

		// Slideshows
		add_action('wp_ajax_ms_get_all_slideshows', array(self::$instance, 'get_all_slideshows'));
		add_action('wp_ajax_ms_get_preview', array(self::$instance, 'get_preview'));

		// Themes
		add_action('wp_ajax_ms_get_all_free_themes', array(self::$instance, 'get_all_free_themes'));
		add_action('wp_ajax_ms_get_custom_themes', array(self::$instance, 'get_custom_themes'));
		add_action('wp_ajax_ms_set_theme', array(self::$instance, 'set_theme'));

		// Slides
		add_action('wp_ajax_ms_import_images', array(self::$instance, 'import_images'));
	}

	/**
	 * Returns all slideshows
	 * 
	 * @return array|WP_Error
	 */
    public function get_all_slideshows() {

		$capability = apply_filters('metaslider_capability', 'edit_others_posts');
		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}

		$slideshows = $this->slideshows->get_all_slideshows();

		if (is_wp_error($slideshows)) {
			return wp_send_json_error(array(
				'message' => $slideshows->get_error_message()
			), 401);
		}
		
		return wp_send_json_success($slideshows, 200);
    }

	/**
	 * Returns all custom themes
	 * 
	 * @return array|WP_Error
	 */
    public function get_custom_themes() {

		$capability = apply_filters('metaslider_capability', 'edit_others_posts');
		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}
		
		$themes = $this->themes->get_custom_themes();

		if (is_wp_error($themes)) {
			return wp_send_json_error(array(
				'message' => $themes->get_error_message()
			), 400);
		}

		return wp_send_json_success($themes, 200);
	}

	/**
	 * Returns all themes
	 * 
	 * @return array|WP_Error
	 */
    public function get_all_free_themes() {

		$user = wp_get_current_user();
		$capability = apply_filters('metaslider_capability', 'edit_others_posts');

		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}
		
		$themes = $this->themes->get_all_free_themes();

		if (is_wp_error($themes)) {
			return wp_send_json_error(array(
				'message' => $themes->get_error_message()
			), 400);
		}

		return wp_send_json_success($themes, 200);
	}
	
	/**
	 * Sets a specific theme
	 * 
	 * @param object $request The request
	 * @return array|WP_Error
	 */
    public function set_theme($request) {
		if (method_exists($request, 'get_param')) {
			$slideshow_id = $request->get_param('slideshow_id');
			$theme = $request->get_param('theme');
			$theme = is_array($theme) ? $theme : array();
		} else {

			// Support for admin-ajax
			$slideshow_id = $_POST['slideshow_id'];
			$theme = isset($_POST['theme']) ? $_POST['theme'] : array();
		}

		$user = wp_get_current_user();
		$capability = apply_filters('metaslider_capability', 'edit_others_posts');

		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}

		if (!is_array($theme)) {
			return wp_send_json_error(array(
				'message' => __('The request format was not valid.', 'ml-slider')
			), 415);
		}
		
		$response = $this->themes->set(absint($slideshow_id), $theme);
		
		if (!$response) {
			return wp_send_json_error(array(
				'message' => 'There was an issue while attempting to save the theme. Please refresh and try again.'
			), 400);
		}

		// If we made it this far, return the theme data
		return wp_send_json_success($theme, 200);
    }
	
	/**
	 * Returns the preview HTML
	 * 
	 * @param object $request The request
	 * @return array|WP_Error
	 */
    public function get_preview($request) {
		if (method_exists($request, 'get_param')) {
			$slideshow_id = $request->get_param('slideshow_id');
			$theme_id = $request->get_param('theme_id');
		} else {
			// Support for admin-ajax
			$slideshow_id = $_GET['slideshow_id'];
			$theme_id = isset($_GET['theme_id']) ? $_GET['theme_id'] : array();
		}

		$user = wp_get_current_user();
		$capability = apply_filters('metaslider_capability', 'edit_others_posts');

		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}

		// The theme id can be either a string or null, exit if it's something else
		if (!is_null($theme_id) && !is_string($theme_id)) {
			return wp_send_json_error(array(
				'message' => __('The request format was not valid.', 'ml-slider')
			), 415);
		}

		// If the slideshow was deleted
		$slideshow = get_post($slideshow_id);
		if ('publish' !== $slideshow->post_status) {
			return wp_send_json_error(array(
				'message' => __('This slideshow is no longer available.', 'ml-slider')
			), 410);
		}

		$html = $this->slideshows->preview(
			absint($slideshow_id), $theme_id
		);

		if (!$html || is_wp_error($html)) {
			return wp_send_json_error(array(
				'message' => 'There was an issue while attempting to load the preview. Please refresh and try again.'
			), 400);
		}

		return wp_send_json_success($html, 200);
	}
	
	/**
	 * Import theme images
	 * 
	 * @param object $request The request
	 * @return array|WP_Error
	 */
    public function import_images($request) {
		if (method_exists($request, 'get_param')) {
			$slideshow_id = $request->get_param('slideshow_id');
			$theme_id = $request->get_param('theme_id');
		} else {

			// Support for admin-ajax
			$slideshow_id = $_POST['slideshow_id'];
			$theme_id = isset($_POST['theme_id']) ? $_POST['theme_id'] : 'none';
		}

		$user = wp_get_current_user();
		$capability = apply_filters('metaslider_capability', 'edit_others_posts');

		if (!current_user_can($capability)) {
			return wp_send_json_error(array(
				'message' => __('You do not have access to this resource.', 'ml-slider')
			), 401);
		}
		
		$import = $this->slides->import(absint($slideshow_id), $theme_id);

		if (is_wp_error($import)) {
			return wp_send_json_error(array(
				'message' => $import->get_error_message()
			), 400);
		}

		return wp_send_json_success($import, 200);
	}
}

if (class_exists('WP_REST_Controller')) :
	/**
	 * Class to handle REST route api endpoints.
	 */
	class MetaSlider_REST_Controller extends WP_REST_Controller {

		/**
		 * Namespace and version for the API
		 * 
		 * @var string
		 */
		protected $namespace = 'metaslider/v1';

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action('rest_api_init', array($this, 'register_routes'));
			$this->api = MetaSLider_Api::get_instance();
			$this->api->setup();
		}

		/**
		 * Register routes
		 */
		public function register_routes() {

			register_rest_route($this->namespace, '/slideshows/all', array(
				array(
					'methods' => 'GET',
					'callback' => array($this->api, 'get_all_slideshows')
				)
			));
			register_rest_route($this->namespace, '/slideshow/preview', array(
				array(
					'methods' => 'GET',
					'callback' => array($this->api, 'get_preview')
				)
			));
			
			register_rest_route($this->namespace, '/themes/all', array(
				array(
					'methods' => 'GET',
					'callback' => array($this->api, 'get_all_free_themes')
				)
			));
			register_rest_route($this->namespace, '/themes/custom', array(
				array(
					'methods' => 'GET',
					'callback' => array($this->api, 'get_custom_themes')
				)
			));
			register_rest_route($this->namespace, '/themes/set', array(
				array(
					'methods' => 'POST',
					'callback' => array($this->api, 'set_theme')
				)
			));
			
			register_rest_route($this->namespace, '/import/images', array(
				array(
					'methods' => 'POST',
					'callback' => array($this->api, 'import_images')
				)
			));
		}
	}
endif;