<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the admin settings interface.
 */
class Admin {

	/**
	 * Hook admin actions.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/**
	 * Register settings menu under Settings.
	 */
	public static function menu() {
		add_options_page(
			__( 'Sarrloop Accessibility', 'sarrloop-accessibility' ),
			__( 'Sarrloop Accessibility', 'sarrloop-accessibility' ),
			'manage_options',
			'sarrloop-accessibility',
			array( __CLASS__, 'page' )
		);
	}

	/**
	 * Register settings option.
	 */
	public static function settings() {
		register_setting(
			'sarrloop_accessibility_group',
			Settings::OPTION,
			array(
				'sanitize_callback' => array( Settings::class, 'sanitize' ),
			)
		);
	}

	/**
	 * Enqueue admin stylesheet and scripts.
	 *
	 * @param string $hook Page hook.
	 */
	public static function assets( $hook ) {
		if ( 'settings_page_sarrloop-accessibility' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'sarrloop-accessibility-admin',
			SARRLOOP_ACCESSIBILITY_URL . 'admin/css/admin.css',
			array(),
			SARRLOOP_ACCESSIBILITY_VERSION
		);

		wp_enqueue_script(
			'sarrloop-accessibility-admin',
			SARRLOOP_ACCESSIBILITY_URL . 'admin/js/admin.js',
			array(),
			SARRLOOP_ACCESSIBILITY_VERSION,
			true
		);
	}

	/**
	 * Render the admin settings page.
	 */
	public static function page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$s     = Settings::get();
		$icons = Icons::get_picker_icons();
		?>
		<div class="wrap sla-admin">
			<div class="sla-admin__hero">
				<div class="sla-admin__hero-text">
					<h1><?php esc_html_e( 'Sarrloop Accessibility', 'sarrloop-accessibility' ); ?></h1>
					<p><?php esc_html_e( 'Configure the accessibility tools displayed to your website visitors.', 'sarrloop-accessibility' ); ?></p>
				</div>
				<span class="sla-version">v<?php echo esc_html( SARRLOOP_ACCESSIBILITY_VERSION ); ?></span>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( 'sarrloop_accessibility_group' ); ?>

				<div class="sla-card">
					<h2><?php esc_html_e( 'General Settings', 'sarrloop-accessibility' ); ?></h2>

					<label class="sla-switch-row">
						<span>
							<strong><?php esc_html_e( 'Enable accessibility widget', 'sarrloop-accessibility' ); ?></strong>
							<small><?php esc_html_e( 'Display the floating accessibility button on your public website.', 'sarrloop-accessibility' ); ?></small>
						</span>
						<input type="checkbox" name="<?php echo esc_attr( Settings::OPTION ); ?>[enabled]" value="1" <?php checked( $s['enabled'], 1 ); ?>>
					</label>

					<div class="sla-icon-picker">
						<p><strong><?php esc_html_e( 'Choose accessibility icon', 'sarrloop-accessibility' ); ?></strong></p>
						<div class="sla-icon-options">
							<?php foreach ( $icons as $icon_key => $icon_label ) : ?>
								<label class="sla-icon-choice <?php echo ( $s['button_icon'] === $icon_key ) ? 'is-selected' : ''; ?>">
									<input type="radio" name="<?php echo esc_attr( Settings::OPTION ); ?>[button_icon]" value="<?php echo esc_attr( $icon_key ); ?>" <?php checked( $s['button_icon'], $icon_key ); ?>>
									<span class="sla-icon-preview" aria-hidden="true">
										<?php Icons::render( $icon_key ); ?>
									</span>
									<span class="sla-icon-label"><?php echo esc_html( $icon_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
						<p class="description"><?php esc_html_e( 'Choose a built-in SVG icon for the floating accessibility button.', 'sarrloop-accessibility' ); ?></p>
					</div>

					<div class="sla-field-grid">
						<p>
							<label for="sla-button-display"><strong><?php esc_html_e( 'Button style', 'sarrloop-accessibility' ); ?></strong></label>
							<select id="sla-button-display" name="<?php echo esc_attr( Settings::OPTION ); ?>[button_display]">
								<option value="icon" <?php selected( $s['button_display'], 'icon' ); ?>><?php esc_html_e( 'Icon only (Recommended)', 'sarrloop-accessibility' ); ?></option>
								<option value="both" <?php selected( $s['button_display'], 'both' ); ?>><?php esc_html_e( 'Icon and text', 'sarrloop-accessibility' ); ?></option>
								<option value="text" <?php selected( $s['button_display'], 'text' ); ?>><?php esc_html_e( 'Text only', 'sarrloop-accessibility' ); ?></option>
							</select>
							<span class="description"><?php esc_html_e( 'Choose whether to display icon only, icon and text, or text only on the floating button.', 'sarrloop-accessibility' ); ?></span>
						</p>
						<p>
							<label for="sla-position"><strong><?php esc_html_e( 'Widget position', 'sarrloop-accessibility' ); ?></strong></label>
							<select id="sla-position" name="<?php echo esc_attr( Settings::OPTION ); ?>[position]">
								<option value="right" <?php selected( $s['position'], 'right' ); ?>><?php esc_html_e( 'Right', 'sarrloop-accessibility' ); ?></option>
								<option value="left" <?php selected( $s['position'], 'left' ); ?>><?php esc_html_e( 'Left', 'sarrloop-accessibility' ); ?></option>
							</select>
						</p>
						<p>
							<label for="sla-button-label"><strong><?php esc_html_e( 'Button label', 'sarrloop-accessibility' ); ?></strong></label>
							<input id="sla-button-label" type="text" class="regular-text" name="<?php echo esc_attr( Settings::OPTION ); ?>[button_label]" value="<?php echo esc_attr( $s['button_label'] ); ?>" placeholder="<?php esc_attr_e( 'Accessibility', 'sarrloop-accessibility' ); ?>">
							<span class="description"><?php esc_html_e( 'Used for screen reader accessibility and when text display is enabled.', 'sarrloop-accessibility' ); ?></span>
						</p>
						<p>
							<label for="sla-button-color"><strong><?php esc_html_e( 'Button color', 'sarrloop-accessibility' ); ?></strong></label>
							<input id="sla-button-color" type="color" name="<?php echo esc_attr( Settings::OPTION ); ?>[button_color]" value="<?php echo esc_attr( $s['button_color'] ); ?>">
						</p>
						<p>
							<label for="sla-design"><strong><?php esc_html_e( 'Widget design', 'sarrloop-accessibility' ); ?></strong></label>
							<select id="sla-design" name="<?php echo esc_attr( Settings::OPTION ); ?>[design]">
								<option value="sidebar" <?php selected( $s['design'], 'sidebar' ); ?>><?php esc_html_e( 'Sidebar — compact', 'sarrloop-accessibility' ); ?></option>
								<option value="card" <?php selected( $s['design'], 'card' ); ?>><?php esc_html_e( 'Card — rounded', 'sarrloop-accessibility' ); ?></option>
								<option value="minimal" <?php selected( $s['design'], 'minimal' ); ?>><?php esc_html_e( 'Minimal — round badge', 'sarrloop-accessibility' ); ?></option>
							</select>
						</p>
					</div>
				</div>

				<div class="sla-card">
					<h2><?php esc_html_e( 'Accessibility Features', 'sarrloop-accessibility' ); ?></h2>
					<p class="description"><?php esc_html_e( 'Select the accessibility tools available to visitors in the panel.', 'sarrloop-accessibility' ); ?></p>

					<div class="sla-features-list">
						<?php
						$features = array(
							'text_size'       => array(
								'title' => __( 'Text size', 'sarrloop-accessibility' ),
								'desc'  => __( 'Allows visitors to increase, decrease, or reset body text size.', 'sarrloop-accessibility' ),
								'icon'  => 'text_size',
							),
							'contrast'        => array(
								'title' => __( 'High contrast', 'sarrloop-accessibility' ),
								'desc'  => __( 'High-contrast black background with high visibility text.', 'sarrloop-accessibility' ),
								'icon'  => 'contrast',
							),
							'grayscale'       => array(
								'title' => __( 'Grayscale', 'sarrloop-accessibility' ),
								'desc'  => __( 'Desaturates all website colors to grayscale.', 'sarrloop-accessibility' ),
								'icon'  => 'grayscale',
							),
							'highlight_links' => array(
								'title' => __( 'Highlight links', 'sarrloop-accessibility' ),
								'desc'  => __( 'Underlines and highlights all hyperlinks.', 'sarrloop-accessibility' ),
								'icon'  => 'highlight_links',
							),
							'stop_animations' => array(
								'title' => __( 'Stop animations', 'sarrloop-accessibility' ),
								'desc'  => __( 'Halts CSS animations, transitions, and smooth scrolling.', 'sarrloop-accessibility' ),
								'icon'  => 'stop_animations',
							),
							'large_cursor'    => array(
								'title' => __( 'Large cursor', 'sarrloop-accessibility' ),
								'desc'  => __( 'Switches to an enlarged high-contrast mouse cursor.', 'sarrloop-accessibility' ),
								'icon'  => 'large_cursor',
							),
							'readable_font'   => array(
								'title' => __( 'Readable font', 'sarrloop-accessibility' ),
								'desc'  => __( 'Applies clean, highly legible system sans-serif typography.', 'sarrloop-accessibility' ),
								'icon'  => 'readable_font',
							),
							'focus'           => array(
								'title' => __( 'Enhanced focus', 'sarrloop-accessibility' ),
								'desc'  => __( 'Shows prominent outline indicators on focused elements.', 'sarrloop-accessibility' ),
								'icon'  => 'focus',
							),
							'tts'             => array(
								'title' => __( 'Read aloud', 'sarrloop-accessibility' ),
								'desc'  => __( 'Uses the browser Speech Synthesis API to read content aloud.', 'sarrloop-accessibility' ),
								'icon'  => 'tts',
							),
							'keyboard'        => array(
								'title' => __( 'Keyboard navigation', 'sarrloop-accessibility' ),
								'desc'  => __( 'Highlights and aids navigation using keyboard tab stops.', 'sarrloop-accessibility' ),
								'icon'  => 'keyboard',
							),
						);

						foreach ( $features as $key => $feature ) :
							?>
							<label class="sla-feature">
								<input type="checkbox" name="<?php echo esc_attr( Settings::OPTION ); ?>[features][<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $s['features'][ $key ] ) ); ?>>
								<span class="sla-feature__icon">
									<?php Icons::render( $feature['icon'] ); ?>
								</span>
								<span class="sla-feature__details">
									<strong><?php echo esc_html( $feature['title'] ); ?></strong>
									<small><?php echo esc_html( $feature['desc'] ); ?></small>
								</span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="sla-notice">
					<strong><?php esc_html_e( 'Accessibility Note', 'sarrloop-accessibility' ); ?></strong>
					<p><?php esc_html_e( 'These tools are designed to assist visitors. They do not automatically guarantee compliance with WCAG, ADA, EAA, or other accessibility regulations. Site owners are responsible for overall website accessibility.', 'sarrloop-accessibility' ); ?></p>
				</div>

				<?php submit_button( __( 'Save Settings', 'sarrloop-accessibility' ) ); ?>
			</form>
		</div>
		<?php
	}
}
