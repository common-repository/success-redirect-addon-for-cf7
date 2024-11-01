<?php
/**
 *
 * @link              #
 * @since             1.0.0
 * @package           cf7_success_redirect_addon
 *
 * @wordpress-plugin
 * Plugin Name:       Success redirect addon for cf7
 * Plugin URI:        #
 * Description:       Redirect to thank you page after the contact form 7 submission.
 * Version:           1.0.0
 * Author:            MageINIC
 * Author URI:        https://profiles.wordpress.org/wpteamindianic/#content-plugins
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       cf7-sr-addon
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
		exit;
}

define('CF7SR', plugin_dir_url(__FILE__));
define('CF7SR_PUBLIC_URL', CF7SR . 'public/');

register_activation_hook( __FILE__, 'cf7sr_activation' );

/**
 * Activation hook
 *
 * @since    		1.0.0
 * @param      	string    $plugin_name       	Success redirect addon for cf7
 * @param      	string    $version    				1.0.0.
 */

function cf7sr_activation() {
	if ( ! ( is_plugin_active('contact-form-7/wp-contact-form-7.php' ) ) ) {
		echo _e('<div class="error"><p>Contact Form 7 is not activated. To activate success redirect you must need to install or activate the contact form 7 plugin.</p></div>');
	}
}

/**
 * Deactivation hook
 *
 * @since    		1.0.0
 * @param      	string    $plugin_name       	Success redirect addon for cf7
 * @param      	string    $version    				1.0.0.
 */
register_deactivation_hook( __FILE__, 'cf7sr_deactivation' );
function cf7sr_deactivation() {
  // Deactivation rules here
}


/**
 * Init hook
 *
 * @since    		1.0.0
 * @param      	string    $plugin_name       	Success redirect addon for cf7
 * @param      	string    $version    				1.0.0.
 */
add_action( 'init', 'cf7sr_init' );
function cf7sr_init() {
	include_once plugin_dir_path( __FILE__ ).'admin/main.php';
}