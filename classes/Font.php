<?php

namespace AutoWPSWISSKnife;

use AutoWPSWISSKnife\Traits\Singleton;

/* Exit, if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	echo 'Hi there! I\'m just a part of plugin, not much I can do when called directly.';
	exit();
}

/**
  *
 */
class Font {

	use Singleton;

	/**
	 * Register the plugable methods.
	 *
	 * @access private
	 */
	private function plugables() {
		add_action( 'wp_enqueue_scripts', [ $this, 'fonts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline_scripts' ] ); // 🔥 added
	} /* plugables() */

	public function fonts() {
		wp_enqueue_style(
			'autowp-swiss-knife-fonts',
			plugin_dir_url( __FILE__ ) . '../assets/css/fonts.css'
		);
	} /* fonts() */

	public function inline_styles() {
		$get_font_family = get_option( 'ask_logo_font_family' );
		$get_font_size   = get_option( 'ask_logo_font_size' );

		$custom_css = "
			.site-header {
				position: sticky;
				top: 0;
				z-index: 999;
			}
			.site-title a {
				font-family: '{$get_font_family}';
				font-size: {$get_font_size};
			}

			@media (max-width: 921px) {
				.site-header-section {
					width: 150px;
				}
				.site-title a {
					font-size: 24px;
				}
			}
		";

		wp_register_style( 'autowp-swiss-knife-inline-css', false );
		wp_add_inline_style( 'autowp-swiss-knife-inline-css', $custom_css );
		wp_enqueue_style( 'autowp-swiss-knife-inline-css' );

	} /* inline_styles() */

	public function inline_scripts() {
		$custom_js = '
			window.addEventListener("scroll", function () {
				const mobileHeaderRight = document.querySelector(
					"#ast-mobile-header .site-header-primary-section-right"
				)

				const mobileHeaderRightBtn = document.querySelector(
					"#ast-mobile-header .site-header-primary-section-right .ast-button-wrap"
				)

				const desktopPhoneBlock = document.querySelector(
					"#ast-desktop-header .site-header-primary-section-right .ast-builder-html-element"
				)

				if (!mobileHeaderRight || !mobileHeaderRightBtn) return

				if (window.innerWidth <= 921) {
					if (window.scrollY <= 100) {
						mobileHeaderRightBtn.style.display = "flex"

						const phoneBlock = mobileHeaderRight.querySelector(".ast-builder-html-element")
						if (phoneBlock) {
							phoneBlock.parentElement.remove()
						} else {
							mobileHeaderRightBtn.style.display = "none"

							if (!mobileHeaderRight.querySelector(".ast-builder-html-element")) {
								mobileHeaderRight.insertAdjacentHTML(
									"afterbegin",
									desktopPhoneBlock.innerHTML
								)
							}
					}
				}
			})
		';

		// Register a dummy script handle
		wp_register_script( 'autowp-swiss-knife-inline-js', '', [], false, true );
		wp_enqueue_script( 'autowp-swiss-knife-inline-js' );
		wp_add_inline_script( 'autowp-swiss-knife-inline-js', $custom_js );
	} /* inline_scripts() */


} /* Font */
