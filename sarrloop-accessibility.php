<?php
/**
 * Plugin Name: Sarrloop Accessibility
 * Description: A lightweight accessibility toolkit with user-controlled text, contrast, motion, cursor, reading, focus, and keyboard assistance features.
 * Version: 2.3.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Author: Mohammad Naim Rahman Alvy
 * Author URI: https://sarrloop.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sarrloop-accessibility
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SARRLOOP_ACCESSIBILITY_VERSION', '2.3.0' );
define( 'SARRLOOP_ACCESSIBILITY_FILE', __FILE__ );
define( 'SARRLOOP_ACCESSIBILITY_DIR', plugin_dir_path( __FILE__ ) );
define( 'SARRLOOP_ACCESSIBILITY_URL', plugin_dir_url( __FILE__ ) );

require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-icons.php';
require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-i18n.php';
require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-settings.php';
require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-frontend.php';
require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-admin.php';
require_once SARRLOOP_ACCESSIBILITY_DIR . 'includes/class-plugin.php';

\Sarrloop\Accessibility\Plugin::init();
