<?php
/*
 ----------------------------------------------------------------------------------------------------------------------
  Plugin Name: WP Queue
  Version: 0.0.1
  Plugin URI:
  Description: A plugin for background processes
  Author: Brandon Hubbard
  Author URI: https://brandonhubbard.com
  Text Domain: wpqueue-restapi
  License: GPL v3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html

 ----------------------------------------------------------------------------------------------------------------------
*/

require_once 'src/functions.php';
require_once 'src/rest-api.php';
require_once 'src/cli-commands.php';

require_once 'src/debug-page.php';
require_once 'src/failure-page.php';

wp_queue_options();
register_activation_hook( __FILE__, 'wp_queue_install_tables' );