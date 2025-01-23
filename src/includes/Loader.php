<?php
/**
 * Loader class for CP Bricks Fixes.
 *
 * @package CP_Bricks_Fixes
 */

namespace CPBF;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Loader class.
 */
class Loader {

	/**
	 * Initialize the plugin.
	 */
	public static function init() {
		// Include other classes.
		require_once CPBF_INCLUDES . 'DependencyFixer.php';

		// Initialize classes.
		DependencyFixer::init();
	}
}
