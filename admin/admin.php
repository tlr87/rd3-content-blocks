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
 * Load Classic Editor integration.
 */
require_once plugin_dir_path( __FILE__ ) . 'editor.php';


/*
 * Load Usage / Help administration.
 */
require_once plugin_dir_path( __FILE__ ) . 'usage.php';


/*
 * Register RD3 Content Blocks admin menu.
 */
function rd3_content_blocks_admin_menu() {

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
 */
function rd3_content_blocks_admin_page() {

    ?>

    <div class="wrap">

        <h1>
            RD3 Content Blocks
        </h1>

        <p>
            Manage reusable Content Blocks
            and Rows for the RD3 website.
        </p>

        <p>

            <a
                href="<?php echo esc_url(
                    admin_url(
                        'edit.php?post_type=rd3_content_block'
                    )
                ); ?>"
                class="button button-primary"
            >
                Content Blocks
            </a>


            <a
                href="<?php echo esc_url(
                    admin_url(
                        'edit.php?post_type=rd3_row'
                    )
                ); ?>"
                class="button"
                style="margin-left:8px;"
            >
                Rows
            </a>

        </p>

    </div>

    <?php
}