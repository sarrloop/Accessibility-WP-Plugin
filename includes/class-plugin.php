<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin initializer.
 */
class Plugin {

	/**
	 * Bootstrap plugin components.
	 */
	public static function init() {
		I18n::init();
		Admin::init();
		Frontend::init();
	}
}
