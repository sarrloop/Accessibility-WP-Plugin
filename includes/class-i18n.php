<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles internationalization and language adaptation.
 */
class I18n {

	/**
	 * Initialize internationalization.
	 * Handled automatically just-in-time by WordPress 4.6+ for plugins hosted on WordPress.org.
	 */
	public static function init() {
		// Handled automatically just-in-time by WordPress core for WordPress.org plugins.
	}

	/**
	 * Get the current site locale code.
	 *
	 * @return string Current locale (e.g. 'en_US', 'es_ES', 'fr_FR', 'ar').
	 */
	public static function get_locale() {
		if ( function_exists( 'determine_locale' ) ) {
			return determine_locale();
		}
		return get_locale();
	}

	/**
	 * Check if the current locale is Right-To-Left (RTL).
	 *
	 * @return bool True if RTL.
	 */
	public static function is_rtl() {
		return is_rtl();
	}

	/**
	 * Get the localized floating button label.
	 * If user entered a custom label in settings, use that; otherwise translate default label.
	 *
	 * @param string $saved_label Stored button label.
	 * @return string Localized label.
	 */
	public static function get_button_label( $saved_label ) {
		$default = 'Accessibility';

		// If empty or default, translate based on site language.
		if ( empty( $saved_label ) || $default === trim( $saved_label ) ) {
			return __( 'Accessibility', 'sarrloop-accessibility' );
		}

		return $saved_label;
	}

	/**
	 * Return all frontend UI strings translated to the current site language.
	 *
	 * @return array
	 */
	public static function get_frontend_strings() {
		return array(
			'accessibility' => __( 'Accessibility', 'sarrloop-accessibility' ),
			'open'          => __( 'Open accessibility options', 'sarrloop-accessibility' ),
			'close'         => __( 'Close accessibility options', 'sarrloop-accessibility' ),
			'reset'         => __( 'Reset all settings', 'sarrloop-accessibility' ),
			'options'       => __( 'Accessibility options', 'sarrloop-accessibility' ),
			'textSize'      => __( 'Text size', 'sarrloop-accessibility' ),
			'increase'      => __( 'Increase text size', 'sarrloop-accessibility' ),
			'decrease'      => __( 'Decrease text size', 'sarrloop-accessibility' ),
			'resetText'     => __( 'Reset text size', 'sarrloop-accessibility' ),
			'contrast'      => __( 'High contrast', 'sarrloop-accessibility' ),
			'grayscale'     => __( 'Grayscale', 'sarrloop-accessibility' ),
			'links'         => __( 'Highlight links', 'sarrloop-accessibility' ),
			'animations'    => __( 'Stop animations', 'sarrloop-accessibility' ),
			'cursor'        => __( 'Large cursor', 'sarrloop-accessibility' ),
			'font'          => __( 'Readable font', 'sarrloop-accessibility' ),
			'focus'         => __( 'Enhanced focus', 'sarrloop-accessibility' ),
			'read'          => __( 'Read aloud', 'sarrloop-accessibility' ),
			'keyboard'      => __( 'Keyboard navigation', 'sarrloop-accessibility' ),
		);
	}
}
