<?php
/**
 * Plugin Name: RD3 Content Blocks
 * Description: Lightweight reusable content blocks for the Classic Editor.
 * Version: 1.0.0
 * Author: RD3 Tech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Load plugin files.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';