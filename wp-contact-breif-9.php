<?php

define('PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once "functions.php";

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              github.com/ayoubmehd
 * @since             0.1.0
 * @package           Wp_Contact_Breif_9
 *
 * @wordpress-plugin
 * Plugin Name:       wp contact breif 9
 * Plugin URI:        github.com/ayoubmehd/wp-contact-breif-9
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ayoub ELMAHDAOUI
 * Author URI:        github.com/ayoubmehd
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-contact-breif-9
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WP_CONTACT_BREIF_9_VERSION', '0.1.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-contact-breif-9-activator.php
 */
register_activation_hook(__FILE__, 'wp_contact_breif_9_activate');
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-contact-breif-9-deactivator.php
 */
register_uninstall_hook(__FILE__, 'wp_contact_breif_9_uninstall');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

if (isset($_POST["contact_form"])) {
	require_once plugin_dir_path(__FILE__) . "/public/send_mail.php";
	add_action("init", "wp_contact_breif_9");
}
add_action('admin_menu', 'wp_contact_breif_9_admin_link');
add_action('admin_init', 'wp_contact_breif_9_options');
add_shortcode('wp_contact_b9', 'wp_contact_breif_9_add_form');
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_wp_contact_breif_9()
{
	add_action("wp_mail_failed", "wp_contact_breif_9_show_error");
}
run_wp_contact_breif_9();
