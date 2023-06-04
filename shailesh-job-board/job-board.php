<?php
/**
 * Plugin Name: Shailesh Job Board
 * Plugin URI: https://example.com
 * Description: The plugin can help you turn your website into Job Board.
 * Version: 1.0.0
 * Author: Shailesh
 * Text Domain: job-board
 * Domain Path: /i18n/languages/
 * Requires at least: 6.0
 * Requires PHP: 7.3
 *
 * @package JobBoard
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'JOB_BOARD_PLUGIN_FILE' ) ) {
	define( 'JOB_BOARD_PLUGIN_FILE', __FILE__ );
}

// Include the main class.
if ( ! class_exists( 'JobBoard\Init' ) ) {
	require_once dirname( JOB_BOARD_PLUGIN_FILE ) . '/includes/class-init.php';
	new JobBoard\Init();
}
