<?php
/**
 * Dependency Fixer class for CP Bricks Fixes.
 *
 * @package CP_Bricks_Fixes
 */

namespace CPBF;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dependency Fixer class.
 */
class DependencyFixer {

	/**
	 * Initialize the dependency fixer.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'fix_dependencies' ), 5 );
	}

	/**
	 * Fix dependencies for Bricks Builder.
	 */
	public static function fix_dependencies() {
		global $wp_scripts;

		$core_scripts = array(
			'wp-mediaelement',
			'wp-api-request',
			'wp-a11y',
			'wp-i18n',
		);

		foreach ( $core_scripts as $script ) {
			if ( ! wp_script_is( $script, 'enqueued' ) ) {
				wp_enqueue_script( $script );
			}
		}

		if ( ! wp_script_is( 'sortable-js', 'registered' ) ) {
			if ( wp_script_is( 'jquery-ui-sortable', 'registered' ) ) {
				$sortable_js = $wp_scripts->registered['jquery-ui-sortable'];
				wp_register_script(
					'sortable-js',
					$sortable_js->src,
					$sortable_js->deps,
					$sortable_js->ver,
					$sortable_js->args
				);
			} else {
				wp_register_script(
					'sortable-js',
					includes_url( 'js/sortable.min.js' ),
					array( 'jquery' ),
					get_bloginfo( 'version' ),
					true
				);
			}
		}

		if ( ! wp_script_is( 'media-models', 'registered' ) ) {
			wp_register_script(
				'media-models',
				includes_url( 'js/media-models.min.js' ),
				array( 'jquery', 'underscore' ),
				get_bloginfo( 'version' ),
				true
			);
		}

		if ( ! wp_script_is( 'wp-plupload', 'registered' ) ) {
			wp_register_script(
				'wp-plupload',
				includes_url( 'js/plupload.min.js' ),
				array( 'jquery' ),
				get_bloginfo( 'version' ),
				true
			);
		}

		$media_views_deps = array(
			'utils',
			'media-models',
			'wp-plupload',
			'sortable-js',
			'wp-mediaelement',
			'wp-api-request',
			'wp-a11y',
			'wp-i18n',
		);

		if ( isset( $wp_scripts->registered['media-views'] ) ) {
			$wp_scripts->registered['media-views']->deps = $media_views_deps;
		} else {
			wp_register_script(
				'media-views',
				includes_url( 'js/media-views.min.js' ),
				$media_views_deps,
				get_bloginfo( 'version' ),
				true
			);
		}

		wp_enqueue_script( 'media-views' );

		if ( ! wp_script_is( 'media-editor', 'enqueued' ) ) {
			wp_enqueue_script(
				'media-editor',
				includes_url( 'js/media-editor.min.js' ),
				array( 'shortcode', 'media-views', 'wp-i18n' ),
				get_bloginfo( 'version' ),
				true
			);
		}

		if ( ! wp_script_is( 'media-audiovideo', 'enqueued' ) ) {
			wp_enqueue_script(
				'media-audiovideo',
				includes_url( 'js/media-audiovideo.min.js' ),
				array( 'media-editor' ),
				get_bloginfo( 'version' ),
				true
			);
		}

		if ( ! wp_script_is( 'wp-api', 'enqueued' ) ) {
			wp_enqueue_script( 'wp-api' );
			wp_add_inline_script(
				'wp-api',
				'window.wp = window.wp || {}; wp.api = wp.api || {}; wp.api.models = wp.api.models || {};',
				'before'
			);
		}
	}
}
