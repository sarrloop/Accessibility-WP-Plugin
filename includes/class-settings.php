<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages plugin settings and sanitization according to WordPress standards.
 */
class Settings {

	const OPTION = 'sarrloop_accessibility_settings';

	/**
	 * Default plugin options.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enabled'        => 1,
			'position'       => 'right',
			'button_display' => 'icon',
			'button_label'   => 'Accessibility',
			'button_icon'    => 'universal',
			'button_color'   => '#4357B8',
			'design'         => 'card',
			'features'       => array(
				'text_size'       => 1,
				'contrast'        => 1,
				'grayscale'       => 1,
				'highlight_links' => 1,
				'stop_animations' => 1,
				'large_cursor'    => 1,
				'readable_font'   => 1,
				'focus'           => 1,
				'tts'             => 1,
				'keyboard'        => 1,
			),
		);
	}

	/**
	 * Valid button display styles.
	 *
	 * @return array
	 */
	public static function allowed_button_displays() {
		return array( 'icon', 'both', 'text' );
	}

	/**
	 * Valid icon keys.
	 *
	 * @return array
	 */
	public static function allowed_icons() {
		return array( 'universal', 'accessibility', 'wheelchair', 'eye', 'hearing', 'person', 'contrast', 'keyboard' );
	}

	/**
	 * Valid widget design layouts.
	 *
	 * @return array
	 */
	public static function allowed_designs() {
		return array( 'sidebar', 'card', 'minimal' );
	}

	/**
	 * Retrieve saved settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get() {
		$saved    = get_option( self::OPTION, array() );
		$defaults = self::defaults();

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		$color = isset( $saved['button_color'] ) ? sanitize_hex_color( $saved['button_color'] ) : null;

		return array(
			'enabled'        => ! empty( $saved['enabled'] ) ? 1 : 0,
			'position'       => in_array( isset( $saved['position'] ) ? $saved['position'] : '', array( 'left', 'right' ), true ) ? $saved['position'] : $defaults['position'],
			'button_display' => in_array( isset( $saved['button_display'] ) ? $saved['button_display'] : '', self::allowed_button_displays(), true ) ? $saved['button_display'] : $defaults['button_display'],
			'button_label'   => isset( $saved['button_label'] ) ? sanitize_text_field( $saved['button_label'] ) : $defaults['button_label'],
			'button_icon'    => in_array( isset( $saved['button_icon'] ) ? $saved['button_icon'] : '', self::allowed_icons(), true ) ? $saved['button_icon'] : $defaults['button_icon'],
			'button_color'   => ! empty( $color ) ? $color : $defaults['button_color'],
			'design'         => in_array( isset( $saved['design'] ) ? $saved['design'] : '', self::allowed_designs(), true ) ? $saved['design'] : $defaults['design'],
			'features'       => wp_parse_args(
				( isset( $saved['features'] ) && is_array( $saved['features'] ) ) ? $saved['features'] : array(),
				$defaults['features']
			),
		);
	}

	/**
	 * Sanitize submitted settings for the WordPress Settings API.
	 *
	 * @param array $input Raw input.
	 * @return array Sanitized settings.
	 */
	public static function sanitize( $input ) {
		$defaults = self::defaults();
		$output   = array();

		if ( ! is_array( $input ) ) {
			return $defaults;
		}

		$output['enabled'] = ! empty( $input['enabled'] ) ? 1 : 0;

		$position_val = isset( $input['position'] ) ? $input['position'] : '';
		$output['position'] = in_array( $position_val, array( 'left', 'right' ), true )
			? $position_val
			: $defaults['position'];

		$display_val = isset( $input['button_display'] ) ? $input['button_display'] : '';
		$output['button_display'] = in_array( $display_val, self::allowed_button_displays(), true )
			? $display_val
			: $defaults['button_display'];

		$output['button_label'] = isset( $input['button_label'] )
			? sanitize_text_field( $input['button_label'] )
			: $defaults['button_label'];

		$icon_val = isset( $input['button_icon'] ) ? $input['button_icon'] : '';
		$output['button_icon'] = in_array( $icon_val, self::allowed_icons(), true )
			? $icon_val
			: $defaults['button_icon'];

		$clean_color = isset( $input['button_color'] ) ? sanitize_hex_color( $input['button_color'] ) : null;
		$output['button_color'] = ! empty( $clean_color ) ? $clean_color : $defaults['button_color'];

		$design_val = isset( $input['design'] ) ? $input['design'] : '';
		$output['design'] = in_array( $design_val, self::allowed_designs(), true )
			? $design_val
			: $defaults['design'];

		$output['features'] = array();
		foreach ( $defaults['features'] as $key => $default_val ) {
			$output['features'][ $key ] = ! empty( $input['features'][ $key ] ) ? 1 : 0;
		}

		return $output;
	}

	/**
	 * Check if a specific feature is enabled.
	 *
	 * @param string $feature Feature identifier.
	 * @return bool
	 */
	public static function feature_enabled( $feature ) {
		$settings = self::get();
		return ! empty( $settings['features'][ $feature ] );
	}
}
