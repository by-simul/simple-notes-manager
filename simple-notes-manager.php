<?php 
/*
Plugin Name: Simple Notes Manager
Plugin URI: https://example.com
Description: A simple plugin to manage notes from admin and frontend.
Version: 1.0
Author: Mak
Author URI: https://example.com
Text Domain: simple-notes-manager
*/

if(!defined("ABSPATH")) exit;


require_once plugin_dir_path(__FILE__) . 'includes/class-activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-core.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-deactivator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-frontend.php';


register_activation_hook(__FILE__, array('Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Deactivator', 'deactivate'));

// Initialize Core
$core = new Core();
$core->run();




?>