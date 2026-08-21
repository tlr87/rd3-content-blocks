<?php

/**
 * RD3 Content Blocks
 *
 * Admin loader and menu.
 */


/*
 * Load Content Blocks administration.
 */
require_once plugin_dir_path( __FILE__ ) . 'blocks.php';


/*
 * Load Rows administration.
 */
require_once plugin_dir_path( __FILE__ ) . 'rows.php';


/*
 * Load Advanced Rows administration.
 */
require_once plugin_dir_path( __FILE__ ) . 'adv-rows.php';


/*
 * Load Advanced Row Page/Post selector.
 */
require_once plugin_dir_path( __FILE__ ) . 'advanced-row-page-selector.php';


/*
 * Load Classic Editor integration.
 */
require_once plugin_dir_path( __FILE__ ) . 'editor.php';


/*
 * Load How to Use administration.
 */
require_once plugin_dir_path( __FILE__ ) . 'help.php';


/*
 * Load usage functions.
 */
require_once plugin_dir_path( __FILE__ ) . 'usage.php';


/*
 * Load Used On columns and sorting.
 *
 * This file must be loaded after usage.php because
 * it uses the usage functions defined there.
 */
require_once plugin_dir_path( __FILE__ ) . 'sort.php';


/*
 * Register RD3 Content Blocks admin menu.
 */
function rd3_content_blocks_admin_menu() {

    /*
     * Main RD3 Content Blocks menu.
     */
    add_menu_page(
        'RD3 Content Blocks',
        'RD3 Content Blocks',
        'manage_options',
        'rd3-content-blocks',
        'rd3_content_blocks_admin_page',
        'dashicons-screenoptions',
        30
    );


    /*
     * How to Use.
     */
    add_submenu_page(
        'rd3-content-blocks',
        'How to Use',
        'How to Use',
        'manage_options',
        'rd3-content-blocks-usage',
        'rd3_content_blocks_usage_page'
    );
}

add_action(
    'admin_menu',
    'rd3_content_blocks_admin_menu',
    20
);


/*
 * Main RD3 Content Blocks admin page.
 *
 * Redirect to Content Blocks.
 */
function rd3_content_blocks_admin_page() {

    wp_safe_redirect(
        admin_url(
            'edit.php?post_type=rd3_content_block'
        )
    );

    exit;
}


