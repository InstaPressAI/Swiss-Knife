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
			document.addEventListener("DOMContentLoaded", function () {
				const headerRight = document.querySelector(
					".site-header-primary-section-right.site-header-section"
				);
				let lastScrollTop = 0;

				window.addEventListener("scroll", function () {
					if (window.innerWidth < 921) {
						let scrollTop = window.scrollY || document.documentElement.scrollTop;

						// Scrolling down past 200px → hide
						if (scrollTop > lastScrollTop && scrollTop > 200) {
							headerRight.style.display = "none";
						}

						// Scrolling up above 200px → show
						else if (scrollTop < lastScrollTop && scrollTop > 200) {
							headerRight.style.display = "block";
						}

						// Reset if near top
						else if (scrollTop <= 200) {
							headerRight.style.display = "block";
						}

						lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // avoid negative
					} else {
						// Always visible on desktop
						headerRight.style.display = "block";
					}
				});
			});
		';

		// Register a dummy script handle
		wp_register_script( 'autowp-swiss-knife-inline-js', '', [], false, true );
		wp_enqueue_script( 'autowp-swiss-knife-inline-js' );
		wp_add_inline_script( 'autowp-swiss-knife-inline-js', $custom_js );
	} /* inline_scripts() */


} /* Font */
