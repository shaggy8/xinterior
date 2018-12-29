=== Plugin Name ===
Contributors: halgatewood
Donate link: https://halgatewood.com/donate/
Tags: testimonials, sidebar, shortcode, testimonial, praise, homage, testimony, witness, appreciation, green light, rotator, rotators, for developers
Requires at least: 3.7
Tested up to: 5.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add Testimonials to your WordPress Blog or Company Website.

== Description ==

Finally a really simple way to manage testimonials on your site. This plugin creates a testimonial and a testimonial rotator custom post type, complete with WordPress admin fields for adding testimonials and assigning them to rotators for display. It includes a Widget and Shortcode to display the testimonials.

= Documentation =
* [Getting Started](https://halgatewood.com/docs/plugins/testimonial-rotator/getting-started-testimonial-rotator)
* [Rotator Attributes](https://halgatewood.com/docs/plugins/testimonial-rotator/full-list-rotator-attributes)
* [Creating a Custom Theme](https://halgatewood.com/docs/plugins/testimonial-rotator/creating-a-theme)
* [CSS Styling](https://halgatewood.com/docs/plugins/testimonial-rotator/css-targeting)
* [Available Filters](https://halgatewood.com/docs/plugins/testimonial-rotator/available-filters)
* [Demos](https://halgatewood.com/docs/plugins/testimonial-rotator/demos)
* [Full List of Guides](http://halgatewood.com/docs/plugins/testimonial-rotator/)


= The Features You Need = 
* Change all rotator settings in the admin
* Add testimonials to multiple rotators
* Prev/Next Buttons
* Vertical Align Testimonials based on Height
* Star Ratings
* Author information field
* Testimonial single template
* Ability to make custom templates
* hReview Support
* Pagination in List Format
* Ability to show the Add Rotator section based on User Role
* Settings section
* New hooks and filters

= Help Videos =
https://www.youtube.com/watch?v=SVg73QdgSuM

https://www.youtube.com/watch?v=IMWVDHbtnVw


== Installation ==

1. Add plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a testimonial rotator
1. Create testimonials and specify which rotator you want it to be a part of
1. Add the rotators to your pages using the shortcode or developers can add the placeholders in their themes.

Check out this [Help Guide](https://halgatewood.com/docs/plugins/testimonial-rotator/getting-started-testimonial-rotator) for more information about getting started and what all the settings mean.


== Screenshots ==

1. Clean admin panel. Available only to those you want.
2. Simple rotator settings.
3. Uses built-in WordPress functionality like excerpt, featured images and menu order.
4. A Testimonial Rotator inserted into a block of text with a shortcode.
5. hReview data included, great for SEO.
6. Create your own custom templates.
7. Widget Options, override rotator settings if needed.
8. Settings area where you can customize error handling and hide Font Awesome if you need to.



== Changelog ==

= 2.5.2 - Oct. 19, 2018 =
* FIX: added wp_reset_postdata to testimonial_rotator_rating

= 2.5 & 2.5.1 - Sept. 25, 2018 =
* NEW: testimonial_rotator_rating shortcode to show the aggregate rating only
* NEW: Author information displayed on all testimonials page in admin
* FIX: Logic for the hReview snippits was incorrect. Much more accurate now.
* FIX: Single template layout will match the look of the rotator theme it belongs too. This may required changes to your single-testimonial.php file or you may need to create a single-testimonial.php file.
* FIX: Sort Testimonials by title in admin
* UPDATED: .pot file

= 2.4 - Sept. 4, 2018 =
* FIX: Single templates not looking in the new Testimonial Theme Pack plugin.
* REMOVED: Theme Pack Updating functionality. Now moved in to the Theme Pack plugin.

= 2.3 - August 21, 2018 =
* UPDATE: Added check for new theme pack

= 2.2.7 - Dec. 18, 2017 =
* NEW: filter to change the order of the testimonials: testimonial_rotator_order

= 2.2.6 - Dec. 12, 2017 =
* FIX: Previous/Next buttons not working in widget override settings.

= 2.2.5 - Sept. 27, 2017 =
* NEW: filter to set 'fx' - testimonial_rotator_fx
* FIX: Replaced create_function for PHP 7.2 compatibility

= 2.2.4.1 - Nov. 15, 2016 =
* MISSED: Added the title to the single.php file for Longform

= 2.2.4 - Nov. 15, 2016 =
* NEW: Ability to hide: title, stars, body, or author from the Rotator settings.
* FIX: Added the 'title' to the Longform theme. Please check the new 'hide title' option on your Rotator settings if you don't want it to show.
* TYPO: I can't spell my own theme names: Lonform is now correctly labeled Longform

= 2.2.3 - Nov. 3, 2016 =
* FIX: Rotator List pagination

= 2.2.2 - October 27th, 2016 =
* NEW: Custom CSS setting in the admin under 'Testimonials' -> 'Settings'

= 2.2.1 - October 27th, 2016 =
* NEW: Settings when adding a widget to hide all elements of the rotator: title, stars, body, author and hreview
* FIX: Boolean attributes can now be passed as 'true/false' or '1/0'
* FIX: Updated language file

= 2.2 - August 31, 2016 =
* HUGE FIX: Fixed compatibility of my plugin with Next Gen Gallery, Social Sharing plugins, Jetpack and more!
* NEW: Added new 'show_link' and 'link_text' attribute with settings in the widget options to make linking to your testimonials super easy!
* NEW: Added form labels to elements so you don't have to click only on the checkbox or radio button
* FIX: Updated language file

= 2.1.5 - Apr 19, 2016 = 
* Change the single page testimonial by adding a content-testimonial.php file in your theme folder.

= 2.1.4.1 - Apr 18, 2016 =
Cleaned up error in the admin

= 2.1.4 - Apr 18, 2016 =
* PHP7 Compatibility

= 2.1.3 - Jan 21, 2016 =
* Single template for Longform template had an extra closing div tag. Thanks @kevinmorton

= 2.1.2 - Jan 18, 2016 =
* Fixed Single Page Testimonial. Test was missing, not single page pulls from template selected
* New filters to improve single page.

= 2.1.1 - Jan. 13, 2016 =
* Fixed excerpt issue cauing fatal errors
* Moved get_the_excerpt into a wrapper function 'testimonial_rotator_excerpt' with a filter 'testimonial_rotator_the_excerpt' to modify the contents

= 2.1 - Jan. 12, 2016 =
* New free theme: Longform!
* Added shortcode for single testimonial [testimonial_single]
* Added testimonial count to rotator list view
* See testimonials associated to rotator in edit screen
* Change the element for the title in settings (default H2)
* Code added for upcoming custom templates
* Flip transitions added
* Fix hreview itemreviewed name
* Code cleanup and better sanitation of variables
* Updated text domain from testimonial_rotator to testimonial-rotator
* Cycle log hidden by default, log="true" to turn on. Thanks @michaelbragg
* Override rotator settings in the widget. (allows you to change settings for the 'All Rotators' option)
* Specify the excerpt length in the widget settings (requires PHP 5.3+)
* New shortcode parameters to hide sections of the testimonial: hide_title, hide_stars, hide_body, hide_author

= 2.0.6 - Updated July 13, 2015 =
* Changed WP_Widget() to __construct, for maximum PHP5 support

= 2.0.5 - Updated July 20, 2014 =
* New filter to change the stars to any FontAwesome Icon
* Improved stability when upgrading from 1.4+
* When 'Previous/Next' is checked in the rotator it will automatically turn on paged for the list view

= 2.0.4 - Updated July 7, 2014 =
* Ability to center the stars
* Hopefully fixed up issues with the_content on the single page
* Added new filter for pause on hover
* Added new filter for loading scripts in the footer
* Added new filter for settings on Widgets
* Added Rotator IDs to most filters so they can used on a rotator basis

= 2.0.3 - Updated May 30, 2014 =
* Added thumbnail setting for Rotator
* Wrapped Init functions with is_admin for the Admin only hooks

= 2.0.2 - Updated May 20, 2014 =
* Fixed Widget Title
* Fixed rotator timeout and transition speed

= 2.0.1 - May 15, 2014 =
* Added wrapper div around quote part of testimonial

= 2.0 -  May 15, 2014 =
* Change all rotator settings in the admin
* Add testimonials to multiple rotators
* Prev / Next Buttons
* Vertical Align Testimonials based on Height
* Font Awesome
* Star Ratings
* Hide Featured Image
* Author Cite Field
* Testimonial single template
* Ability to make custom templates (Theme Pack coming soon)
* hReview Support
* Pagination in List Format
* Ability to show the Add Rotator section based on User Role
* New Settings section
* New hooks added
* Code cleanup and more commenting

= 1.4 =
* Use shortcode to display testimonials from all rotators by not passing in an 'id' attribute
* Completed preparation for translation, wrapped all text in __()
* Two new filters for the 'supports' section of the register_post_type: testimonial_rotator_supports and testimonial_rotator_testimonial_supports
* Two new filters for auto-height 'calc': testimonial_rotator_calc and testimonial_rotator_widget_fx

= 1.3.7 =
* Updated icon for WordPress 3.8
* Fixed translation and added languages folder, moved .pot to this folder
* Moved styles and scripts from action wp_head to wp_enqueue_scripts
* Fixed images and order not showing in admin list view
* Prepped for an upcoming PRO version!

= 1.3.6 =
* Fix bug not rotating widget

= 1.3.5 =
* Changed cycle2 to cycletwo as it was conflicting with other plugins

= 1.3.4 = 
* Fixed small bug where some themes were adding extra spaces and breaking the rotator

= 1.3.3 =
* Switched from jQuery Cycle1 to Cycle 2
* Widget now uses Rotator FX and Timeout settings
* Added .testimonial_rotator_widget_blockquote class to widget blockquote to help override some CSS problems with themes.
* Rotator Height is now fixed at the highest testimonial instead of auto adjusting the height

= 1.3.2 =
reset query bug

= 1.3 =
* Randomize testimonials without code
* Hide the title
* Display excerpt or full testimonial in width
* Display specific rotator in widget
* More shortcode examples
* The widget has been updated with all the features as options, no more coding!

= 1.2 =
* main testimonial now uses the_content filter to make styling better.
* include rotator using the rotator slug, for example: [testimonial_rotator id=homepage]
* new attributes to the shortcode: 
** hide_title: hides the h2 heading
** format: settings format=list will remove rotator and display all testimonials
** limit: set the number of testimonials to display, new default is -1 or all testimonials

= 1.1.5 =
* small bug in widget javascript

= 1.1.4 =
* reworking loading of scripts for rotator, should be sorted now.

= 1.1.3 =
* jQuery ready function always

= 1.1.2 =
* Testimonial widget using jQuery ready function instead of window.onload

= 1.1.1 =
* Can't remember, forgot to put this one in (not cool of me, I know)

= 1.1 =
* Small fix to make the testimonial widget fit it's container

= 1.0 =
* Initial load of the plugin.

