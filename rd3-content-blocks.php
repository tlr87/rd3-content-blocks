<?php
/**
 * Plugin Name: RD3 Content Blocks
 * Description: Lightweight reusable content blocks for the Classic Editor.
 * Version: 1.1.0
 * Author: RD3 Tech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rd3_content_blocks_enqueue_styles() {

    wp_enqueue_style(
        'rd3-content-blocks-rows',
        plugin_dir_url( __FILE__ ) .
        'assets/rows.css',
        array(),
        '1.0.0'
    );
}

add_action(
    'wp_enqueue_scripts',
    'rd3_content_blocks_enqueue_styles'
);

/*
 * Load plugin files.
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';
