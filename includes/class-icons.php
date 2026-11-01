<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides scalable, dependency-free SVG icons for the accessibility toolkit.
 */
class Icons {

	/**
	 * Whitelist of allowed HTML tags and attributes for rendering SVG securely with wp_kses.
	 *
	 * @return array
	 */
	public static function allowed_html() {
		return array(
			'svg'      => array(
				'class'       => true,
				'viewbox'     => true,
				'viewBox'     => true,
				'xmlns'       => true,
				'aria-hidden' => true,
				'focusable'   => true,
				'role'        => true,
				'width'       => true,
				'height'      => true,
				'fill'        => true,
				'stroke'      => true,
			),
			'path'     => array(
				'd'               => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
			),
			'circle'   => array(
				'cx'              => true,
				'cy'              => true,
				'r'               => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
			),
			'rect'     => array(
				'x'               => true,
				'y'               => true,
				'width'           => true,
				'height'          => true,
				'rx'              => true,
				'ry'              => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
			),
			'line'     => array(
				'x1'              => true,
				'y1'              => true,
				'x2'              => true,
				'y2'              => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
			),
			'polyline' => array(
				'points'          => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
			),
		);
	}

	/**
	 * Available button icons for the admin icon picker.
	 *
	 * @return array
	 */
	public static function get_picker_icons() {
		return array(
			'universal'     => __( 'Universal Access', 'sarrloop-accessibility' ),
			'accessibility' => __( 'Human Access', 'sarrloop-accessibility' ),
			'wheelchair'    => __( 'Wheelchair', 'sarrloop-accessibility' ),
			'eye'           => __( 'Vision / Eye', 'sarrloop-accessibility' ),
			'hearing'       => __( 'Hearing', 'sarrloop-accessibility' ),
			'person'        => __( 'Person', 'sarrloop-accessibility' ),
			'contrast'      => __( 'Contrast', 'sarrloop-accessibility' ),
			'keyboard'      => __( 'Keyboard', 'sarrloop-accessibility' ),
		);
	}

	/**
	 * Return raw SVG inner markup for a given icon key.
	 *
	 * @param string $key Icon key.
	 * @return string Inner SVG elements.
	 */
	public static function get_path( $key ) {
		$icons = array(
			// Universal Accessibility Symbol (Person inside a circle - Flaticon 12470648)
			'universal'       => '<circle cx="12" cy="12" r="9.5"/><circle cx="12" cy="6.8" r="1.8" fill="currentColor" stroke="none"/><path d="M5.5 10.2c2-.5 4.3-.7 6.5-.7s4.5.2 6.5.7"/><path d="M12 9.5v5.5"/><path d="M8.5 20l3.5-5 3.5 5"/>',

			// International Symbol of Access / Human Access (Vitruvian figure - Flaticon 6502370)
			'accessibility'   => '<circle cx="12" cy="4.5" r="2" fill="currentColor" stroke="none"/><path d="M4.5 10h15"/><path d="M5.5 6.5l6.5 3.5 6.5-3.5"/><path d="M12 7.5v8"/><path d="M7 21l5-5.5 5 5.5"/><path d="M10.5 21l1.5-5.5 1.5 5.5"/>',

			// Disabled / Wheelchair (Flaticon 5273832)
			'wheelchair'      => '<circle cx="14" cy="4.5" r="2" fill="currentColor" stroke="none"/><path d="M7.5 15a5 5 0 1 0 5 5"/><path d="M11.5 8.5h3.5l2 5.5H20"/><path d="M10 11.5l3 3.5"/>',

			// Vision / Eye (Flaticon 11245201)
			'eye'             => '<path d="M2 12s3.8-7 10-7 10 7 10 7-3.8 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3.2"/><circle cx="12" cy="12" r="1.2" fill="currentColor" stroke="none"/>',

			// Hearing / Ear (Flaticon 3988097)
			'hearing'         => '<path d="M6 10a5.5 5.5 0 0 1 11 0c0 3.5-2.5 5-2.5 7.5a2.5 2.5 0 0 1-5 0M9.5 10.5A2 2 0 0 1 13 12c0 1.2-1 1.8-1 3M19 8a5 5 0 0 1 0 8M21.5 5.5a8.5 8.5 0 0 1 0 13"/>',

			// Person / User (Flaticon 1077012)
			'person'          => '<circle cx="12" cy="7" r="4"/><path d="M5.5 21v-2a6.5 6.5 0 0 1 13 0v2"/>',

			// Contrast / Brightness (Flaticon 11053633)
			'contrast'        => '<circle cx="12" cy="12" r="9"/><path d="M12 3v18a9 9 0 0 0 0-18Z" fill="currentColor" stroke="none"/>',

			// Computer Keyboard (Flaticon 7546214)
			'keyboard'        => '<rect x="2.5" y="6" width="19" height="12" rx="2.5"/><path d="M6.5 10h.01M10 10h.01M14 10h.01M17.5 10h.01M7 14h10"/>',

			// Grayscale (Sun/Moon / Monochrome palette)
			'grayscale'       => '<circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18M12 7l6 6M12 11l4 4M12 15l2 2"/>',

			// Highlight links (Chain link)
			'highlight_links' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',

			// Stop animations (Pause / Motion stop)
			'stop_animations' => '<circle cx="12" cy="12" r="9"/><rect x="9" y="8.5" width="2.5" height="7" rx="1" fill="currentColor" stroke="none"/><rect x="13" y="8.5" width="2.5" height="7" rx="1" fill="currentColor" stroke="none"/>',

			// Large cursor (Mouse pointer)
			'large_cursor'    => '<path d="M4 3.5l14 10.5-5.5 1.5 3.5 6-2.5 1.5-3.5-6-4.5 3.5V3.5z" fill="currentColor" stroke="none"/>',

			// Readable font (Typography Aa)
			'readable_font'   => '<path d="M4 18l5-12 5 12M6 14h6M15 13a3 3 0 1 1 5 2.5V18M20 15a2.5 2.5 0 0 1-5 0"/>',

			// Enhanced focus (Viewfinder / Target)
			'focus'           => '<path d="M3 8V5a2 2 0 0 1 2-2h3M16 3h3a2 2 0 0 1 2 2v3M21 16v3a2 2 0 0 1-2 2h-3M8 21H5a2 2 0 0 1-2-2v-3"/><circle cx="12" cy="12" r="2.5"/>',

			// Read aloud / TTS (Speaker with sound waves)
			'tts'             => '<path d="M11 5L6 9H3v6h3l5 4V5zM15.5 8.5a5 5 0 0 1 0 7M18.5 5.5a9 9 0 0 1 0 13"/>',

			// Text size (Letter A with sizing symbols)
			'text_size'       => '<path d="M4 19L9.5 5l5.5 14M6.2 14h6.6M16 12h6M19 9v6"/>',

			// Reset all settings (Undo circular arrow)
			'reset'           => '<path d="M3 12a9 9 0 1 0 2.64-6.36L2.5 8.5"/><polyline points="2.5 3.5 2.5 8.5 7.5 8.5"/>',

			// Close (Clean cross using universal path)
			'close'           => '<path d="M18 6L6 18M6 6l12 12"/>',
		);

		return isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['universal'];
	}

	/**
	 * Build full SVG string with proper classes and attributes.
	 *
	 * @param string $key   Icon key.
	 * @param string $class Additional CSS classes.
	 * @return string Safe SVG markup.
	 */
	public static function get_svg( $key, $class = '' ) {
		$inner = self::get_path( $key );
		$svg   = sprintf(
			'<svg class="%s" viewBox="0 0 24 24" aria-hidden="true" focusable="false">%s</svg>',
			esc_attr( trim( 'sla-icon ' . $class ) ),
			$inner
		);

		return wp_kses( $svg, self::allowed_html() );
	}

	/**
	 * Output the sanitized SVG directly.
	 *
	 * @param string $key   Icon key.
	 * @param string $class Additional CSS classes.
	 */
	public static function render( $key, $class = '' ) {
		// Output is fully escaped via wp_kses in get_svg().
		echo self::get_svg( $key, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
