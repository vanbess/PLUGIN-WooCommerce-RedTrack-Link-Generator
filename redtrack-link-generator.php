<?php

/**
 * Plugin Name: SBWC Redtrack Link Generator
 * Description: Campaign link generator for Redtrack
 * Author: WC Bessinger @ Smartecomm
 * Version: 1.0.0
 */

defined('ABSPATH') || exit();

add_action('plugins_loaded', 'redtrack_init');

function redtrack_init()
{

    define('REDTRACK_PATH', plugin_dir_path(__FILE__));
    define('REDTRACK_URL', plugin_dir_url(__FILE__));

    // cpt
    include REDTRACK_PATH.'admin/cpt.php';

    // generator
    include REDTRACK_PATH.'admin/generator.php';

    // settings
    include REDTRACK_PATH.'admin/settings.php';
}
