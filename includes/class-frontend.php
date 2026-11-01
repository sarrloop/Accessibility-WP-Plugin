<?php
namespace Sarrloop\Accessibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the public frontend accessibility widget.
 */
class Frontend {

	/**
	 * Hook frontend actions.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render' ) );
	}

	/**
	 * Enqueue frontend CSS and JavaScript.
	 */
	public static function enqueue() {
		$settings = Settings::get();

		if ( empty( $settings['enabled'] ) ) {
			return;
		}

		wp_enqueue_style(
			'sarrloop-accessibility',
			SARRLOOP_ACCESSIBILITY_URL . 'public/css/accessibility.css',
			array(),
			SARRLOOP_ACCESSIBILITY_VERSION
		);

		wp_enqueue_script(
			'sarrloop-accessibility',
			SARRLOOP_ACCESSIBILITY_URL . 'public/js/accessibility.js',
			array(),
			SARRLOOP_ACCESSIBILITY_VERSION,
			true
		);

		$strings = I18n::get_frontend_strings();

		wp_localize_script(
			'sarrloop-accessibility',
			'SarrloopAccessibility',
			array(
				'storageKey'  => 'sarrloopAccessibility',
				'position'    => $settings['position'],
				'design'      => $settings['design'],
				'buttonColor' => $settings['button_color'],
				'features'    => $settings['features'],
				'siteLocale'  => I18n::get_locale(),
				'isRtl'       => I18n::is_rtl(),
				'strings'     => $strings,
			)
		);
	}

	/**
	 * Render the accessibility floating button and panel.
	 */
	public static function render() {
		$settings = Settings::get();

		if ( empty( $settings['enabled'] ) ) {
			return;
		}

		$is_rtl       = I18n::is_rtl();
		$rtl_class    = $is_rtl ? 'sla-is-rtl' : '';
		$button_label = I18n::get_button_label( $settings['button_label'] );
		$strings      = I18n::get_frontend_strings();

		$feature_items = array(
			'contrast'        => array(
				'label' => $strings['contrast'],
				'icon'  => 'contrast',
			),
			'grayscale'       => array(
				'label' => $strings['grayscale'],
				'icon'  => 'grayscale',
			),
			'highlight_links' => array(
				'label' => $strings['links'],
				'icon'  => 'highlight_links',
			),
			'stop_animations' => array(
				'label' => $strings['animations'],
				'icon'  => 'stop_animations',
			),
			'large_cursor'    => array(
				'label' => $strings['cursor'],
				'icon'  => 'large_cursor',
			),
			'readable_font'   => array(
				'label' => $strings['font'],
				'icon'  => 'readable_font',
			),
			'focus'           => array(
				'label' => $strings['focus'],
				'icon'  => 'focus',
			),
			'tts'             => array(
				'label' => $strings['read'],
				'icon'  => 'tts',
			),
			'keyboard'        => array(
				'label' => $strings['keyboard'],
				'icon'  => 'keyboard',
			),
		);
		?>
		<div class="sla-widget sla-widget--<?php echo esc_attr( $settings['position'] ); ?> sla-widget--<?php echo esc_attr( $settings['design'] ); ?> <?php echo esc_attr( $rtl_class ); ?>" style="--sla-brand: <?php echo esc_attr( $settings['button_color'] ); ?>;" data-sla-widget>
			<button type="button" class="sla-toggle sla-toggle--<?php echo esc_attr( $settings['button_display'] ); ?>" aria-expanded="false" aria-controls="sla-panel" aria-label="<?php echo esc_attr( $button_label ); ?>" title="<?php echo esc_attr( $button_label ); ?>">
				<?php if ( 'text' !== $settings['button_display'] ) : ?>
					<span class="sla-toggle__icon"><?php Icons::render( $settings['button_icon'] ); ?></span>
				<?php endif; ?>
				<?php if ( 'icon' !== $settings['button_display'] ) : ?>
					<span class="sla-toggle__label"><?php echo esc_html( $button_label ); ?></span>
				<?php else : ?>
					<span class="sla-sr-only"><?php echo esc_html( $button_label ); ?></span>
				<?php endif; ?>
			</button>

			<section id="sla-panel" class="sla-panel" hidden aria-label="<?php echo esc_attr( $strings['options'] ); ?>">
				<div class="sla-panel__header">
					<div class="sla-panel__title">
						<span class="sla-panel__title-icon"><?php Icons::render( $settings['button_icon'] ); ?></span>
						<h2><?php echo esc_html( $strings['accessibility'] ); ?></h2>
					</div>
					<button type="button" class="sla-close" aria-label="<?php echo esc_attr( $strings['close'] ); ?>" title="<?php echo esc_attr( $strings['close'] ); ?>">
						<span class="sla-close__icon"><?php Icons::render( 'close' ); ?></span>
					</button>
				</div>

				<div class="sla-panel__body">
					<?php if ( ! empty( $settings['features']['text_size'] ) ) : ?>
						<div class="sla-control sla-control--text-size">
							<span class="sla-control__title"><?php echo esc_html( $strings['textSize'] ); ?></span>
							<div class="sla-buttons">
								<button type="button" data-sla-action="decrease-text" aria-label="<?php echo esc_attr( $strings['decrease'] ); ?>" title="<?php echo esc_attr( $strings['decrease'] ); ?>">
									<span>A−</span>
								</button>
								<button type="button" data-sla-action="reset-text" aria-label="<?php echo esc_attr( $strings['resetText'] ); ?>" title="<?php echo esc_attr( $strings['resetText'] ); ?>">
									<span>A</span>
								</button>
								<button type="button" data-sla-action="increase-text" aria-label="<?php echo esc_attr( $strings['increase'] ); ?>" title="<?php echo esc_attr( $strings['increase'] ); ?>">
									<span>A+</span>
								</button>
							</div>
						</div>
					<?php endif; ?>

					<div class="sla-grid">
						<?php foreach ( $feature_items as $key => $item ) : ?>
							<?php if ( ! empty( $settings['features'][ $key ] ) ) : ?>
								<button type="button" class="sla-option" data-sla-action="<?php echo esc_attr( $key ); ?>" aria-pressed="false">
									<span class="sla-option__icon"><?php Icons::render( $item['icon'] ); ?></span>
									<span class="sla-option__text"><?php echo esc_html( $item['label'] ); ?></span>
								</button>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>

					<button type="button" class="sla-reset" data-sla-action="reset">
						<span class="sla-reset__icon"><?php Icons::render( 'reset' ); ?></span>
						<span><?php echo esc_html( $strings['reset'] ); ?></span>
					</button>
				</div>
			</section>
		</div>
		<?php
	}
}
