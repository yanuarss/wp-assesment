<?php
/*
 * Plugin Name: SS Form
 */

 defined('ABSPATH') || exit();

 defined('SS_DIR') || define('SS_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
 defined('SS_URI') || define('SS_URI', plugin_dir_url(__FILE__));

require_once __DIR__ . '/includes/ss-shortcode.php';

// fire action when plugin activated
register_activation_hook( __FILE__, function() {
    do_action('ss_form_plugin_activated');
});
