<?php
/**
 * CP Bricks Fixes.
 *
 * @package CP_Bricks_Fixes
 */

/**
 * Plugin Name:       CP Bricks Fixes
 * Plugin URI:        https://github.com/Hakira-Shymuy/cpbricksfixes
 * Description:       Some compatibility fixes for Bricks Builder in ClassicPress.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.3
 * Author:            Hakira Shymuy
 * Author URI:        https://github.com/Hakira-Shymuy/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/Hakira-Shymuy/cpbricksfixes
 * Text Domain:       cpbf
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CPBF_PATH', plugin_dir_path( __FILE__ ) );
define( 'CPBF_INCLUDES', CPBF_PATH . 'includes/' );

require_once CPBF_INCLUDES . 'Loader.php';

\CPBF\Loader::init();
