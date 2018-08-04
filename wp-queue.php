<?php
/**
 * WP_Queue Library.
 *
 * @package WP_Queue
 */

/*
---------------------------------------------------------------------------------------------------------------------
Plugin Name: WP Queue
Version: 0.0.1
Plugin URI:
Description: A plugin for background processes
Author: The WP Queue Team.
Author URI: https://www.wp-queue.com
Text Domain: wp-queue
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
----------------------------------------------------------------------------------------------------------------------
*/

require_once 'functions.php';

register_activation_hook( __FILE__, 'wp_queue_install_tables' );
register_activation_hook( __FILE__, 'wp_queue_options' );
